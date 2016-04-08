var dir = '../resources/assets/js/angular/'

var tictactoeApp = angular.module('tictactoeApp', [
  'ngRoute',
  'gameControllers'
]);

tictactoeApp.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/', {
        templateUrl: dir+'partials/loby.html',
        controller: 'lobyCtrl'
      }).
      when('/phones2/', {
        templateUrl: dir+'partials/phone-detail.html',
        controller: 'PhoneDetailCtrl'
      }).
      otherwise({
        redirectTo: '/phones'
      });
  }]);