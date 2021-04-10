<?php 
require_once 'Commons.php';
?>
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
                padding: 20px;
            }
            .main .input-field{
                height:40px;
                width: 400px;
                margin: 40% auto 0;
                display: block;
               
                border: 1px solid #aabbff;
                padding:15px;
            }
            .main .input-button{
                height:40px;
                width: 400px;
                margin: 20px auto;
                display: block;
                border: 1px solid #aabbff;
                padding:15px;
                cursor:pointer;
            }
        </style>
    </head>
    <body>
        <div class="main">
            <form action="UserChat.php" method="post">
            <input type="text" placeholder="Enter your name" name="username" class="input-field"/>
            <input type="submit" value="Let's chat" class="input-button"/>
            </form>
        </div>
    </body>
</html>