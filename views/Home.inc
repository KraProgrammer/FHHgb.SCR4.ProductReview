
<?php self::renderView('partial/Header', $model); ?>

  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    </ol>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img class="first-slide" src="img/eye.jpg" alt="Eye">
        <div class="container">
          <div class="carousel-caption text-right">
            <h3>Ein genauer Blick lohnt sich...</h3>
          </div>
        </div>
      </div>
    </div>
  </div>


<div class="container main-container">
  <h1>Willkommen!</h1>
  <p>Mit Product Review können Sie endlich uneingeschränkt Produkte bewerten.</p>
  <p>
    <?php if (!isset($model['user'])): ?>
      <?php self::actionLink('Einloggen', 'LogIn', 'User', array(), 'btn btn-outline-success'); ?>
    <?php endif; ?>
  </p>
</div>

<?php self::renderView('partial/Footer', $model); ?>
