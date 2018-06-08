
<?php foreach ($model['reviews'] as $review):  ?>
<div class="card border-info mb-3">
  <div class="card-header">
    <div class="d-flex justify-content-between">

      <?php self::htmlOut($review->getUser()); ?> um <?php self::htmlOut($review->getDate()); ?>
      
      <?php if (isset($model['user']) && $model['user'] !== null && $model['user']->getUserName() === $review->getUser()): ?>
        <?php self::beginActionLink('Create', 'Review', array('rid' => $review->getId())); ?>
          <i class="far fa-edit"></i>
        <?php self::endActionLink(); ?>
      <?php endif; ?>
      

    </div>
  </div>
  <div class="card-body">

    <?php for($i = 0; $i < $review->getRating(); $i++) {
        echo ('<span class="fa fa-star checked"></span>');
    } 
    for($i = $review->getRating(); $i < 5; $i++) {
        echo ('<span class="fa fa-star"></span>');
    } ?>

    
    <h4 class="card-title"><?php self::htmlOut($review->getProduct()); ?> </h4>

    <p class="card-text"><?php self::htmlOut($review->getComment()); ?></p>
  </div>
</div>
<?php endforeach; ?>



