'use strict';

/* Controllers */
angular.module('vitaminc.controllers', []).
  controller('LoadCtrl', function($scope, TestRunner, $location){
    TestRunner.init(function(){
        $location.path('/projects');
    });
  }).
 controller('MenuCtrl', function($scope, $location) {
    $scope.goto = function(where){
        $location.path('/' + where);
    };
 }).
 controller('ProjectsCtrl', function($scope, TestRunner, $location) {
    $scope.tr = TestRunner;
    $scope.project_list = false;
    $scope.project_filter = '';
    $scope.project_base_dir = '';
    $scope.project_ignore_list = $scope.tr.project.tests.ignore_list;

    $scope.saveProject = function(){
        if($scope.new_project){
            $scope.createProject();
            $scope.new_project = false;
        } else {
            $scope.tr.project.update();
        }
    };

    $scope.createProjectInit = function(){
        $scope.new_project = true;
        $scope.edit_project = true;
        $scope.tr.project.selected = {};
    };
    $scope.createProject = function(){
        $scope.project_list = false;
        $scope.project_filter = '';
        $scope.tr.project.createProject();
    };

    $scope.toggleDropdown = function(){
        $scope.project_list = !$scope.project_list;
        $scope.project_filter = '';
    };

    $scope.selectProject = function(project_name){
        $scope.tr.project.select(project_name);
        $scope.project_list = false;
    };

    $scope.search = function(query){
        $scope.tr.project.tests.search(query);
    };

    $scope.clear = function(){
        $scope.tr.clear();
        $scope.edit_project = false;
    };

    /* Make sure we've loaded appropriatly */
    if(!$scope.tr.ready){
        $location.path('/load');
    }
  }).
  controller('TestResultsCtrl', function($scope, TestRunner){
    $scope.tr = TestRunner;
  }).
 controller('TestRunnerCtrl', function($location, $scope, TestRunner) {
    $scope.tr = TestRunner;
  });
