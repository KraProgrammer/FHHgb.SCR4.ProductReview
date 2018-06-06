<?php

namespace BusinessLogic;

interface DataLayer {
  public function getCategories();
  public function getManufacturer();
  public function getProductsForCategory($categoryId);
  public function getProductsForSearchCriteria($name);
  public function getProductsForId($id);
  public function getUser($id);
  public function isUsernameUsed($userName);
  public function createUser($username, $password, $firstname, $lastname);
  public function getUserForUserNameAndPassword($userName, $password);
  public function createReview($userId, $productId, $rating, $comment);
  public function getReviewForUserId($userId);
  public function getReviewForProductId($productId);
  public function createProduct($userId, $category, $name, $manufacturer);
  public function updateProduct($productId, $category, $name, $manufacturer);
}
