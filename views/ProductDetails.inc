<?php self::renderView('partial/Header', $model); ?>
<div class="container main-container">
  <h1>Produktdetails</h1>

  <?php if ($model['product'] !== null): ?>
    <?php $product = $model['product'];?>
    <div class="card text-white bg-success mb-3">
      <div class="card-header">
        <?php self::htmlOut($product->getUser()); ?>
      </div>
      <div class="card-body">
        <h4 class="card-title">
          <?php if ($model['user'] !== null && $model['user']->getUserName() === $product->getUser()): ?>
            <div class="d-flex justify-content-between">
              <?php self::htmlOut($product->getName()); ?>
              <?php self::beginActionLink('Create', 'Product', array('pid' => $product->getId()), 'GET'); ?>
                <i class="far fa-edit"></i>
              <?php self::endActionLink(); ?>
            </div>
          <?php endif; ?>
          </h4>
        <p class="card-text">
          Hersteller: <?php self::htmlOut($product->getManufacturer()); ?> <br/>
          Anzahl der Bewertungen: <?php self::htmlOut($product->getReviewCount()); ?> <br/>
          Durchschnittliches Rating: <?php self::htmlOut(number_format((float)$product->getReviewAvg(), 1, '.', '')); ?>
        </p>
      </div>
    </div>
  <?php endif ?>


    <div class="container">
      <br />
      
    <?php self::beginActionForm('Create', 'Review', null, 'GET', 'form-inline my-2 my-md-0'); ?>
      <button class="btn btn-info">Neue Bewertung anlegen</button>
    <?php self::endActionForm(); ?>
      <hr>

      <h3> Bewertungen </h3>
      <?php if (sizeof($model['reviews']) > 0) {
        self::renderView('/partial/reviews', array(
          'reviews' => $model['reviews']
        ));
      } else { ?>
        <p> No reviews!.</p>
      <?php } ?>
    </div>
</div>

<?php self::renderView('partial/Footer', $model); ?>
