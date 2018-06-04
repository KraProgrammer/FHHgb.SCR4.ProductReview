<?php

namespace Domain;

class Product extends Entity
{
  private $category;
  private $name;
  private $user;
  private $manufacturer;
  private $countReview;
  private $avgReview;

  public function getCategory() {
    return $this->category;
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

  function __construct($id, $category, $name, $user, $manufacturer, $avgReview, $countReview) {
    parent::__construct($id);
    $this->category = $category;
    $this->name = $name;
    $this->user = $user;
    $this->manufacturer = $manufacturer;
    $this->countReview = $countReview;
    $this->avgReview = $avgReview;
  }
}
