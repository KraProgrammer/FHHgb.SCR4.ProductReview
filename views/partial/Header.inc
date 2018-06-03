<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SCR 4 Book Shop</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/productreview.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-md navbar-dark bg-primary fixed-top">
		<div class="container">
			<?php self::actionLink('Product Review', 'Index', 'Home', array(), 'navbar-brand text-info') ?>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
	  
		  	<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
					<li class="nav-item"><?php self::actionLink('Home', 'Index', 'Home') ?></li>
					<li class="nav-item"><?php self::actionLink('List', 'Index', 'Books') ?></li>
					<li class="nav-item"><?php self::actionLink('Search', 'Search', 'Books') ?></li>
					<li class="nav-item"><?php self::actionLink('Checkout', 'Create', 'Order') ?></li>
				</ul>
				<form class="form-inline my-2 my-md-0">
					<?php self::renderView('partial/User', $model['user']); ?>
				</form>
		  	</div>	
    </nav>
	</div>
    <?php if(isset($model['errors'])) {
      self::renderView('partial/Errors', $model['errors']);
    }