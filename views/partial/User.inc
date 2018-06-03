<ul class='nav navbar-nav'>
  <?php if (!isset($model)): ?>
    <li><?php self::actionLink('Log in', 'LogIn', 'User'); ?></li>
  <?php else: ?>
    <li class="btn d-none d-lg-block"><?php self::htmlOut($model->getUserName()); ?></li>
    <li>
      <?php self::beginActionForm('LogOut', 'User', null, 'POST', 'form-inline my-2 my-md-0'); ?>
      <button class="btn btn-info">Log Out</button>
      <?php self::endActionForm(); ?>
    </li>
  <?php endif; ?>
</ul>
