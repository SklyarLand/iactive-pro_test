<?php
include_once 'message.php';
include_once '../config/database.php';
/**
 * 
 */
class MessageList
{
    private $messages;
    private $conn;
    private $error;
    private $count;

    function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function setMessages(array $messages_array)
    {
        foreach ($messages_array as $i => $value) {
            $this->messages[] = new Message($value,$this->conn);
        }
        $this->count = count($messages_array);
    }

    public function checkMessages()
    {
        foreach ($this->messages as $i => $message) {
            if($message->isDuplicate()) {
                unset($this->messages[$i]);
            }
        }
    }

    public function sendMessages() 
    {
        if ($this->messages) {
            foreach ($this->messages as $i => $message) {
                $message->send();
            }
        }
    }

    public function getResponse()
    {
        $response;

        if ($this->error) {//код ошибки
            $response = '{ "status": "error", "body": "not all added", "code": 500 }';
        } else {
            $response = '{ "status": "ok", "body": "added %d", "code": 500 }';
            $response = sprintf($response, $this->count);
        }
        return json_encode($response);
    }

    public function getData()
    {
        $query = "SELECT m.id, author_id, datetime, content, datetime_first_message, datetime_last_message, messages_count FROM messages AS m INNER JOIN authors AS a ON m.author_id = a.id  WHERE m.is_deleted = 0 AND a.is_banned = 0";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}
?>