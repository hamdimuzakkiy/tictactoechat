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
		<?php print Html::script('../resources/assets/js/angular/controller/lobyController.js') ?>				

		<!-- material design -->
		<?php print Html::style('../resources/assets/js/angular/bower_components/angular-material/angular-material.min.css') ?>		
		<?php print Html::script('../resources/assets/js/angular/bower_components/angular-material/angular-material.min.js') ?>		
		<?php print Html::script('../resources/assets/js/angular/bower_components/angular-material/angular-material-mocks.js') ?>		
		<?php print Html::script('../resources/assets/js/angular/bower_components/angular-animate/angular-animate.min.js') ?>		
		<?php print Html::script('../resources/assets/js/angular/bower_components/angular-aria/angular-aria.min.js') ?>		

		<!-- materialize -->
		<?php print Html::style('../resources/assets/css/own.css') ?>				
	</head>
	<body>		
		<md-toolbar>
			<div class="md-toolbar-tools">				
				<h2>
				  <span>Tic Tac Toe</span><span id="emails"> - ( {{email}} )</span>
				</h2>
				<span flex></span>
				<md-button  aria-label="Loby" ng-href = '#/'>
				  Loby
				</md-button>								
				<md-button  aria-label="Game" ng-href = '#/game'>
				  Game
				</md-button>				
			</div>
		</md-toolbar>
		<div ng-view></div>
	</body>
</html>



