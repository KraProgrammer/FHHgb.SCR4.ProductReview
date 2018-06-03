<div class="main-container container">
  <div class="alert alert-danger" role='alert'>
    <strong> Please correct the following and try again</strong>
    <ul>
      <?php foreach ($model as $errMsg): ?>
        <li><?php self::htmlOut($errMsg); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
