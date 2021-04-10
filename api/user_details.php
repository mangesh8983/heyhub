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


$email = '';
$password = '';

$databaseService = new ConnectDb();
$conn = $databaseService->getConnection();
$data = json_decode(file_get_contents("php://input"));
$username = $data->username;
$table_name = 'users';
$query = "select user_id, username from users where username = :user";

$stmt = $conn->prepare( $query );
$stmt->execute(array(':user' => $username));
$num = $stmt->rowCount();


if($stmt->rowCount()>0)
{
    $result = $stmt->fetch();
    $userId = $result['user_id'];
    $userName = $result['username'];
}
else
{
    $stmt = $conn->prepare("insert into users(username) values(?)");
    $stmt->bindParam(1, $username);
    $stmt->execute();
    $userId = $conn->lastInsertId();
}


$secret_key = "HEYHUB2021";
$issuer_claim = "LOCALHOST"; // this can be the servername

$issuedat_claim = time(); // issued at
$notbefore_claim = $issuedat_claim + 10; //not before in seconds
$expire_claim = $issuedat_claim + 60; // expire time in seconds
$token = array(
    "iss" => $issuer_claim,
    "nbf" => $notbefore_claim,
    "exp" => $expire_claim,
    "data" => array(
        "id" => $userId,
        "username" => $username,
));
http_response_code(200);
$jwt = JWT::encode($token, $secret_key);
echo json_encode(
array(
    "message" => "Successful login.",
    "jwt" => $jwt,
    "username" => $username,
    "expireAt" => $expire_claim
));

