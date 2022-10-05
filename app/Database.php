<?php
//declare(strict_types=1);

/**
 * Class Database
 */
class Database
{
    private $pdo;
    private $hostdb;
    private $namedb;
    private $userdb;
    private $passworddb;


    public function __construct()
    {

        require __DIR__ . '/config.php';
        $this->hostdb = $config['connec']['host'];
        $this->namedb = $config['connec']['dbname'];
        $this->userdb = $config['connec']['user'];
        $this->passworddb = $config['connec']['password'];

        try {
            if (!empty($this->hostdb)) {


                if ($_SERVER['SERVER_ADDR'] === ":::1" || $_SERVER['SERVER_ADDR'] === "127:0:0:1" || $_SERVER['SERVER_NAME'] === "localhost") {
                    $this->hostdb = $config['connection']['host'];
                    $this->namedb = $config['connection']['dbname'];
                    $this->userdb = $config['connection']['user'];
                    $this->passworddb = $config['connection']['password'];
                    $this->pdo = new PDO(
                        "mysql:host={$this->hostdb};dbname={$this->namedb};charset=UTF8",
                        $this->userdb,
                        $this->passworddb,
                        [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                        ]
                    );
                } else {
                    $this->hostdb = $config['connec']['host'];
                    $this->namedb = $config['connec']['dbname'];
                    $this->userdb = $config['connec']['user'];
                    $this->passworddb = $config['connec']['password'];
                    $this->pdo = new PDO(
                        "mysql:host={$this->hostdb};dbname={$this->namedb};charset=UTF8",
                        $this->userdb,
                        $this->passworddb,
                        []
                    );
                }
            }
        } catch (PDOException $e) {
            echo '<h1 class="alert danger">Connexion refusée : ' . $e->getMessage() . '</h1>';
            exit;
        }
    }

    public function __destruct()
    {

        $this->pdo = null;
    }

    public function query(string $sql, array $params = []): array
    {
        $res = explode(" ", $sql);

        if ($res[0] === "INTO") {
            return $this->into($sql, $params);
        }
        if ($res[0] === "UPDATE") {
            return $this->update($sql, $params);
        }
        if ($res[0] === "DELETE") {
            return $this->del($sql, $params);
        }
        if ($res[0] === "SELECT") {
            return $this->fetchAll($sql, $params);
        }
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        $query = $this->pdo->prepare($sql);
        $query->execute($params);

        $data = $query->fetchAll();
        //$query->debugDumpParams();
        if (!empty($data)) {
            return $data;
        } else {
            return array();
        }
    }


    public function fetch(string $sql, array $params = []): array
    {
        $query = $this->pdo->prepare($sql);
        $query->execute($params);
        $user = $query->fetch();
        //var_dump($params);
        //$query->debugDumpParams();
        if (!empty($user)) {
            return $user;
        } else {
            return array();
        }
    }

    public function into(string $sql, array $params = []): string
    {
        $id = 0;
        $query = $this->pdo->prepare($sql);
        $query->execute($params);

        $id = $this->pdo->lastInsertId();
        return $id;
    }

    public function delt(string $sql, array $params = []): int
    {

        return $this->del($sql, $params);
    }

    public function del(string $sql, array $params = []): int
    {
        $query = $this->pdo->prepare($sql);
        $query->execute($params);

        return $query->rowCount();
    }

    public function update(string $sql, array $params = []): int
    {
        $query = $this->pdo->prepare($sql);
        $query->execute($params);

        return $query->rowCount();
    }

    private function getPdo(): PDO
    {
        return $this->pdo;
    }

    private function setPdo(PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    function close()
    {

        $this->pdo = null;
        $query = null;
    }
}
