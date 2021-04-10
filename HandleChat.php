<?php
require_once 'Commons.php';
require_once 'ConnectDb.php';

class HandleChat{
    function send($message) {
		global $clientSocketArray;
		$messageLength = strlen($message);
		foreach($clientSocketArray as $clientSocket)
		{
			@socket_write($clientSocket,$message,$messageLength);
		}
		return true;
	}

	function unseal($socketData) {
		$length = ord($socketData[1]) & 127;
		if($length == 126) {
			$masks = substr($socketData, 4, 4);
			$data = substr($socketData, 8);
		}
		elseif($length == 127) {
			$masks = substr($socketData, 10, 4);
			$data = substr($socketData, 14);
		}
		else {
			$masks = substr($socketData, 2, 4);
			$data = substr($socketData, 6);
		}
		$socketData = "";
		for ($i = 0; $i < strlen($data); ++$i) {
			$socketData .= $data[$i] ^ $masks[$i%4];
		}
		return $socketData;
	}

	function seal($socketData) {
		$b1 = 0x80 | (0x1 & 0x0f);
		$length = strlen($socketData);
		
		if($length <= 125)
			$header = pack('CC', $b1, $length);
		elseif($length > 125 && $length < 65536)
			$header = pack('CCn', $b1, 126, $length);
		elseif($length >= 65536)
			$header = pack('CCNN', $b1, 127, $length);
		return $header.$socketData;
	}

	function doHandshake($received_header,$client_socket_resource, $host_name, $port) {
		$headers = array();
		$lines = preg_split("/\r\n/", $received_header);
		foreach($lines as $line)
		{
			$line = chop($line);
			if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
			{
				$headers[$matches[1]] = $matches[2];
			}
		}

		$secKey = $headers['Sec-WebSocket-Key'];
		$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
		$buffer  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
		"Upgrade: websocket\r\n" .
		"Connection: Upgrade\r\n" .
		"WebSocket-Origin: $host_name\r\n" .
		"WebSocket-Location: ws://$host_name:$port/demo/shout.php\r\n".
		"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
		socket_write($client_socket_resource,$buffer,strlen($buffer));
	}
	function create_chat($chat_user, $chat_box_message)
        {
              
            $handle = curl_init();
            $url = "http://localhost/heyhubchat/api/create_chat.php";
            $username = $chat_user;
            $data = ['username'=>$username, 'message'=>$chat_box_message];
            $postdata = json_encode($data);
            // Set the url
            curl_setopt($handle, CURLOPT_URL, $url);
            curl_setopt($handle, CURLOPT_POST, 1);
            curl_setopt($handle, CURLOPT_POSTFIELDS,$postdata);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($handle);
            
            curl_close($handle); 
        }
        function get_chat($arr)
        {
                
                $username = $arr['username'];
               /* $db = new ConnectDb();
                $con = $db->getConnection();
                $stmt1 = $con->prepare("select u.user_id, u.username, uc.sent_on, uc.message from users u inner join user_chat uc on u.user_id = uc.user_id order by uc.sent_on desc limit 0, 20");
                $stmt1->execute();
                $result = $stmt1->fetchAll();
               
                $chatMessage = '';
                foreach($result as $res)
                {
                
                $sentOn = $res['sent_on'];
                $sentTime = date('h:i A', strtotime($sentOn));
                $class = ($username == $res['username'])?'chat-box-html-right':'chat-box-html';
                $message = $res['username'].' : ' . " <div class='chat-box-message'>" . $res['message'] . "</div><div>".$sentTime."</div>";
		$chatMessage.= "<div class='".$class."'>".$message."</div>";
		 
                }*/
                $handle = curl_init();
                $url = "http://localhost/heyhubchat/api/get_chat.php";
                
                $data = ['username'=>$username];
                $postdata = json_encode($data);
                // Set the url
                curl_setopt($handle, CURLOPT_URL, $url);
                curl_setopt($handle, CURLOPT_POST, 1);
                curl_setopt($handle, CURLOPT_POSTFIELDS,$postdata);
                curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                $output = curl_exec($handle);
                $data = json_decode($output);
                $chatMessage = $data->chatMessage;
                curl_close($handle); 
                echo $chatMessage;
               
        }
	function createChatBoxMessage($chat_user='', $chat_box_message='') {
               
                
                $username = $chat_user;
                $chat_user = empty($chat_user)?'GUEST':$chat_user.' : ';
                
                $message = $chat_user . " <div class='chat-box-message'>" . $chat_box_message . "</div><div>".date('h:i A')."</div>";
		$messageArray = empty($message)?array('message'=>'', 'message_type'=>''):array('message'=>$message,'message_type'=>'chat-box-html', 'username'=>$username);
		$chatMessage = $this->seal(json_encode($messageArray));
		return $chatMessage;
	}
        
        
        
}
$ajx = new HandleChat();
$ajx->get_chat($_POST);