
<?php self::renderView('partial/Header', $model); ?>
<div class="container main-container">
  <h1>Neuen User erstellen</h1>

  <?php self::beginActionForm('SignUp', 'User', null, 'post'); ?>
    <div class="form-group">
      <label for="userName"> Username</label>
      <input class="form-control" id="userName" name="un" value='<?php self::htmlOut($model['userName']); ?>'>
    </div>
    <div class="form-group">
      <label for="password"> Password</label>
      <input type="password" class="form-control" id="password" name="pwd" >
    </div>
    <div class="form-group">
      <label for="fn"> Vorname</label>
      <input type="text" class="form-control" id="fn" name="fn" value='<?php self::htmlOut($model['firstname']); ?>'>
    </div>
    <div class="form-group">
      <label for="ln"> Nachname</label>
      <input type="text" class="form-control" id="ln" name="ln"value='<?php self::htmlOut($model['lastname']); ?>'>
    </div>        
    <button class="btn btn-info">User erstellen</button>
  <?php self::endActionForm(); ?>


</div>

<?php self::renderView('partial/Footer', $model); ?>
