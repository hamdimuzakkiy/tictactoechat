var dir = '../resources/assets/js/angular/'

var tictactoeApp = angular.module('tictactoeApp', [
  'ngRoute',    
  'ngMaterial',
  'lobyControllers',  
])
.config(function ($mdThemingProvider,$mdIconProvider) {
    $mdThemingProvider.theme('default')
    .primaryPalette('deep-purple',{
        'default' : '400',
        'hue-1' : '100',
    })
    .accentPalette('pink');    
})

tictactoeApp.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/', {
        templateUrl: dir+'partials/loby.html',
        controller: 'lobyCtrl'
      }).
      when('/create_room/', {
        templateUrl: dir+'partials/modal.html',
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