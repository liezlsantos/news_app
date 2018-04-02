<?php

class HttpResponseHelper {

    public static function sendResponse($statusCode = 200, $data = null, $message = null) {
        if ($statusCode != 200) {
            http_response_code($statusCode);
            $response = ['message' => $message ?: 'Unknown error occurred'];
        } else if ($data !== null) {
            $response = $data;
        } else {
            $response = ['message' => $message ?: 'OK'];
        }
        echo json_encode($response);
        exit();
    }
}
