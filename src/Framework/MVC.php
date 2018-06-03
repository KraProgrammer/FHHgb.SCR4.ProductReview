<?php
namespace Framework;


  final class MVC {
    private function __construct() {}

    const PARAM_CONTROLLER = 'c';
    const PARAM_ACTION = 'a';

    const DEFAULT_CONTROLLER = 'Home';
    const DEFAULT_ACTION = 'Index';

    const CONTROLLER_NAMESPACE = '\\Controllers';

    private static $viewPath = 'views';

    public static function getViewPath() {
      return self::$viewPath;
    }

    public static function buildActionLink(string $action, string $controller, array $params = array()) {
      $res = '?' . self::PARAM_ACTION . '=' . rawurlencode($action) . '&' . self::PARAM_CONTROLLER . '=' . rawurlencode($controller);
      // (is_array($params)) { foreach } -> not necessary because typehint
      foreach ($params as $name => $value) {
        $res .= '&' .rawurlencode($name) . '=' . rawurlencode($value);
      }
      return $res;
    }

    public static function handleRequest() {
      //determine controller class
      $controllerName = isset($_REQUEST[self::PARAM_CONTROLLER])
          ? $_REQUEST[self::PARAM_CONTROLLER]
          : self::DEFAULT_CONTROLLER;

      $controller = self::CONTROLLER_NAMESPACE . "\\$controllerName";

      // determine HTTP method and action
      $method = $_SERVER['REQUEST_METHOD'];
      $action = isset($_REQUEST[self::PARAM_ACTION])
          ? $_REQUEST[self::PARAM_ACTION]
          : self::DEFAULT_ACTION;

      // instanciate controller and call according action method
      $m = $method . '_' . $action;
      Injector::resolve($controller)->$m();
    }
  }
