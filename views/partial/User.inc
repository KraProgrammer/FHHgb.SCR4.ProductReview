<ul class='nav navbar-nav'>
  <?php if (!isset($model['user'])): ?>
    <li><?php self::actionLink('Log in', 'LogIn', 'User', array('ctx' => $model['context'])); ?></li>
  <?php else: ?>
    <li class="btn d-none d-lg-block"><?php self::htmlOut($model['user']->getUserName()); ?></li>
    <li>
      <?php self::beginActionForm('LogOut', 'User', array('ctx' => $model['context']), 'POST', 'form-inline my-2 my-md-0'); ?>
      <button class="btn btn-info">Log Out</button>
      <?php self::endActionForm(); ?>
    </li>
  <?php endif; ?>
</ul>
