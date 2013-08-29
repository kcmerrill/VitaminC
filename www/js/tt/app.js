var tt = angular.module('TeamTest', [], function ($httpProvider) {
    // Use x-www-form-urlencoded Content-Type
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

    // Override $http service's default transformRequest
    $httpProvider.defaults.transformRequest = [function (data) {
        /**
         * The workhorse; converts an object to x-www-form-urlencoded serialization.
         * @param {Object} obj
         * @return {String}
         */
        var param = function (obj) {
            var query = '';
            var name, value, fullSubName, subName, subValue, innerObj, i;

            for (name in obj) {
                value = obj[name];

                if (value instanceof Array) {
                    for (i = 0; i < value.length; ++i) {
                        subValue = value[i];
                        fullSubName = name + '[' + i + ']';
                        innerObj = {};
                        innerObj[fullSubName] = subValue;
                        query += param(innerObj) + '&';
                    }
                }
                else if (value instanceof Object) {
                    for (subName in value) {
                        subValue = value[subName];
                        fullSubName = name + '[' + subName + ']';
                        innerObj = {};
                        innerObj[fullSubName] = subValue;
                        query += param(innerObj) + '&';
                    }
                }
                else if (value !== undefined && value !== null) {
                    query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
                }
            }

            return query.length ? query.substr(0, query.length - 1) : query;
        };

        return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
    }];
})
    .config(function ($interpolateProvider) {
        $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
    });
tt.factory('states', function () {
    return {
        "file_list": false,
        "modal": true,
        "ready": false,
        "projects": true,
        "file_text": '',
        "settings": false
    }
});

tt.factory('projects', function ($http, states) {
    return {
        selected: undefined,
        all: {},
        fetchAll: function () {
            var self = this;
            $http({method: 'GET', url: '/index.php/project/all'})
                .success(function (data) {
                    self.all = data;
                    if (self.selected != undefined) {
                        self.selected = _.findWhere(self.all, {file: self.selected.file})
                    }
                    if (!_.size(self.all)) {
                        states.settings = true;
                    }
                    if (_.size(self.all) >= 1 && !_.size(self.selected)) {
                        for (var key in self.all)
                            self.selected = self.all[key];
                        states.ready = true;
                    }
                });
        },
        addTest: function (file) {
            console.log('Trying to addTest: ', file);
            var self = this;
            $http({method: 'POST', data: {'test': file}, url: '/index.php/project/test/' + self.selected.file})
                .error(function (data) {
                })
                .success(function (data) {
                    self.fetchAll();
                });
        },
        deleteTest: function (test) {
            var self = this;
            $http({method: 'DELETE', url: '/index.php/project/test/' + self.selected.file + '/' + test._id})
                .success(function (data) {
                    self.fetchAll();
                });
        },
        create: function (name, basepath) {
            var self = this;
            $http({method: 'POST', data: {'name': name, 'basepath': basepath}, url: '/index.php/project'})
                .success(function (data) {
                    self.fetchAll();
                });
        }
    }
});

function masterCtrl($scope, $http, $timeout, states, projects) {
    $scope.states = states;
    $scope.projects = projects;
    $scope.epoch_time = 0;
    $scope.every = 2000;

    $scope.addTest = function () {
        $scope.states.file_list = true;
        $scope.states.projects = false;
    }

    $scope.delete = function (test) {
        $scope.projects.deleteTest(test);
    };

    $scope.runTests = function () {
        console.log('Running tests', $scope.projects.selected.tests);
        _.each($scope.projects.selected.tests, function (test, idx) {
            $scope.projects.selected.tests[idx].state = 'running';
        })
    }

    $scope.poll = function (epoch_time) {
        if ($scope.projects.selected == undefined) {
            $timeout($scope.poll, $scope.every);
        } else {
            /** only run this if there is a project selected **/
            $http({method: 'GET', url: '/index.php/files/modified/' + epoch_time + '/' + $scope.projects.selected.name})
                .success(function (data) {
                    if (data.modified) {
                        $scope.runTests();
                    }
                    $timeout(function () {
                        $scope.poll(data.time);
                    }, $scope.every);
                });
        }
    };
    $scope.poll($scope.epoch_time);
}

function projectCtrl($scope, $http, states, projects) {
    $scope.projects = [];
    $scope.projects = projects;
    $scope.states = states;

    $scope.selectProject = function (project) {
        $scope.states.ready = true;
        $scope.projects.selected = project;
    }

    /** Load automagically if no project is currently set **/
    if (!_.size($scope.projects.all)) {
        $scope.projects.fetchAll();
    }
}

function filesCtrl($scope, $http, states, projects) {
    $scope.states = states;
    $scope.files = [];
    $scope.query = "";
    $scope.projects = projects;


    $scope.search = function () {
        if (_.isEmpty($scope.query)) {
            $scope.files = [];
            $scope.states.file_text = '';
            return true;
        }
        $http({method: 'GET', url: '/index.php/files/' + encodeURIComponent($scope.query.replace('/', '[[..........]]')) + '/' + $scope.projects.selected.file})
            .error(function (data) {
                $scope.states.file_text = "Your basepath cannot be found, please update " + $scope.projects.selected.file;
            })
            .success(function (data) {
                $scope.files = data;
                $scope.states.file_text = _.size(data) + ' files matched "' + $scope.query + '"';
            });
    }

    $scope.addTest = function (file) {
        $scope.projects.addTest(file);
        $scope.cancel();
    }

    $scope.cancel = function () {
        $scope.files = [];
        $scope.query = "";
        $scope.states.file_text = "";
        $scope.states.file_list = false;
        $scope.states.projects = true;
    }
}

function settingsCtrl($scope, $http, states, projects) {
    $scope.states = states;
    $scope.projects = projects;
    $scope.p_name = "";
    $scope.p_basepath = "";

    $scope.save = function () {
        projects.create($scope.p_name, $scope.p_basepath);
        states.settings = false;
    }
}
