<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../objects/message-list.php';

$message_list = new MessageList();
$array = $message_list->getData();//получение данных
$result = json_encode($array);
echo $result;
?>