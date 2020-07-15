<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../objects/message-list.php';

# JSON в массив messages
$json_str = '{ "messages":[ { "message":"Как ваши дела?", "phone":"7999999999" }, {"message":"Привет!","phone":"7999999998"}]}';
//$json_str = file_get_contents('php://input');

if ($json_str) {
    $data = json_decode($json_str, true);
    $messages = $data['messages'];

    $message_list = new MessageList();
    $message_list->setMessages($messages);//передача сообщений
    $message_list->checkMessages();//проверка
    $message_list->sendMessages();//отправка

    echo $message_list->getResponse();//получение ответа
}
?>