<?php

class CommentManager {
    private static $instance = null;

    public static function getInstance() {
        if (null === self::$instance) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    /**
     * Get all comments linked to a news
     */
    public function listCommentsForNews($newsId) {
        try {
            $db = DB::getInstance();
            $rows = $db->select("SELECT `id`, `body`, `news_id`, `created_at`
                FROM `comment`
                WHERE `news_id` = ${newsId}
                    AND `is_deleted` = 0");
        } catch (DBException $e) {
            throw new CommentManagerException('Could not retrieve comments', $e->getStatusCode(), $e);
        }

        $comments = [];
        foreach($rows as $row) {
            $n = new Comment();
            $comments[] = $n->setId($row['id'])
              ->setBody($row['body'])
              ->setCreatedAt($row['created_at'])
              ->setNewsId($row['news_id']);
        }
        return $comments;
    }

    /**
     * Add new comment to a news
     */
    public function addCommentForNews($body, $newsId) {
        try {
            $db = DB::getInstance();
            $sql = "INSERT INTO `comment` (`body`, `created_at`, `news_id`)
                VALUES('${body}', NOW(), '${newsId}')";
            $db->exec($sql);
            return $db->lastInsertId($sql);
        } catch (DBException $e) {
            throw new CommentManagerException('Failed to add comment.', $e->getStatusCode(), $e);
        }
    }

    /**
     * Delete a comment
     */
    public function deleteComment($id) {
        try {
            $db = DB::getInstance();
            $sql = "UPDATE `comment`
                SET `is_deleted` = 1
                WHERE `id` = ${id}";
            if ($db->exec($sql) === 0) {
                throw new NotFoundException("Comment with id ${id} not found.");
            }
        } catch (DBException $e) {
            throw new CommentManagerException('Failed to delete comment.', $e->getStatusCode(), $e);
        }
    }

    /**
     * Delete all comments for news
     */
    public function deleteCommentForNews($newsId) {
        try {
            $db = DB::getInstance();
            $sql = "UPDATE `comment`
                SET `is_deleted` = 1
                WHERE `news_id` = ${newsId}";
            return $db->exec($sql);
        } catch (DBException $e) {
            throw new CommentManagerException('Failed to delete comments.', $e->getStatusCode(), $e);
        }
    }
}
