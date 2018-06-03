<ul class='nav navbar-nav'>
  <?php if (!isset($model)): ?>
    <li><?php self::actionLink('Log in', 'LogIn', 'User'); ?></li>
  <?php else: ?>
    <li class="navbar-text"><?php self::htmlOut($model->getUserName()); ?></li>
    <li>
      <?php self::beginActionForm('LogOut', 'User', null, 'post', 'navbar-form'); ?>
      <button class="btn btn-link">Log Out</button>
      <?php self::endActionForm(); ?>
    </li>
  <?php endif; ?>
</ul>
