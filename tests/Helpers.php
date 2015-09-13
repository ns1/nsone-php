<?php

namespace NSONE\Tests;

class Helpers {

	/**
	 *
	 * @var Singleton The reference to *Singleton* instance of this class
	 */
	private static $instance;

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return Singleton The *Singleton* instance.
	 *
	 * USAGE: $h = Helpers::getInstance();
	 */
	public static function getInstance() {

		if (null === static::$instance) {
			static::$instance = new static ();
		}

		return static::$instance;

	}

	/**
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 */
	protected function __construct() {}
	private function __clone() {}
	private function __wakeup() {}

	/**
	 * getPrivateProperty
	 *
	 * @author Joe Sexton <joe@webtipblog.com>
	 * @param string $className
	 * @param string $propertyName
	 * @return ReflectionProperty
	 * @see http://www.webtipblog.com/unit-testing-private-methods-and-properties-with-phpunit/
	 */
	public static function getPrivateProperty($className, $propertyName) {

		$reflector = new \ReflectionClass ( $className );
		$property = $reflector->getProperty ( $propertyName );
		$property->setAccessible ( true );

		return $property;

	}


	/**
	 * getPrivateMethod
	 *
	 * @author	Joe Sexton <joe@webtipblog.com>
	 * @param 	string $className
	 * @param 	string $methodName
	 * @return	ReflectionMethod
	 * @see http://www.webtipblog.com/unit-testing-private-methods-and-properties-with-phpunit/
	 */
	public function getPrivateMethod( $className, $methodName ) {
		$reflector = new ReflectionClass( $className );
		$method = $reflector->getMethod( $methodName );
		$method->setAccessible( true );

		return $method;
	}

	/**
	 * Set protected/private attribute of object
	 *
	 * @param object &$object       Object containing attribute
	 * @param string $attributeName Attribute name to change
	 * @param string $value         Value to set attribute to
	 *
	 * @return null
	 *
	 * @see https://github.com/jtreminio/TestExtensions/blob/master/src/jtreminio/TestExtensions/TestExtensionsSilex.php
	 */
	public static function setAttribute(&$object, $attributeName, $value)
	{
		$class = is_object($object) ? get_class($object) : $object;
		$reflection = new \ReflectionProperty($class, $attributeName);
		$reflection->setAccessible(true);
		$reflection->setValue($object, $value);
	}


	/**
	 * Detrmine if ~/.nsone config file available
	 *
	 * @return bool TRUE if ~/.nsone exists
	 */
	public static function nsoneConfigAvailable() {

		$filename = '.nsone';
		$path = getenv ( 'HOME' ) . '/' . $filename;
		return file_exists ( $path );

	}

	/**
	 * Return JSON string for ~/.nsone config file
	 *
	 * @return string nsone json config
	 * @throws \Exception if json parsing fails or file not found
	 * @see Helpers::nsoneConfigAvailable
	 */
	public static function getNsoneConfig() {

		$decoded = null;
		$filename = '.nsone';
		$path = getenv ( 'HOME' ) . '/' . $filename;
		if(!file_exists ( $path ))
			throw new Exception("~/.nsone config file not found");

		$json = file_get_contents ( $path, "r" );
		if ($json) {
			$decoded = json_decode ( $json );
			if ($decoded === NULL)
				throw new \Exception ( "Failed parsing json config file: {$path}" );
		}

		return $decoded;

	}

}
