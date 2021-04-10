<?php

ini_set('display_errors', '1');
require_once 'Commons.php';
require_once 'LoadPage.php';
class UserChat {
    //put your code here
    function userDetails($arr)
    {
        $LoadChat = new LoadPage();
        if(isset($arr) && !empty($arr))
        {
        $handle = curl_init();
        $url = "http://localhost/heyhubchat/api/user_details.php";
        $username = $arr['username'];
        $data = ['username'=>$username];
        $postdata = json_encode($data);
        // Set the url
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS,
            $postdata);
        // Set the result output to be a string.
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($handle);
        $data = json_decode($output);
        $token = ($data->jwt);
        curl_close($handle);
        $LoadChat->testToken($token);
        }
        else
        {
             $LoadChat->loadLogin();
        }

    }
}

$userDetails = new UserChat();
$userDetails->userDetails($_POST);
