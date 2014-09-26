'use strict';


// Declare app level module which depends on filters, and services
angular.module('vitaminc', [
  'ngRoute',
  'vitaminc.services',
  'vitaminc.controllers'
]).
config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/load', {templateUrl: 'partials/credits.html', controller: 'LoadCtrl'});
  $routeProvider.when('/debug', {templateUrl: 'partials/debug.html', controller: 'TestResultsCtrl'});
  $routeProvider.when('/projects', {templateUrl: 'partials/projects2.html', controller: 'ProjectsCtrl'});
  $routeProvider.otherwise({redirectTo: '/load'});
}]);
