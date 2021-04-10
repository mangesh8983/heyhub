<?php

ini_set('display_errors', '1');
include_once 'ConnectDb.php';
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$databaseService = new ConnectDb();
$con = $databaseService->getConnection();
$data = json_decode(file_get_contents("php://input"));
$username = $data->username;
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
}


echo json_encode(
array(
    "message" => "Successful",
    "chatMessage"=>$chatMessage
));

