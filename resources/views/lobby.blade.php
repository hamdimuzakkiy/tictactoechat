
<textarea name="chat" id = "chat"></textarea>
<button>hello</button>


<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
{!! Html::script('../resources/assets/js/pusher.min.js') !!}
{!! Html::script('../resources/assets/js/jquery-1.12.2.min.js') !!}
<script type="text/javascript">	
    var CSRF_TOKEN = '{{ csrf_token() }}'    
	$("button").click(function(){
		var chat = $('textarea#chat').val();    	
    	$.ajax({type:"POST",url: "http://localhost/tictactoechat/public/", data: {_token: CSRF_TOKEN, message:chat}, success: function(result){
        	
    	}});
	});
	var pusher = new Pusher('abb96bf6b157928ed5cc');
	var channel = pusher.subscribe('lobby');	
	channel.bind('message', function(data) {
	    console.log(data);
	});	
</script>