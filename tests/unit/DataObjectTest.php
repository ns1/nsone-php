<?php

namespace NSONE\Tests;

use NSONE\DataObject;


/**
 * @coversDataObjectClass \NSONE\DataObject
 *
 * @author wkw@acmetech.com
 *
 */
class DataObjectTest extends \PHPUnit_Framework_TestCase {

	public function testConstructor() {

		$obj = new DataObject();
		$this->assertTrue ( ( bool ) $obj);

	}

	public function testDataAccessors() {

		$obj = new DataObject();
		$obj['foo'] = 'bar';

		$this->assertArrayHasKey('foo', $obj, "DataObject should contain key 'foo'");

		$this->assertEquals('bar', $obj['foo'], "DataObject['foo'] should equal 'bar'");

		unset($obj['foo']);
		$this->assertEmpty($obj['foo'], "DataObject should NOT contain key='foo' after unset()");

	}

	public function testGetData() {

		$expected = array('foo' => 'bar');
		$defaultValue = 'foobar';
		$obj = new DataObject();
		$obj['foo'] = 'bar';

		$this->assertEquals($expected, $obj->getData(),
				"getData() returns array with one key, 'foo'=>'bar'");

		$this->assertEquals('bar', $obj->getData('foo'),
				"getData() returns value 'foo' for key = 'bar'");

		$this->assertEquals($defaultValue, $obj->getData('nonexistent', $defaultValue),
				"getData() returns value default value 'foobar' for unset key");

	}

	public function testDump() {

		$obj = new DataObject();
		$obj['foo'] = 'bar';

		$dump = $obj->dump(true);
		$this->assertContains('[foo]', $dump);

		# test that returned value is same as value output (we know it wiill be...)
		$this->expectOutputString($dump, $obj->dump(false));

	}

}
