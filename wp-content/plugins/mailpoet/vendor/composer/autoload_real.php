<?php
if (!defined('ABSPATH')) exit;
// autoload_real.php @generated by Composer
class ComposerAutoloaderInitaf531d9638aae475648182d0c638008c
{
 private static $loader;
 public static function loadClassLoader($class)
 {
 if ('Composer\Autoload\ClassLoader' === $class) {
 require __DIR__ . '/ClassLoader.php';
 }
 }
 public static function getLoader()
 {
 if (null !== self::$loader) {
 return self::$loader;
 }
 require __DIR__ . '/platform_check.php';
 spl_autoload_register(array('ComposerAutoloaderInitaf531d9638aae475648182d0c638008c', 'loadClassLoader'), true, true);
 self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
 spl_autoload_unregister(array('ComposerAutoloaderInitaf531d9638aae475648182d0c638008c', 'loadClassLoader'));
 require __DIR__ . '/autoload_static.php';
 call_user_func(\Composer\Autoload\ComposerStaticInitaf531d9638aae475648182d0c638008c::getInitializer($loader));
 $loader->register(true);
 $filesToLoad = \Composer\Autoload\ComposerStaticInitaf531d9638aae475648182d0c638008c::$files;
 $requireFile = \Closure::bind(static function ($fileIdentifier, $file) {
 if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
 $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;
 require $file;
 }
 }, null, null);
 foreach ($filesToLoad as $fileIdentifier => $file) {
 $requireFile($fileIdentifier, $file);
 }
 return $loader;
 }
}