
<table class="table">
  <tr>
    <th>Name</th>
    <th>Hersteller</th>
    <th>Ersteller</th>
    <th>Anzahl</th>
    <th>Rating</th>
    <th></th>
  </tr>
<?php foreach ($model['products'] as $product):  ?>
  <tr>
    <td><?php self::htmlOut($product->getName()); ?> </td>
    <td><?php self::htmlOut($product->getManufacturerId()); ?> </td>
    <td><?php self::htmlOut($product->getUserId()); ?> </td>
    <td>5</td>
    <td>4.0</td>
  </tr>
<?php endforeach; ?>
</table>
