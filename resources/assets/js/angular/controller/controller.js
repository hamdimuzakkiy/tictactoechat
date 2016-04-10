var baseUrl = 'http://localhost/tictactoechat/public/';
var pusher = new Pusher('abb96bf6b157928ed5cc');
var chatChannel = pusher.subscribe('chat');
var roomChannel = pusher.subscribe('room');
var gameControllers = angular.module('gameControllers', []);

gameControllers.controller('lobyCtrl', ['$scope', '$routeParams', '$http',
	function($scope, $routeParams, $http) {
	$http(makeRequest(baseUrl+'/room', 'GET', {})).success(function(data){		
		$scope.rooms = data;		
	});
	$scope.chats = [];
	chatChannel.bind('message', function(data) {		
    	$scope.chats.push({
    		user : data.user,
    		message : data.message,
    	});
    	$scope.$apply();
	});
	roomChannel.bind('room', function(data){		
		$scope.rooms = data;
		$scope.$apply();
	});	

	$scope.submit = function(){
		if ($scope.chat != '')
		$http(makeRequest(baseUrl, 'POST', {message : $scope.chat}));
		$scope.chat = '';
	}

	$scope.makeRoom = function(){
		$http(makeRequest(baseUrl+'make_room', 'POST', {name:$scope.name, $password:$scope.password})).success(function(data){
			console.log(data);
		});
	}

	$scope.chooseRoom = function (id){
		$http(makeRequest(baseUrl+'join_room', 'POST', {id : id})).success(function(data){
			console.log(data);
		});
	}
}]);


gameControllers.controller('gameCtrl', ['$scope', '$routeParams', '$http',
	function($scope, $routeParams, $http){
		$http(makeRequest(baseUrl+'/game', 'GET', {})).success(function(data){
			console.log(data.length);
		});
}]);

function makeRequest(url, method ,data){
	return {
		method : method,
		url : url,
		headers: {
      		'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    	},
    	data : data,
	};
}

gameControllers.directive('ngEnter', function(){
	return function(scope, element, attrs){
		element.bind("keydown press", function (event){
			if (event.which == 13){
				scope.$apply(function(){
					scope.$eval(attrs.ngEnter);
				});
				event.preventDefault();
			}
		})
	}
});