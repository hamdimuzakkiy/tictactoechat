var dir = '../resources/assets/js/angular/'
var pusher = new Pusher('abb96bf6b157928ed5cc');
var gameControllers = angular.module('gameControllers', []);
var subscribe = '';
var account = '';

gameControllers.controller('boardCtrl', ['$scope', '$routeParams', '$http', '$location', '$anchorScroll', '$mdDialog', '$rootScope', '$window',
	function($scope, $routeParams, $http, $location, $anchorScroll, $mdDialog ,$rootScope, $window) {
	
	$http(makeRequest(baseUrl+'profile', 'GET', {})). success(function(profile){
		account = profile;			
		$rootScope.email = profile.email;
		$http(makeRequest(baseUrl+'game', 'GET', {})).success(function(data){
			if (data.length == 0 || data[0] == null){
				var content = {
					title 		: 'Warning !!!',
					textContent : "You Didn't Have Any Game",
					ok 			: 'Ok',
				};
				dialogConfirm($mdDialog, '', content, function(){
					$window.location.href = '#/';
				});
			}	
			else if (data[1].opponent == ''){
				$scope.turn = 'Waiting For Opponent ... ';
				var gameChannel = pusher.subscribe(data[1].subscribe);
				gameChannel.bind('message', function(data){					
					updateAll(data, $scope);
				});
			}		
			else{
				$scope.movements = data[1].movements;				
				if (Object.keys($scope.movements).length%2==0)
				$scope.turn = addTurn(data[1]['creator']);
				else
				$scope.turn = addTurn(data[1]['opponent']);
				$scope.movements = transleteMovement(data[1]);
				var gameChannel = pusher.subscribe(data[1].subscribe);
				gameChannel.bind('message', function(data){					
					updateAll(data, $scope);
				});
			}
		});
		$scope.choosing = function (choice){
			$http(makeRequest(baseUrl+'turn', 'POST', {tile:choice})). success(function(result){
				
			});
		}
	});
}]);

function addTurn(text){
	return text+' Turn';	
}

function updateAll(data, $scope){	
	if (Object.keys(data['movements']).length%2==0)		
		$scope.turn 		= addTurn(data['creator']);
	else
		$scope.turn 		= addTurn(data['opponent']);
	$scope.movements	= transleteMovement(data);	
	xoro();	
	$scope.$apply();	
}

function transleteMovement(game){
	var movements = game['movements'];
	var boardInit = {
		A:{
			A1 : '',
			A2 : '',
			A3 : '',
		},
		B:{
			B1 : '',
			B2 : '',
			B3 : '',
		},
		C:{
			C1 : '',
			C2 : '',
			C3 : '',
		}
	}	
	for (movement in movements){			
		boardInit[movement[0]][movement] = xoro(movements[movement], game['creator']);			
	}	
	return boardInit;
}

function xoro(player, creator){	
	if (creator == player)
		return 'X';
	return 'O';
}