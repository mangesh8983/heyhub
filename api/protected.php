<?php
ini_set('display_errors', '1');
include_once 'ConnectDb.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require "../vendor/autoload.php";
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$secret_key = "HEYHUB2021";
$jwt = new \Firebase\JWT\JWT;

$jwt::$leeway = 60;
$databaseService = new ConnectDb();
$conn = $databaseService->getConnection();
$data = json_decode(file_get_contents("php://input"));
$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$arr = explode(" ", $authHeader);
$jwt = $arr[1];
if($jwt){
    try {
        $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
      //  print_r($decoded->data->id);exit;
        echo json_encode(array(
            "message" => "Access granted:",
            "error" => "0",
            "id" => $decoded->data->id,
            "username" => $decoded->data->username
        ));
    }catch (Exception $e){
    http_response_code(401);
    echo json_encode(array(
        "message" => "Access denied.",
        "error" => $e->getMessage()
    ));
}
}