<?php

namespace NSONE\Tests;

use NSONE\Client;
use NSONE\Zone;
use NSONE\Rest\TransportException;


#use NSONE\Tests\Helpers;



/**
 * @coversDataObjectClass \NSONE\Zone
 *
 * @author wkw@acmetech.com
 *
 * @todo Possibly make use of some phpunit @depends annotations to run the flow of CRUD
 */
class ZoneTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var NSONE\Client
	 */
	protected static $Client = null;

	/**
	 * @var String test zone name
	 */
	protected static $ZoneName = 'ns1test.com';

	/**
	 * @var string name of record in test zone
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

		$newZone = $nsone->createZone( self::$ZoneName, $options );

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

		$this->markTestSkipped(
			'skipping this test'
		);

	}

	public function test__Call() {

		$nsone = self::$Client;
		$zone = $nsone->loadZone(self::$ZoneName);
		$magicMethodName = 'add_A';
		$answer = '1.2.3.4';
		$options = array('nx_ttl' => 100);
		$args = array(
				self::$RecordName,
				$answer,
				$options
		);

		$record = $zone->__call($magicMethodName, $args);
		$this->assertInstanceOf('NSONE\Record', $record,
				'Zone::__call() should return instance of NSONE\Record.');

		# remove record
		$record->delete();

		# test incorrect magic method name `ADD_A` instead of `add_A`
		$record = $zone->__call('ADD_A', $args);
		$this->assertNull($record, 'Zone::__call() with unsupported magic method name should return NULL.');

	}

	public function testLoad() {

		$this->markTestSkipped(
			'skipping this test (reload() covers it)'
		);

	}

	public function testReload() {

		$nsone = self::$Client;
		$zone = $nsone->loadZone(self::$ZoneName);

		$zoneH = $zone->reload();
		$this->assertInstanceOf('NSONE\Zone', $zoneH,
				'Zone::reload() should return instance of NSONE\Zone.');

	}

	public function testDelete() {

		$this->markTestSkipped(
			'skipping this test'
		);

	}

	public function testUpdate() {

		$nsone = self::$Client;
		$zone = $nsone->loadZone(self::$ZoneName);
		$ip = '1.2.3.4';
		$newIp = '8.8.8.8';

		# create new single record
		$record = $zone->add_A(self::$RecordName, $ip);
		$this->assertInstanceOf('NSONE\Record', $record,
				'Zone::add_A() should return instance of NSONE\Record.');

		$answers = $record['answers'];
		$this->assertCount(1, $answers);
		$this->assertEquals($ip, $answers[0]['answer'][0], 'Zone record answer should match ' . $ip);

		$options = array(
				'refresh' => 2000,
				'nx_ttl'	=> 100
		);
		$zone = $zone->update($options);
		$this->assertInstanceOf('NSONE\Zone', $zone,
				'Zone::update() should return instance of NSONE\Zone.');

		# remove record
		$record->delete();

	}

	public function testCreate() {

		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);

	}

	public function testLoadRecord() {

		$this->markTestSkipped(
			'skipping this test; '
		);

	}

	public function testQps() {

		$nsone = self::$Client;
		$zone = $nsone->loadZone(self::$ZoneName);

		$qps = $zone->qps();
		$this->assertArrayHasKey('qps', $qps);

	}

	public function testUsage() {

		$nsone = self::$Client;
		$zone = $nsone->loadZone(self::$ZoneName);

		$usage = $zone->usage();
		$this->assertGreaterThan(0, count($usage), 'Zone::usage() should return at least one reocrd.');

	}

	/**
	 *
	 */
	public function testSkipped() {

		$this->markTestSkipped(
			'skipping this test'
		);
	}

	/**
	 *
	 */
	public function testIncomplete() {

		$this->markTestIncomplete(
        'This test has not been implemented yet.'
     );

	}
}
