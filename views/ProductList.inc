<?php self::renderView('partial/Header', $model); ?>
<div class="container main-container">
  <h1>Produktliste</h1>
  <p>
    <ul class="nav nav-pills nav-fill" role="tablist">
      <?php foreach ($model['categories'] as $cat) { ?>
      <li class="nav-item text-info" role="presentation">
        <?php if ($cat->getId() == $model['selectedCategoryId']) { $class = "nav-link active"; } else { $class = "nav-link"; } ?>
        <?php self::actionLink($cat->getName(), 'Index', 'Product', array('cid' => $cat->getId()), $class); ?>
      </li>
      <?php } ?>
    </ul>
  </p>

  <?php if ($model['products'] !== null): ?>
    <?php if (sizeof($model['products']) > 0) {
      self::renderView('/partial/products', array(
        'products' => $model['products'],
        'context' => $model['context']
      ));
    } else { ?>
      <p> No products in this category.</p>
    <?php } ?>
  <?php else: ?>
    <p> Please select a cagategory.</p>
  <?php endif; ?>
  <?php self::beginActionForm('Create', 'Product', null, 'GET', 'form-inline my-2 my-md-0'); ?>
  <button class="btn btn-info">Neues Produkt anlegen</button>
  <?php self::endActionForm(); ?>
</div>

<?php self::renderView('partial/Footer', $model); ?>
