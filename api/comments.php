<?php
require_once('../include.php');

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'POST':
        addComment(json_decode(file_get_contents('php://input')));
        break;
    case 'DELETE':
        if (isset($_SERVER['PATH_INFO'])) {
            $path = explode('/', trim($_SERVER['PATH_INFO'], '/'));
            deleteComment(array_shift($path));
        }
        HttpResponseHelper::sendResponse(404);
        break;
    default:
        HttpResponseHelper::sendResponse(404);
        break;
}

function addComment($comment) {
    if (!isset($comment->news_id)) {
        HttpResponseHelper::sendResponse(400, null, 'Comment news_id is required.');
    }
    if (!isset($comment->body)) {
        HttpResponseHelper::sendResponse(400, null, 'Comment body is required.');
    }

    try {
        NewsManager::getInstance()->getNewsById($comment->news_id);
    } catch (NotFoundException $e) {
        HttpResponseHelper::sendResponse(404, null, 'News '.$comment->news_id.' does not exist.');
    }

    try {
        CommentManager::getInstance()->addCommentForNews(
            $comment->body,
            $comment->news_id
        );
        HttpResponseHelper::sendResponse(200, $comment);
    } catch (CommentManagerException $e) {
        HttpResponseHelper::sendResponse(500, null, $e->getMessage());
    } catch (Exception $e) {
        HttpResponseHelper::sendResponse(500);
    }
}

function deleteComment($commentId) {
    if (!is_numeric($commentId) || $commentId < 1) {
        HttpResponseHelper::sendResponse(400, null, 'Invalid comment id.');
    }

    try {
        CommentManager::getInstance()->deleteComment($commentId);
        HttpResponseHelper::sendResponse(200, null, 'Comment successfully deleted.');
    } catch (NotFoundException $e) {
        HttpResponseHelper::sendResponse(404, null, $e->getMessage());
    } catch (CommentManagerException $e) {
        HttpResponseHelper::sendResponse(500, null, $e->getMessage());
    } catch (Exception $e) {
        HttpResponseHelper::sendResponse(500, null, $e->getMessage());
    }
}
