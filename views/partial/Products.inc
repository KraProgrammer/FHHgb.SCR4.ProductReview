
<table class="table">
  <tr>
    <th>Name</th>
    <th class="d-none d-md-table-cell">Hersteller</th>
    <th class="d-none d-md-table-cell">Ersteller</th>
    <th>Anzahl</th>
    <th>Rating</th>
    <th></th>
  </tr>
<?php foreach ($model['products'] as $product):  ?>
  <tr >

    <td><?php self::htmlOut($product->getName()); ?> </td>
    <td class="d-none d-md-table-cell"><?php self::htmlOut($product->getManufacturer()); ?> </td>
    <td class="d-none d-md-table-cell"><?php self::htmlOut($product->getUser()); ?> </td>
    <td><?php self::htmlOut($product->getReviewCount()); ?></td>
    <td><?php self::htmlOut(number_format((float)$product->getReviewAvg(), 1, '.', '')); ?> </td>
    <td>
      <?php self::beginActionLink('Details', 'Product', array('pid' => $product->getId())); ?>
        <i class="fas fa-chevron-right"></i>
      <?php self::endActionLink(); ?>
    </td>

  </tr>
<?php endforeach; ?>
</table>