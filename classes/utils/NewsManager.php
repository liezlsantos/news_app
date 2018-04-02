<?php

class NewsManager {

    private static $instance = null;

    public static function getInstance() {
        if (null === self::$instance) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    /**
     * Get all news
     */
    public function listNews() {
        try {
            $db = DB::getInstance();
            $rows = $db->select('SELECT `id`, `title`, `body`, `created_at`
                FROM `news`
                WHERE `is_deleted` = 0
                ORDER BY created_at DESC');
        } catch (DBException $e) {
            throw new NewsManagerException('Failed to retrieve list of news.');
        }

        $news = [];
        foreach($rows as $row) {
            $n = new News();
            $news[] = $n->setId($row['id'])
              ->setTitle($row['title'])
              ->setBody($row['body'])
              ->setCreatedAt($row['created_at']);
        }

        return $news;
    }

    /**
     * Get specific news
     */
    public function getNewsById($id) {
        try {
            $db = DB::getInstance();
            $rows = $db->select("SELECT `id`, `title`, `body`, `created_at`
                FROM `news`
                WHERE `id` = ${id}
                    AND `is_deleted` = 0");
        } catch (DBException $e) {
            throw new NewsManagerException("Failed to retrieve news.", $e->getStatusCode(), $e);
        }

        if (count($rows) > 0) {
            $row = $rows[0];
            $news = new News();
            $news->setId($row['id'])
                ->setTitle($row['title'])
                ->setBody($row['body'])
                ->setCreatedAt($row['created_at']);
            return $news;
        }
        throw new NotFoundException("News with id ${id} not found.");
    }

    /**
     * Add a record in news table
     */
    public function addNews($title, $body) {
        try {
            $db = DB::getInstance();
            $sql = "INSERT INTO `news` (`title`, `body`, `created_at`)
                VALUES('${title}','${body}', NOW())";
            $db->exec($sql);
            return $db->lastInsertId($sql);
        } catch (DBException $e) {
            throw new NewsManagerException('Failed to add news.', $e->getStatusCode(), $e);
        }
    }

    /**
     * Deletes a news, and also linked comments
     */
    public function deleteNews($id) {
        try {
            $db = DB::getInstance();
            $db->beginTransaction();
            $comments = CommentManager::getInstance()->deleteCommentForNews($id);
            $sql = "UPDATE `news`
                SET `is_deleted` = 1
                WHERE `id` = ${id}";
            if ($db->exec($sql) === 0) {
                throw new NotFoundException("News with id ${id} not found.");
            }
            $db->commit();
        } catch (NotFoundException $e) {
            $db->rollback();
            throw $e;
        } catch (Exception $e) {
            $db->rollback();
            throw new NewsManagerException("Failed to delete news with id ${id}.");
        }
    }
}
