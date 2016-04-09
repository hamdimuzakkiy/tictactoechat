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
      when('/create_room/', {
        templateUrl: dir+'partials/cereate-room.html',
        controller: 'createRoomCtrl'
      }).      
      when('/game/', {
        templateUrl : dir+'partials/game.html',
        controller: 'gameCtrl'
      }).
      otherwise({
        redirectTo: '/'
      });
  }]);