<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoadChat
 *
 * @author apple
 */
class LoadPage {
    //put your code here
    function loadChat($userId, $userName)
    {
        include_once 'page2.php';
    }
    function loadLogin()
    {
        include_once 'page1.php';
    }
    function testToken($token)
    {
        $ch = curl_init();
 
        $url = "http://localhost/heyhubchat/api/protected.php";
     
        $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer '.$token
        );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        if(curl_errno($ch)){
        // throw the an Exception.
        throw new Exception(curl_error($ch));
        }
        curl_close($ch);
         $response;
        
        $data = json_decode($response);
        $userId = $data->id;
        $userName = $data->username;
        $this->loadChat($userId, $userName);
       // $token = ($data->jwt);
    }
}


