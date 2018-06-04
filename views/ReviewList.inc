<?php self::renderView('partial/Header', $model); ?>
<div class="container main-container">
  <h1>Meine Reviews</h1>

  <?php if ($model['reviews'] !== null): ?>
    <?php if (sizeof($model['reviews']) > 0) {
      self::renderView('/partial/reviews', array(
        'reviews' => $model['reviews']
      ));
    } else { ?>
      <p> No reviews!.</p>
    <?php } ?>
  <?php else: ?>
    <p> Please login</p>
  <?php endif; ?>

</div>

<?php self::renderView('partial/Footer', $model); ?>
