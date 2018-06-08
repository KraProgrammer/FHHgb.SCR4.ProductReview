<?php

namespace Domain;

class Review extends Entity
{
  private $product;
  private $date;
  private $user;
  private $rating;
  private $comment;
  private $productId;

  public function getProduct() {
    return $this->product;
  }

  public function getProductId() {
    return $this->productId;
  }

  public function getDate() {
    return $this->date;
  }

  public function getUser() {
    return $this->user;
  }

  public function getRating() {
    return $this->rating;
  }

  public function getComment() {
      return $this->comment;
  }

  function __construct($id, $product, $productId,  $date, $user, $rating, $comment) {
    parent::__construct($id);
    $this->product = $product;
    $this->productId = $productId;
    $this->date = $date;
    $this->user = $user;
    $this->rating = $rating;
    $this->comment = $comment;
  }
}
