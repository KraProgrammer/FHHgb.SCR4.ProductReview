<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SCR 4 Book Shop</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
  	<link href="css/productreview.css" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
		<link rel="stylesheet" href="css/fontawesome-stars.css">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
		<div class="container">
			<?php self::actionLink('Product Review', 'Index', 'Home', array(), 'navbar-brand text-info') ?>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
	  
		  	<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
					<li class="nav-item"><?php self::actionLink('Home', 'Index', 'Home') ?></li>
					<li class="nav-item"><?php self::actionLink('Produkte', 'Index', 'Product') ?></li>
					<li class="nav-item"><?php self::actionLink('Suche', 'Search', 'Product') ?></li>
					<li class="nav-item"><?php self::actionLink('Bewertungen', 'Index', 'Review') ?></li>
				</ul>
				<div class="navbar-right">
					<?php self::renderView('partial/User', array(
						'user' => $model['user'],
        		'context' => isset($model['context']) ? $model['context'] : null )); ?>
					</div>
		  	</div>	
    </nav>
	</div>
    <?php if(isset($model['errors'])) {
      self::renderView('partial/Errors', $model['errors']);
    }
