
<?php self::renderView('partial/Header', $model); ?>
<div class="container main-container">
  <h1>Search</h1>

  <p>
    <?php self::beginActionForm('Search', 'Product', null, 'get', 'form-inline'); ?>
      <div class="form-group">
        <label for="name">Name</label>
        <input class="form-control" id="name" name="name" value="<?php self::htmlOut($model['name']); ?>" />
      </div>
      <button class="btn btn-info">Search</button>
    <?php self::endActionForm(); ?>

  </p>
  <?php if ($model['products'] !== null): ?>
    <?php if (sizeof($model['products']) > 0) {
      self::renderView('/partial/Products', array(
        'products' => $model['products'],
        'context' => $model['context']
      ));
    } else { ?>
      <p>No matching products found. </p>
    <?php } ?>
  <?php endif; ?>
</div>

<?php self::renderView('partial/Footer', $model); ?>
