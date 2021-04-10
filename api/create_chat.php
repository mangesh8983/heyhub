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
$message = $data->message;
$table_name = 'users';
$query = "select user_id, username from users where username = :user";

$stmt1 = $con->prepare( $query );
$stmt1->execute(array(':user' => $username));

$result = $stmt1->fetch();
$chat_uid = $result['user_id'];

if(!empty($chat_uid) && !empty($message))
{
$stmt = $con->prepare("insert into user_chat(user_id, message) values(?,?)");
$stmt->bindParam(1, $chat_uid);
$stmt->bindParam(2, $message);
$stmt->execute();
}


echo json_encode(
array(
    "message" => "Successful",
));

