<?php
/**
 * 
 */
class Author
{
    private $id;
    private $phone;
    private $datetime_first_message;
    private $datetime_last_message;
    private $messages_count;

    private $conn;

    function __construct(string $phone, string $datetime, PDO $connection)
    {
        $this->phone = $phone;
        $this->conn = $connection;
        $this->datetime_last_message = $datetime;
        $this->receiveId();

        if (!$this->id) {//если автора нет в базе
            $this->datetime_first_message = $datetime;
            $this->add();
        }
        $this->receiveId();
    }

    private function receiveId()
    {
        //получение id из базы
        $query = "SELECT id from authors WHERE phone = " . $this->phone;
        $stmt = $this->conn->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id = $result['id'];
    }

    public function getId()
    {
        return $this->id;
    }

    private function add() 
    {
         //добавление нового автора
        $insert = "INSERT authors(phone, datetime_first_message, datetime_last_message, messages_count)
         VALUES (:phone, :first, :last, 0)";
        $stmt = $this->conn->prepare($insert);

        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':first', $this->datetime_first_message);
        $stmt->bindParam(':last', $this->datetime_last_message);

        $stmt->execute();
    }

    public function update()
    {
        //количество сообщений
        $query = "SELECT COUNT(*) from messages WHERE author_id = " . $this->id;
        $stmt = $this->conn->query($query);
        $result = $stmt->fetch();
        $count = $result['COUNT(*)'];
        //обновление полей автора
        $update = "UPDATE authors SET messages_count = :messages_count, datetime_last_message = :datetime_last_message WHERE id = :id";
        $stmt = $this->conn->prepare($update);

        $stmt->bindParam(':messages_count', $count);
        $stmt->bindParam(':datetime_last_message', $this->datetime_last_message);
        $stmt->bindParam(':id', $this->id);

        $stmt->execute();
    }
}
?>