<?php

namespace Domain;

class Product extends Entity
{
  private $categoryId;
  private $name;
  private $userId;
  private $manufacturerId;

  public function getCategoryId() {
    return $this->categoryId;
  }

  public function getName() {
    return $this->name;
  }

  public function getUserId() {
    return $this->userId;
  }

  public function getManufacturerId() {
      return $this->manufacturerId;
  }

  function __construct($id, $categoryId, $name, $userId, $manufacturerId) {
    parent::__construct($id);
    $this->categoryId = $categoryId;
    $this->name = $name;
    $this->userId = $userId;
    $this->manufacturerId = $manufacturerId;
  }
}
