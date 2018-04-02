<?php
require_once('../include.php');

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        $newsId = getNewsId();
        if ($newsId !== null) {
            getNewsById($newsId);
        }
        getAllNews();
        break;
    case 'POST':
        addNews(json_decode(file_get_contents('php://input')));
        break;
    case 'DELETE':
        $newsId = getNewsId();
        if ($newsId !== null) {
            deleteNews($newsId);
        }
        HttpResponseHelper::sendResponse(404);
        break;
    default:
        HttpResponseHelper::sendResponse(404);
        break;
}

function getNewsId() {
    $newsId = null;
    if (isset($_SERVER['PATH_INFO'])) {
        $path = explode('/', trim($_SERVER['PATH_INFO'], '/'));
        $newsId = array_shift($path);
    }
    return $newsId;
}

function getNewsById($newsId) {
    if (!is_numeric($newsId) || $newsId < 1) {
        HttpResponseHelper::sendResponse(400, null, 'Invalid news id.');
    }

    try {
        $news = NewsManager::getInstance()->getNewsById($newsId);
        $commentsList = [];
        foreach (CommentManager::getInstance()->listCommentsForNews($newsId) as $comment) {
            $commentsList[] = [
                'id' => $comment->getId(),
                'body' => $comment->getBody(),
                'created_at' => $comment->getCreatedAt(),
                'news_id' => $comment->getNewsId(),
            ];
        }
        $result = [
            'id' => $newsId,
            'title' => $news->getTitle(),
            'body' => $news->getBody(),
            'created_at' => $news->getCreatedAt(),
            'comments' => $commentsList,
        ];
        HttpResponseHelper::sendResponse(200, $result);
    } catch (NotFoundException $e) {
        HttpResponseHelper::sendResponse(404, null, $e->getMessage());
    } catch (NewsManagerException $e) {
        HttpResponseHelper::sendResponse(500, null, $e->getMessage());
    } catch (CommentManagerException $e) {
        HttpResponseHelper::sendResponse(500, null, $e->getMessage());
    } catch (Exception $e) {
        HttpResponseHelper::sendResponse(500);
    }
}

function getAllNews() {
    $newsList = [];
    try {
        foreach (NewsManager::getInstance()->listNews() as $news) {
            $newsId = $news->getId();
            $commentsList = [];
            foreach (CommentManager::getInstance()->listCommentsForNews($newsId) as $comment) {
                $commentsList[] = [
                    'id' => $comment->getId(),
                    'body' => $comment->getBody(),
                    'created_at' => $comment->getCreatedAt(),
                    'news_id' => $comment->getNewsId(),
                ];
            }
            $newsList[] = [
                'id' => $newsId,
                'title' => $news->getTitle(),
                'body' => $news->getPreviewBody(),
                'created_at' => $news->getCreatedAt(),
                'comments' => $commentsList,
            ];
        }
        HttpResponseHelper::sendResponse(200, $newsList);
    } catch (NewsManagerException $e) {
        HttpResponseHelper::sendResponse(500, null, $e->getMessage());
    } catch (CommentManagerException $e) {
        HttpResponseHelper::sendResponse(500, null, $e->getMessage());
    } catch (Exception $e) {
        HttpResponseHelper::sendResponse(500);
    }
}

function addNews($news) {
    if (!isset($news->title)) {
        HttpResponseHelper::sendResponse(400, null, 'News title is required.');
    }
    if (!isset($news->body)) {
        HttpResponseHelper::sendResponse(400, null, 'News body is required.');
    }

    try {
        NewsManager::getInstance()->addNews(
            $news->title,
            $news->body
        );
        HttpResponseHelper::sendResponse(200, $news);
    } catch (CommentManagerException $e) {
        HttpResponseHelper::sendResponse(500, null, $e->getMessage());
    } catch (Exception $e) {
        HttpResponseHelper::sendResponse(500);
    }
}

function deleteNews($newsId) {
    if (!is_numeric($newsId) || $newsId < 1) {
        HttpResponseHelper::sendResponse(400, null, 'Invalid news id.');
    }

    try {
        NewsManager::getInstance()->deleteNews($newsId);
        HttpResponseHelper::sendResponse(200, null, 'News successfully deleted.');
    } catch (NotFoundException $e) {
        HttpResponseHelper::sendResponse(404, null, $e->getMessage());
    } catch (NewsManagerException $e) {
        HttpResponseHelper::sendResponse(500, null, $e->getMessage());
    } catch (Exception $e) {
        HttpResponseHelper::sendResponse(500);
    }
}
