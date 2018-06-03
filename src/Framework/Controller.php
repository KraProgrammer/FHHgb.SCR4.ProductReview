<?php

namespace Framework;

class Controller {
  public final function hasParam($id) {
    return isset($_REQUEST[$id]);
  }

  public final function getParam($id, $defaultValue = null) {
    return isset($_REQUEST[$id]) ? $_REQUEST[$id] : $defaultValue;
  }

  // MVC::ViewRenderer.renderView();
  public final function renderView(string $view, array $model = array()) {
    ViewRenderer::renderView($view, $model);
  }

  public final function buildActionLink(string $action, string $controller, array $params) {
    return MVC::buildActionLink($action, $controller, $params);
  }

  public final function redirectToUrl(string $url) {
    header("Location: $url");
  }

  public final function redirect(string $action, string $controller, array $params = array()) {
    $this->redirectToUrl($this->buildActionLink($action, $controller, $params));
  }
}
