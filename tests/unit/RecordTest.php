<?php

namespace NSONE\Tests;

use NSONE\Client;
use NSONE\Zone;
use NSONE\Record;
use NSONE\Rest\TransportException;

#include dirname(__FILE__) . '/../Helpers.php';


/**
 * @coversDataObjectClass \NSONE\Record
 *
 * @author wkw@acmetech.com
 *
 */
class RecordTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var NSONE\Client
	 */
	protected static $Client = null;

	/**
	 * @var String test $ZoneName
	 */
	protected static $ZoneName = 'ns1test.com';

	/**
	 * @var NSONE\Zone $TestZone
	 */
	protected static $TestZone = null;

	/**
	 * @var string $RecordName of record in test zone
	 */
	protected static $RecordName = 'newrecord';

	/**
	 * @var string record type in test zone
	 */
	protected static $RecordType = 'A';

	/**
	 * Create a known domain configuration for other tests.
	 *
	 * {@inheritDoc}
	 */
	public static function setUpBeforeClass() {

		self::$Client = $nsone = new Client ();

		$zones = $nsone->zones ();
		$zoneList= $zones->list_();

		$options = array ();

		try {

			$zone = $nsone->loadZone(self::$ZoneName);
			$rc = $zone->delete();

		}catch(TransportException $ex) {
			// ignored, non-existent zone
		}

		self::$TestZone = $nsone->createZone( self::$ZoneName, $options );

	}

	/**
	 * {@inheritDoc}
	 */
	public static function tearDownAfterClass() {

		$nsone = self::$Client;
		$zones = $nsone->zones ();
		$zones->delete ( self::$ZoneName );

	}

	public function testConstructor() {

		$nsone = self::$Client;
		$config = $nsone->config;
		$parentZone = self::$TestZone;
		$recordName = self::$RecordName;
		$recType = 'A';

		$parentZone->add_A($recordName, '1.2.3.4');

		$record = new Record($config, $parentZone, $recordName, $recType);
		//print_r($record); die();
		$record ->reload();

		$this->assertInstanceOf('NSONE\Record', $record,
				'Record::_constructor() should return instance of NSONE\Record.');

		return $record;

	}

	/**
	 *
	 */
	public function testCreate() {

		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);

	}

 /**
   *
	 */
	public function testLoad() {

		$this->markTestSkipped(
			'skipping this test'
		);

	}

	/**
	 * @arg NSONE\Record $record
	 *
	 * @covers NSONE\Record::reload
	 * @depends testConstructor
	 */
	public function testReload($record=NULL) {

		$rec = $record->reload();

		$this->assertInstanceOf('NSONE\Record', $rec,
				'Record::testReload() should return instance of NSONE\Record.');

		return $rec; # for @depends

	}


	/**
	 *
	 */
	public function testDelete() {

		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);

	}

	/**
	 * @arg NSONE\Record $record
	 * @depends testReload
	 * @covers NSONE\Record::update
	 */
	public function testUpdate($record) {

		$options = array();
		$rec = $record->update($options);

		$this->assertInstanceOf('NSONE\Record', $rec,
				'Record::testUpdate() should return instance of NSONE\Record.');

	}


}
