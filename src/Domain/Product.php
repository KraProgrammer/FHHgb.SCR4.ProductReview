<?php

namespace Domain;

class Product extends Entity
{
  private $categoryId;
  private $name;
  private $user;
  private $manufacturerId;
  private $countReview;
  private $avgReview;

  public function getCategoryId() {
    return $this->categoryId;
  }

  public function getName() {
    return $this->name;
  }

  public function getUser() {
    return $this->user;
  }

  public function getManufacturer() {
      return $this->manufacturer;
  }

  public function getReviewCount() {
    return $this->countReview;
  }

  public function getReviewAvg() {
      return $this->avgReview;
  }

  function __construct($id, $categoryId, $name, $user, $manufacturer, $avgReview, $countReview) {
    parent::__construct($id);
    $this->categoryId = $categoryId;
    $this->name = $name;
    $this->user = $user;
    $this->manufacturer = $manufacturer;
    $this->countReview = $countReview;
    $this->avgReview = $avgReview;
  }
}
