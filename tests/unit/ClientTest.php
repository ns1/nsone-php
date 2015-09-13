<?php

namespace NSONE\Tests;

use NSONE\Client;
use NSONE\Config;
use NSONE\Rest\TransportException;


/**
 * @coversDefaultClass \NSONE\Config
 *
 * @author wkw@acmetech.com
 *
 */
class ClientTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var NSONE\Client
	 */
	protected static $Client = null;

	/**
	 * @var String $ZoneName - our test parent zone
	 */
	protected static $ZoneName = 'ns1test.com';

	/**
	 * @var string $RecordName - name of record in test zone
	 */
	protected static $RecordName = 'newrecord';

	/**
	 * @var string $RecordType - record type in test zone
	 */
	protected static $RecordType = 'A';

	/**
	 * @var int $Verbosity - config transport verbosity level. default=0
	 */
	protected static $Verbosity = 1;


	/**
	 * Create a known domain configuration for other tests.
	 *
	 * {@inheritDoc}
	 */
	public static function setUpBeforeClass() {

		self::$Client = $nsone = new Client ();

		$zones = $nsone->zones ();
		$nsone->config ['verbosity'] = self::$Verbosity;
		$zoneList= $zones->list_();

		$options = array (
				'nx_ttl' => 100
		);

		try {

			$zone = $nsone->loadZone(self::$ZoneName);
			$rc = $zone->delete();

		}catch(TransportException $ex) {
			// ignored, non-existent zone
		}

		$newZone = $nsone->createZone( self::$ZoneName, $options );
		# create a single record
		$add_A = 'add_' . self::$RecordType;
		$rc = $newZone->{$add_A}('newrecord', '1.2.3.4');

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
		$this->assertTrue ( ( bool ) $nsone );

	}

	/**
	 * Creates new client with passed-in options (config).
	 * Will be skipped if ~/.nsone not present.
	 */
	public function testConstructorCustomOptions() {

		// use sample test data
		$filename = dirname ( __FILE__ ) . '/../data/nsone.json';
		$options = array (
				'config' => new Config ( $filename )
		);

		$nsone = new Client ( $options );
		$this->assertInstanceOf('NSONE\Client', $nsone);

		#config we gave, should be config on client
		$this->assertEquals ( $options ['config'], $nsone->config );

	}

	public function testStats() {

		$nsone = self::$Client;
		$stats = $nsone->stats ();

		$this->assertInstanceOf('NSONE\Rest\Stats', $stats,
				'Client::stats() should return instance of NSONE\Rest\Stats');

		$this->assertArrayHasKey ( 'qps', $stats->qps () );

	}

	public function testZones() {

		$nsone = self::$Client;
		$zones = $nsone->zones();
		$this->assertInstanceOf('NSONE\Rest\Zones', $zones,
				'Client::zones() should return instance of NSONE\Rest\Zones');

		$availableZones = $zones->list_();

		$this->assertTrue ( ( bool ) $availableZones );
		$this->assertGreaterThan ( 0, count($availableZones) );

		$testZone = $zones->retrieve(self::$ZoneName);

		$this->assertEquals(self::$ZoneName, $testZone['zone']);

	}

	public function testRecords() {

		$nsone = self::$Client;
		$records = $nsone->records();

		$this->assertInstanceOf('NSONE\Rest\Records', $records,
				'Client::records() should return instance of NSONE\Rest\Zones');

	}

	public function testDatasource() {

		$nsone = self::$Client;
		$datasource = $nsone->datasource();

		$this->assertInstanceOf('NSONE\Rest\DataSource', $datasource,
				'Client::datasource() should return instance of NSONE\Rest\DataSource');

	}

	public function testDatafeed() {

		$nsone = self::$Client;
		$datafeed = $nsone->datafeed();

		$this->assertInstanceOf('NSONE\Rest\DataFeed', $datafeed,
				'Client::datafeed() should return instance of NSONE\Rest\DataFeed');

	}

	/**
	 * high level interface
	 */

	public function testLoadZone() {

		$nsone = self::$Client;
		$zone = $nsone->loadZone(self::$ZoneName);

		$this->assertInstanceOf('NSONE\Zone', $zone,
				'Client::loadZone() should return instance of NSONE\Zone');

		$this->assertEquals(self::$ZoneName, $zone->zone);

	}

	public function testCreateZone() {

		$newZoneName = 'newzone33.com';

		$nsone = self::$Client;
		$zone = $nsone->createZone($newZoneName);

		$this->assertInstanceOf('NSONE\Zone', $zone,
				'Client::createZone() should return instance of NSONE\Zone');

		$this->assertEquals($newZoneName, $zone->zone);

		# cleanup $newZoneName
		try {

			$zone->delete();

		}catch(Exception $ex) {}

	}

	public function testLoadRecord() {

		$nsone = self::$Client;

		# record created in setupBeforeClass()
		$zone = $nsone->loadRecord(self::$RecordName, self::$RecordType, self::$ZoneName);
		$this->assertInstanceOf('NSONE\Zone', $zone,
				'Client::loadRecord() should return instance of NSONE\Zone.');

		# test missing optional zone name
		$zone = $nsone->loadRecord(self::$RecordName . '.' . self::$ZoneName, self::$RecordType);
		$this->assertInstanceOf('NSONE\Zone', $zone,
				'Client::loadRecord() without explicit zonename should parse zone from record name and return instance of NSONE\Zone.');

		# load non existent record, excpect exception
		$this->setExpectedException('NSONE\Rest\TransportException');
		$nsone->loadRecord(self::$RecordName, 'MX', self::$ZoneName);

	}


}
