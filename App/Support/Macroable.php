<?php
/**
 * Created by PhpStorm.
 * User: Code95
 * Date: 4/18/2019
 * Time: 2:29 PM
 */

namespace App\Support;


use ReflectionClass;
use ReflectionMethod;

trait Macroable
{
    public static $macros = [];

    public static function macro($name, $macro)
    {
        static::$macros[$name] = $macro;
    }

    public static function hasMacro($name)
    {
        return isset(static::$macros[$name]);
    }

    public static function mixin($mixin)
    {
        $methods = (new ReflectionClass($mixin))->getMethods(
            ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED
        );
        foreach ($methods as $method) {
            $method->setAccessible(true);
            static::macro($method->getName(), $method->invoke($mixin));
        }
    }

    public function __call($name, $arguments)
    {
        if (!self::hasMacro($name)) {

            throw new \BadMethodCallException("Method ${name} Doesn't exist ");
        }
        $macro = static::$macros[$name];

        if ($macro instanceof \Closure) {
            return call_user_func_array($macro->bindTo($this, static::class), $arguments);
        }
        return call_user_func_array($macro, $arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        if (!self::hasMacro($name)) {

            throw new \BadMethodCallException("Method ${name} Doesn't exist ");
        }
        $macro = static::$macros[$name];
        if ($macro instanceof \Closure) {
            return call_user_func_array(\Closure::bind($macro, null, static::class), $arguments);
        }
        return call_user_func_array($macro, $arguments);
    }
}