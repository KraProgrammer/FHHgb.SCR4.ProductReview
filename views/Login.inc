
<?php self::renderView('partial/Header', $model); ?>
<div class="container main-container">
  <h1>Login</h1>

  <?php self::beginActionForm('LogIn', 'User', null, 'post'); ?>
    <div class="form-group">
      <label for="userName"> Username</label>
      <input class="form-control" id="userName" name="un" value='<?php self::htmlOut($model['userName']); ?>'>
    </div>
    <div class="form-group">
      <label for="password"> Password</label>
      <input type="password" class="form-control" id="password" name="pwd">
    </div>
    <button class="btn btn-info">Log in</button>
    <?php self::actionLink('User erstellen', 'SignUp', 'User', array(), 'btn btn-outline-success'); ?>
  <?php self::endActionForm(); ?>


</div>

<?php self::renderView('partial/Footer', $model); ?>
