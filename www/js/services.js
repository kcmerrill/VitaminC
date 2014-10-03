'use strict';

angular.module('vitaminc.services', []).
  factory('TestRunner', function(Project){
    return {
        ready: false,
        project: Project,
        init: function(success_callback){
            this.project.init(success_callback);
            this.ready = true;
        },
        clear: function(){
            this.project.clear();
        }
    };
  }).
  factory('Tests', function($http, $location, $timeout){
    return {
        files:[],
        ignore_files: [],
        test_count: 0,
        assertion_count: 0,
        status: false,
        error_message: '',
        raw_output: '',
        timer:false,
        project_name: false,
        files_searched_for: [],
        to_search_for: '',
        init: function(project){
            var self = this;
            self.files = [];
            self.project_name = project.project;
            _.each(project.tests, function(file){
                file.status = 'running';
                self.files.push(file);
            });
            self.ignore_files = project.ignore_files;
            if(self.timer) {
               $timeout.cancel(self.timer);
            }
            self.timer = $timeout(function(){ self.checkForModifications(self.project_name); } , 2000);
            self.startTesting();
        },
        checkForModifications: function(project_name){
            var self = this;
            $http.get('projects/' + project_name + '/files/modified/2secondsago').
            success(function(data){
                $timeout.cancel(self.timer);
                if(data.length){
                    self.startTesting();
                }
                self.timer = $timeout(function(){ self.checkForModifications(self.project_name); } , 2000);
            });
        },
        clear: function(){
           var self = this;
           $timeout.cancel(self.timer);
           self.project_name = false;
           self.timer = false;
           self.error_message = '';
           self.status = false;
           self.files = [];
           self.files_searched_for = [];
        },
        startTesting: function(){
           var self = this;
           self.status = 'running';
           self.test_count = self.assertion_count = 0;
           self.files = _.sortBy(self.files, function(file){
                return file.pass == false ? 2 : 1;
           });
           _.each(self.files, function(file, index){
                self.test(index, file);
           });
        },
        test: function(index, file){
            var self = this;
            self.files[index].status = 'running';
            $http.get('/test?file=' + file.full_path).
            success(function(data){
                if(self.status == 'running'){
                    self.status = data.pass;
                } else {
                    self.status = data.pass ? self.status : false;
                }
                self.files[index].status = 'finished';
                self.files[index].pass = data.pass;
                self.files[index].fail = data.fail;
                self.files[index].raw_output = data.raw_output;
                if(!data.pass && !data.fail){
                    self.error_message = data.raw_output;
                    self.raw_output = data.raw_output;
                } else if (!data.pass) {
                    self.error_message = data.error_message;
                    self.raw_output = data.raw_output;
                }
                self.test_count += data.stats.test_count;
                self.assertion_count += data.stats.assertion_count;
            });;
        },
        search: function(query){
            var self = this;
            self.files_searched_for = [];
            $http.get('projects/' + self.project_name + '/files/search?query=' + query).
            success(function(data){
                self.files_searched_for = data;
            });
        },
        add: function(file, success_callback){
            var self = this;
            self.files.push(file);
            success_callback();
        },
        delete: function(full_path, success_callback){
            var self = this;
            self.files = _.filter(self.files, function(file){
                return file.full_path != full_path;
            });
            success_callback();
        }
    }
  }).
  factory('Project', function($http, Tests){
    return {
        available: [],
        tests: Tests,
        selected: false,
        init: function(success_callback){
           var self = this;
           $http.get('/projects').success(function(data){
                self.available = data;
                success_callback();
           });
        },
        delete: function(project_name){
            var self = this;
            self.available = _.filter(self.available, function(p){
                return p.project != project_name;
            });
            $http.delete('/projects/' + project_name);
        },
        addTest: function(file){
            var self = this;
            self.tests.add(file, function(){
                self.update();
                self.tests.startTesting();
            });
        },
        deleteTest: function(full_path){
            var self = this;
            self.tests.delete(full_path, function(){
                self.update();
                self.tests.startTesting();
            });
        },
        select: function(project_name){
            var self = this;
            var project = _.findWhere(self.available, {project : project_name});
            if(project !== undefined){
                self.selected = project;
                self.tests.init(project);
            }
        },
        update: function(){
            var self = this;
            self.selected.tests = self.tests.files;
            $http.put('/projects/' + self.selected.project, self.selected).
            success(function(data){
                self.available = data;
            });
        },
        createProject: function(){
            var self = this;
            $http.put('/projects/' + self.selected.project, self.selected).
            success(function(data){
                self.available = data;
                self.select(self.selected.project);
            });
        },
        clear: function(){
            var self = this;
            self.tests.clear();
            self.selected = false;
        }
    };
  });
