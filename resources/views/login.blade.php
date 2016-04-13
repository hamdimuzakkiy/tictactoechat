<head>
	<?php print Html::script('../resources/assets/js/angular/bower_components/jquery/dist/jquery.min.js') ?>
	<?php print Html::style('../resources/assets/css/materialize/css/materialize.css') ?>
	<?php print Html::script('../resources/assets/css/materialize/js/materialize.js') ?>
</head>

<body>
	<div class="row">
    {!! Form::open(array('url' => 'login')) !!}
      <div class="row">        
        <div class="input-field col s6">
          <input type="text" name="email">
          <label for="last_name">Email</label>
        </div>           
        <div class="input-field col s6">
          <input type="password" name="password">
          <label for="password">Password</label>
        </div>
      </div>
      <button style="float:right; margin-right:1%;" class="waves-effect waves-light btn">button</button>      
    {!! Form::close() !!}
  </div>
</body>

<!-- {!! Form::open(array('url' => 'login')) !!}
	{!! Form::text('email') !!}
	{!! Form::password('password') !!}
	{!! Form::submit('Clicked Me !!') !!}
{!! Form::close() !!} -->