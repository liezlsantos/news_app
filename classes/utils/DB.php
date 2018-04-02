<?php

class DB {
    private $pdo;

    private static $instance = null;

    private function __construct() {
        $config = Config::get('database');
        $dsn = 'mysql:dbname='.$config['database_name'].';host='.$config['host'];
        $user = $config['user'];
        $password = $config['password'];

        try {
            $this->pdo = new \PDO($dsn, $user, $password);
        } catch (Exception $e) {
            throw new DBException('Could not connect to the database.', $e->getStatusCode(), $e);
        }
    }

    public static function getInstance() {
        if (null === self::$instance) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public function select($sql, $binds = null) {
        try {
            $sth = $this->pdo->prepare($sql);
            $sth->execute($binds);
            return $sth->fetchAll();
        } catch (Exception $e) {
            throw new DBException('Failed to execute select statement.', $e->getStatusCode(), $e);
        }
    }

    public function exec($sql, $binds = null) {
        try {
            $sth =  $this->pdo->prepare($sql);
            return $sth->execute($binds);
        } catch (Exception $e) {
            throw new Exception('Failed to execute sql statement.', $e->getStatusCode(), $e);
        }
    }

    public function lastInsertId() {
        try {
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve last insert id.', $e->getStatusCode(), $e);
        }
    }

    public function beginTransaction() {
        try {
            $this->pdo->beginTransaction();
        } catch (Exception $e) {
            throw new DBException('Failed to start transaction.', $e->getStatusCode(), $e);
        }
    }

    public function commit() {
        try {
            $this->pdo->commit();
        } catch (Exception $e) {
            throw new DBException('Failed to commit changes.', $e->getStatusCode(), $e);
        }
    }

    public function rollback() {
        try {
            $this->pdo->rollback();
        } catch (Exception $e) {
            throw new DBException('Failed to rollback changes.', $e->getStatusCode(), $e);
        }
    }
}
