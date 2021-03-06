<?php

namespace Framework;

final class ViewRenderer {
  private function __construct() {} // private because static

  public static function renderView(string $view, $model) {
    require(MVC::getViewPath() . "/$view.inc");
  }

  // PRIVATE HELPER FUNCTION FOR VIEW RENDERRING
  private static function htmlOut($string) {
    echo(nl2br(htmlentities($string)));
  }

  private static function actionLink(string $content, string $action, string $controller, array $params = array(), string $cssClass = null) {
    ViewRenderer::beginActionLink($action, $controller, $params, $cssClass);
    self::htmlOut($content);
    ViewRenderer::endActionLink();
  }

  private static function beginActionLink(string $action, string $controller, array $params = array(), string $cssClass = null) {
    $cc = $cssClass != null ? " class=\"$cssClass\"" : "";
    $url = MVC::buildActionLink($action, $controller, $params);
    echo("<a href=\"$url\"$cc>");
  }

  private static function endActionLink() {
    echo("</a>");
  }


  private static function beginActionForm(string $action, string $controller, array $params = null, string $method = 'get', string $cssClass = null) {
    $c = MVC::PARAM_CONTROLLER;
    $a = MVC::PARAM_ACTION;

    //$cc = $cssClass !== null ? " class=\" $cssClass \"" : '';
    $cc = $cssClass !== null ? $cssClass : '';
    $form = <<<FORM
<form method="$method" action="?" class="$cc">
  <input type="hidden" name="$c" value="$controller" >
  <input type="hidden" name="$a" value="$action" >
FORM;
      echo($form);
      if (is_array($params)) {
        foreach ($params as $name => $value) {
          echo("<input type=\"hidden\" name=\"$name\" value=\"$value\">");
        }
      }
  }

  private static function endActionForm() {
    echo('</form>');
  }


}
