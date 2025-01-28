<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("数据库连接失败: " . $e->getMessage());
        }
    }

    public static function init() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public static function query($sql) {
        return self::$instance->pdo->query($sql);
    }

    public static function prepare($sql) {
        return self::$instance->pdo->prepare($sql);
    }

    public static function lastInsertId() {
        return self::$instance->pdo->lastInsertId();
    }
}
?>