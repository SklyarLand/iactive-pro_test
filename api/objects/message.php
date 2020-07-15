<?php
include_once 'author.php';
/**
 * 
 */
class Message
{
    private $author;
    private $datetime;
    private $content;
    private $conn;

    function __construct(Array $message, PDO $connection)
    {
        $this->conn = $connection;
        $this->content = $message['message'];
        $this->datetime = date('Y-m-d H:i:s');
        $this->author = new Author($message['phone'], $this->datetime, $connection);
    }

    public function send()
    {
        //отправка
        $insert = "INSERT messages(author_id, datetime, content)
         VALUES (:author_id, :datetime, :content)";
        $stmt = $this->conn->prepare($insert);

        $stmt->bindParam(':author_id', $this->author->getId());
        $stmt->bindParam(':datetime', $this->datetime);
        $stmt->bindParam(':content', $this->content);

        if (!$stmt->execute()) {
            return false;
        }

        $this->author->update();
        return true;
    }

    public function isDuplicate()
    {
        //проверка дублирования
        $two_min_ago = strtotime($this->datetime);
        $two_min_ago = strtotime("-2 minutes", $two_min_ago);
        $two_min_ago = date('Y-m-d H:i:s', $two_min_ago);
        
        $query = "SELECT id from messages WHERE datetime >= '" . $two_min_ago . "' and content <> '" . $this->content . "'";
        $stmt = $this->conn->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['id'];
    }
}
?>