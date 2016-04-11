var baseUrl = 'http://localhost/tictactoechat/public/';
var dir = '../resources/assets/js/angular/'
var pusher = new Pusher('abb96bf6b157928ed5cc');
var chatChannel = pusher.subscribe('chat');
var roomChannel = pusher.subscribe('room');
var lobyControllers = angular.module('lobyControllers', []);

lobyControllers.controller('lobyCtrl', ['$scope', '$routeParams', '$http', '$location', '$anchorScroll', '$mdDialog', '$rootScope',
	function($scope, $routeParams, $http, $location, $anchorScroll, $mdDialog ,$rootScope) {
	$http(makeRequest(baseUrl+'profile', 'GET', {})). success(function(data){
		$rootScope.email = data.email;
	})
	$http(makeRequest(baseUrl+'room', 'GET', {})).success(function(data){				
		$scope.rooms = data;		
	});
	$scope.chats = [];
	chatChannel.bind('message', function(data) {		
    	$scope.chats.push({
    		user : data.user,
    		message : data.message,
    	});
    	$scope.$apply();    	
    	$location.hash('bottom');      	
      	$anchorScroll();
	});
	roomChannel.bind('room', function(data){		
		$scope.rooms = data;		
		$scope.$apply();				
	});	

	$scope.openMakeRoomForm = function () {
		$modal.open({
			templateUrl : dir+'partials/make_room_modal.html',
			controller : 'lobyCtrl',
			scope : $scope,
		});
	}

	$scope.submit = function(){
		if ($scope.chat != '')
		$http(makeRequest(baseUrl, 'POST', {message : $scope.chat}));
		$scope.chat = '';
	}

	$scope.chooseRoom = function (ev,id){
		var content = {
			placeholder 	: 'Password',
			ariaLabel 		: 'Password',
			title 			: 'Join Room',
			textContent 	: 'Password Required',			
			ok 				: 'Submit',
			cancel 			: 'Cancle',
		}
		if ($scope.rooms[id]['isPassword'] == 1){

			dialogPrompt($mdDialog, ev, content, function (password) {				
				$http(makeRequest(baseUrl+'join_room', 'POST', {id : id, password:password})).success(function(status){					
					if (status == true)
						var success = {
							title 		: 'Success',
							textContent : 'You Will Redirect To The Game ... ',
							ok 			: 'Ok',
					}
					else 
						var failed = {
							title 		: 'Failed',
							textContent : 'Wrong Password/ Room Full / You Have A Game',
							ok 			: 'Ok'
						}					
					if (status == true)
						dialogConfirm($mdDialog, ev, success, function () {
							console.log('redirect');
						});
					else
						dialogConfirm($mdDialog, ev, failed, function () {
							console.log('failed');
						});
				});					
			})
		}
		else{			
			$http(makeRequest(baseUrl+'join_room', 'POST', {id : id, password:''})).success(function(data){
				
			});
		}					
	}

	$scope.makeRoom = function (ev) {
		var content = {
			placeholder 	: 'Password',
			ariaLabel 		: 'Password',
			title 			: 'Create New Room',
			textContent 	: 'Using Password If You Want ... ',			
			ok 				: 'Submit',
			cancel 			: 'Cancle',
		}		
		dialogPrompt($mdDialog, ev, content, function(password){
			$http(makeRequest(baseUrl+'make_room', 'POST', {password:password })).success(function(data){
				if (data == 0){
				var status = {
						title 		: 'Failed!!!',
						textContent : 'You Have A Game',
						ok 			: 'Ok',
					}
				}
				else{
				var status = {
						title 		: 'Success!!!',
						textContent : 'You Just Create New Room',
						ok 			: 'Ok',

					}
				}
				dialogConfirm($mdDialog, ev, status, function () {					
				});
			});
		});
	}	
}]);


lobyControllers.controller('gameCtrl', ['$scope', '$routeParams', '$http',
	function($scope, $routeParams, $http){
		$http(makeRequest(baseUrl+'game', 'GET', {})).success(function(data){
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

function dialogConfirm($mdDialog, ev, content, callback){
	var dialogContent = $mdDialog.alert()
	.title(content.title)
	.textContent(content.textContent)
	.ariaLabel('')
	.targetEvent(ev)		
	.ok(content.ok);
	$mdDialog.show(dialogContent).then(function () {
		callback();
	});	
}

function dialogPrompt($mdDialog, ev, content, callback){
	var dialogContent = $mdDialog.prompt()
	.placeholder(content.placeholder)
	.ariaLabel(content.ariaLabel)
	.title(content.title)
	.textContent(content.textContent)
	.targetEvent(ev)
  	.ok(content.ok)
  	.cancel(content.cancel);
  	$mdDialog.show(dialogContent).then(function (password) {
  		callback(password);
  	})	      		      	
}

lobyControllers.directive('ngEnter', function(){
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
