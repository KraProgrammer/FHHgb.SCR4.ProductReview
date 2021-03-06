<?php

namespace Framework;

final class Injector {
  private function __construct() {  }

  private static $instances; // string => object
  private static $singletonFlags; // string => boolean
  private static $classNames; // string => string


  public static function register($serviceName, $isSingleton = false, $className = null) {
    self::$singletonFlags[$serviceName] = $isSingleton;
    self::$classNames[$serviceName] = $className;
  }

  public static function resolve($serviceName) {
    // check if there is already an instance
    if (isset(self::$instances[$serviceName])) {
      return self::$instances[$serviceName];
    }


    // determine class name (if serviceName is interface)
    $className = isset(self::$classNames[$serviceName]) && self::$classNames[$serviceName] !== null
    ? self::$classNames[$serviceName]
    : $serviceName;

    // determine constructor parameters
    $actualParams = array();

    $rClass = new \ReflectionClass($className);
    if ($rClass == null) {
      die("Cannot find class: " . $className . "! ");
    }

    $rCtor = $rClass->getConstructor();
    if ($rCtor != null) {
      foreach ($rCtor->getParameters() as $rParam) {
        if ($rParam->isOptional()) {
          // use default value if parameter is optional
          $actualParams[] = $rParam->getDefaultValue();
        } elseif ($rParam->getClass() != null) {
          // try to resolve parameter by its type
          $actualParams[] = self::resolve($rParam->getClass()->name);
        } else {
          die("Cannot resolve cotr parameter '{$rParam->getName()}' for class '$className'");
        }
      }
    }

    // create instance
    $instance = new $className(...$actualParams);
    
    //store instance in case of singleton
    if (isset(self::$singletonFlags[$serviceName]) && self::$singletonFlags[$serviceName] == true) {
      self::$instances[$serviceName] = $instance;
    }

    // return instance
    return $instance;
  }


}
