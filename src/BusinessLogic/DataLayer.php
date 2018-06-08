<?php

namespace BusinessLogic;

interface DataLayer {
  public function getUser($id);
  public function isUsernameUsed($userName);
  public function createUser($username, $password, $firstname, $lastname);
  public function getUserForUserNameAndPassword($userName, $password);

  public function getCategories();
  public function getManufacturer();

  public function getProductsForId($id);
  public function getProductsForCategory($categoryId);
  public function getProductsForSearchCriteria($name);
  public function createProduct($userId, $category, $name, $manufacturer);
  public function updateProduct($productId, $category, $name, $manufacturer);

  public function getReviewForUserId($userId);
  public function getReviewForProductId($productId);
  public function createReview($userId, $productId, $rating, $comment);
  public function updateReview($reviewId, $rating, $comment);
  public function deleteReview($reviewId);
}
