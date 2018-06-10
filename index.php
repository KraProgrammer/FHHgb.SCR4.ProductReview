<?php

/* autoload php */
spl_autoload_register(function (string $class) {
  $file = __DIR__ . '/src/' . str_replace('\\', '/', $class) . '.php';

  if (file_exists($file)) {
    require_once($file);
  }
});

\Framework\Injector::register(\BusinessLogic\DBConfig::class, true, \BusinessLogic\DBConfigLocalhost::class);
\Framework\Injector::register(\BusinessLogic\DataLayer::class, false, \BusinessLogic\DBDataLayer::class);
\Framework\Injector::register(\BusinessLogic\Session::class, true);

\Framework\MVC::handleRequest();
