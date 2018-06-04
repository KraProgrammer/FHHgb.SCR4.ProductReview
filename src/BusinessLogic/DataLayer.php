<?php

namespace BusinessLogic;

interface DataLayer {
  public function getCategories();
  public function getManufacturer();
  public function getProductsForCategory($categoryId);
  public function getProductsForSearchCriteria($name);
  public function getUser($id);
  public function getUserForUserNameAndPassword($userName, $password);
  public function getReviewForUserId($userId);
  public function createProduct($userId, $category, $name, $manufacturer);
}
