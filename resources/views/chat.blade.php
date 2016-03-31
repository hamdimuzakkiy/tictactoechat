
<button>hello</button>

{!! Html::script('../resources/assets/js/pusher.min.js') !!}
{!! Html::script('../resources/assets/js/jquery-1.12.2.min.js') !!}
<script type="text/javascript">
	
	$("button").click(function(){
    	$.ajax({url: "http://localhost/tictactoechat/public/send", success: function(result){
        	
    	}});
	});
	var pusher = new Pusher('193434');
	var channel = pusher.subscribe('chat');
	channel.bind('message', function(data) {
	    console.log(data);
	});
</script>
{!! Html::script('../resources/assets/js/main.js') !!}