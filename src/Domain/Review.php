<?php

namespace Domain;

class Review extends Entity
{
  private $productId;
  private $date;
  private $userId;
  private $rating;
  private $comment;

  public function getProductId() {
    return $this->productId;
  }

  public function getDate() {
    return $this->date;
  }

  public function getUserId() {
    return $this->userId;
  }

  public function getRating() {
    return $this->rating;
  }

  public function getComment() {
      return $this->comment;
  }

  function __construct($id, $productId, $date, $userId, $rating, $comment) {
    parent::__construct($id);
    $this->productId = $productId;
    $this->date = $date;
    $this->userId = $userId;
    $this->rating = $rating;
    $this->comment = $comment;
  }
}
