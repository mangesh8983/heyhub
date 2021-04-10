<html>
    <head>
        <title>Chat Application</title>
        <style>
            .main{
                border:1px solid #aaa;
                height:550px;
                width:600px;
                display: block;
                margin: 0 auto;
                padding:20px;
            }
            .main .chat-input{
                height:40px;
                width: 500px;
                margin: 10px 0 0;
                display: block;
               float: left;
                border: 1px solid #aabbff;
                padding:15px;
            }
          
            .main .input-button{
                height:40px;
                width: 100px;
                margin: 10px auto 0 0 ;
                display: block;
                border: 1px solid #aabbff;
                padding:15px;
                cursor:pointer;
                float:left;
            }
            .chat-box-html{
                border: 1px solid #aaa;
                width: 300px;
                height: auto;
                padding: 20px;
                border-top-right-radius: 25px;
                border-bottom-right-radius: 25px;
                margin: 10px 0 10px 0;
            }
            .chat-box-html-right{
                border: 1px solid #aaa;
                width: 300px;
                height: auto;
                padding: 20px;
                border-top-left-radius: 25px;
                border-bottom-left-radius: 25px;
                
                margin: 10px 0 10px auto;
            }
            .chat-box{
                height:450px;
                overflow: scroll;
            }
            .message-input-box{
                display: block;
                width: 100%;
                height:100px;
                
            }
        </style>
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script>
        
        function showMessage(messageHTML) {
		$('#chat-box').append(messageHTML);
	}
        $(document).ready(function(){
		var websocket = new WebSocket("ws://localhost:8090/heyhubchat/php-socket.php"); 
		websocket.onopen = function(event) { 
		//	showMessage("<div class='chat-connection-ack'>Connection is established!</div>");	
                    
		}
		websocket.onmessage = function(event) {
			var Data = JSON.parse(event.data);
                        var clss = Data.message_type;
                       
                           if($('#chat-user').val() == Data.username)
                           {
                               clss = 'chat-box-html-right';
                           };
			showMessage("<div class='"+clss+"'>"+Data.message+"</div>");
			$('#chat-message').val('');
		};
		
		websocket.onerror = function(event){
			showMessage("<div class='error'>Problem due to some Error</div>");
		};
		websocket.onclose = function(event){
			showMessage("<div class='chat-connection-ack'>Connection Closed</div>");
		}; 
		
		$('#frmChat').on("submit",function(event){
			event.preventDefault();
                        if($('#chat-message').val() == '')
                        {
                            return false;
                        }
			var messageJSON = {
				chat_user: $('#chat-user').val(),
				chat_message: $('#chat-message').val(),   
			};
                        $('body').animate({
                            scrollTop: eval($('#' + $('#chat-box').attr('target')).offset().top - 70)
                        }, 1000);
			websocket.send(JSON.stringify(messageJSON));
		});
             $.ajax({
                type: "POST",
                url:  "HandleChat.php", 
                data: {username: $('#chat-user').val()},
                dataType: "html",  
                cache:false,
                success: 
                     function(data){
                      $('#chat-box').append(data);  //as a debugging message.
                     }
                 });// you have missed this bracket   
    });
      </script>
    </head>
    <body>
        <div class="main">
            <h2 id="username"></h2>
            <form id="frmChat" class="frmChat">
                <div id="chat-box" target="username" class="chat-box">
                        
            </div>
            <input type="hidden" name="chat-user-id" id="chat-userid" value="<?php echo $userId;?>" class="chat-input" />
            <input type="hidden" name="chat-user-name" id="chat-user" value="<?php echo $userName;?>" class="chat-input" />
            <div class="message-input-box">
            <input type="text" name="chat-message" id="chat-message" placeholder="type a message" class="input-field chat-input"/>
            <input type="submit" id="btnSend" value="Send" class="input-button">
            </div>
            </form>
        </div>
    </body>
</html>