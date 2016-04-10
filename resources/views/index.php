<!doctype html>
<html lang="en" ng-app="tictactoeApp">
	<head>	
	  	<meta charset="utf-8">
	  	<title>My HTML File</title>	
	  	<!-- jquery -->
		<?php print Html::script('../resources/assets/js/angular/bower_components/jquery/dist/jquery.min.js') ?>
	  	
	  	<!-- pusher -->
		<?php print Html::script('../resources/assets/js/pusher.min.js') ?>		

		<!-- angular -->
		<?php print Html::script('../resources/assets/js/angular/bower_components/angular/angular.js') ?>
		<?php print Html::script('../resources/assets/js/angular/bower_components/angular-route/angular-route.js') ?>
		<?php print Html::script('../resources/assets/js/angular/app.js') ?>
		<?php print Html::script('../resources/assets/js/angular/controller/controller.js') ?>				

		<!-- materialize -->
		<?php print Html::script('../resources/assets/js/angular/bower_components/angular-materialize/src/angular-materialize.js') ?>		
		<?php print Html::script('../resources/assets/js/angular/bower_components/angular-material-icons/angular-material-icons.css') ?>		
		<?php print Html::script('../resources/assets/js/angular/bower_components/angular-material-icons/angular-material-icons.min.js') ?>				

		<?php print Html::style('../resources/assets/css/materialize/css/materialize.min.css') ?>
		<?php print Html::script('../resources/assets/css/materialize/js/materialize.min.js') ?>		
	</head>
	<body>
		<nav>
			<div class="nav-wrapper">
			  <a href="#" class="brand-logo center">Tic Tac Toe</a>
			  <ul id="nav-mobile" class="right hide-on-med-and-down">
			    <li><a ng-href="#/">Loby</a></li>
			    <li><a ng-href="#/create_room">Create Room</a></li>
			    <li><a ng-href="#/game">Game</a></li>
			  </ul>
			</div>
		</nav>
		<div ng-view></div>
	</body>
</html>



