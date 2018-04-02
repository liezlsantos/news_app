<?php

class News {
    const MAX_WORDS_FOR_PREVIEW = 100;

    protected $id, $title, $body, $createdAt;

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setBody($body) {
        $this->body = $body;
        return $this;
    }

    public function getBody() {
        return $this->body;
    }

    public function getPreviewBody() {
        $words = explode(' ', $this->getBody());
        if (count($words) > self::MAX_WORDS_FOR_PREVIEW) {
            return implode(' ',
                array_slice($words, 0, self::MAX_WORDS_FOR_PREVIEW)
            ).'...';
        }
        return $this->getBody();
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }
}
