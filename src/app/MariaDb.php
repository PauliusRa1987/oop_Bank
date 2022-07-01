<?php

namespace Bankas;
use App\DB\DataBase;
use PDO;

class MariaDb implements DataBase

{
    public $data;
    public  $pdo;

    public  function __construct()
    {
        $host = '127.0.0.1';
        $db   = 'bankas_oop';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->pdo = new PDO($dsn, $user, $pass, $options);
    }

    public function showAll(): array
    {
        $sql = "
    SELECT client, sasNr, name, surname, personId, suma
    FROM accounts
    ORDER BY surname
";
        $stmt = $this->pdo->query($sql);
        $this->data = $stmt->fetchAll();

        return $this->data;
    }

    public function create(array $data): void
    {
        $this->data[] = $data;
        $sql = "
        INSERT INTO accounts
        (sasNr, name, surname, personId, suma)
        VALUES (?, ?, ?, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$data['sasNr'], $data['name'], $data['surname'], $data['personId'], $data['suma'],]);
    }

    public function show(int $userId): array
    {
        $sql = "
        SELECT client, sasNr, name, surname, personId, suma
        FROM accounts
        Where client = ".$userId;
       
        $stmt = $this->pdo->query($sql);
        $this->data = $stmt->fetch();

        
        return $this->data;
    }
    public function delete(int $id) : void {
        
        $sql = "
            DELETE FROM accounts
            WHERE client = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
    }

    function update(int $id, array $data) : void {
        
        $sql = "
        UPDATE accounts
            SET suma = ?
            WHERE client = ?
    ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$data['suma'], $id]);
    }
    
    //// Log and Sign methodes
    public function createUser(array $data): void
    {
        $this->data[] = $data;
        $sql = "
        INSERT INTO signin
        (username, password)
        VALUES (?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$data['username'], $data['password']]);
    }

    public function showUs(): array
    {
        $sql = "
        SELECT *
        FROM signin
        ";
       
        $stmt = $this->pdo->query($sql);
        $this->data = $stmt->fetchAll();

        return $this->data;
    }
    function updateUser(int $id, array $data) : void {
        
        $sql = "
        UPDATE signin
            SET session = ?
            WHERE id = ?
    ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$data['session'], $data['id']]);
    }



}
