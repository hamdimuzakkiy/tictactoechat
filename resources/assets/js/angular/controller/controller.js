var baseUrl = 'http://localhost/tictactoechat/public/';
var pusher = new Pusher('abb96bf6b157928ed5cc');
var channel = pusher.subscribe('chat');
var gameControllers = angular.module('gameControllers', []);

// gameControllers.controller('lobyCtrl',['$scope'
// 	function ($scope, $http){	
// 		$scope.name = 'hamdi';
// }]);

// phonecatControllers.controller('PhoneListCtrl', ['$scope', '$http',
//   function ($scope, $http) {
//     $http.get('phones/phones.json').success(function(data) {
//       $scope.phones = data;
//     });

//     $scope.orderProp = 'age';
//   }]);

gameControllers.controller('lobyCtrl', ['$scope', '$routeParams', '$http',
	function($scope, $routeParams, $http) {
	$scope.chats = [];

	channel.bind('message', function(data) {		
    	$scope.chats.push({
    		user : data.user,
    		message : data.message,
    	});
    	$scope.$apply();
	});
	$scope.submit = function(){
		if ($scope.chat != '')
		$http(makeRequest(baseUrl, 'POST', {message : $scope.chat}));
		$scope.chat = '';
	}
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