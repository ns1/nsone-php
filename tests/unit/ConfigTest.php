<?php

namespace NSONE\Tests;

use NSONE\Config;

/**
 * @coversDefaultClass \NSONE\Config
 * @covers ::<public>
 *
 * @author wkw@acmetech.com
 *
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Tests loading config file with provided path in Config object constructor.
	 *
	 * @dataProvider configProvider
	 *
	 * @param string $filename path to sample config file
	 * @param string $jsonConf mock json config settings
	 * @param mixed  $phpConf parsed json config
	 */
	public function testLoadFromFileInConstructorTestData($filename, $jsonConf, $phpConf) {

		if (! file_exists ( $filename )) {
			$this->markTestSkipped ( 'Missing test API config file: {$this->NSONE_config_file}.' );
		}

		$config = new Config ( $filename );
		$actualKey = $config->getAPIKey ();
		$expectedKey = $phpConf->keys->{$phpConf->default_key}->key;

		$this->assertEquals ( $expectedKey, $actualKey );

	}

	/**
	 * Tests loading config file from ~/.nsone and ::expandTilde()
	 *
	 * @param string $filename path to sample config file
	 * @param string $jsonConf mock json config settings
	 * @param mixed  $phpConf parsed json config
	 */
	public function testLoadFromFileConfigInHomeDir() {

		$filename = '.nsone';
		$path = getenv("HOME");
		$fullpath = null;
		$skip = false;

		if($path){
			$fullpath = $path . '/' . $filename;
			if(!file_exists($fullpath))
				$skip = true;
		}
		else
			$skip = true;

		if($skip) {
			$this->markTestSkipped(
				"Skipping test for loading config from {$fullpath}."
			);
			return;
		}

		$config = new Config ();
		$config->loadFromFile();

		$this->assertNotEmpty($config->getAPIKey ());

	}

	/**
	 * @dataProvider configProvider
	 *
	 * @param string $filename path to sample config file
	 * @param string $jsonConf mock json config settings
	 * @param mixed  $phpConf parsed json config
	 */
	public function testLoadFromFileWithPathTestData($filename, $jsonConf, $phpConf) {

		if (! file_exists ( $filename )) {
			$this->markTestSkipped ( 'Missing test API config file: {$this->NSONE_config_file}.' );
		}

		$config = new Config ();
		$config->loadFromFile ( $filename );
		$actualKey = $config->getAPIKey ();
		$expectedKey = $phpConf->keys->{$phpConf->default_key}->key;

		$this->assertEquals ( $expectedKey, $actualKey );

	}


	/**
	 *
	 * @dataProvider configProvider
	 *
	 * @param string $filename path to sample config file
	 * @param string $jsonConf mock json config settings
	 * @param mixed $phpConf parsed json config
	 */
	public function testGetKeyConfigDefaultKey($filename, $jsonConf, $phpConf) {

		$config = new Config ();
		$config->loadFromString ( $jsonConf );
		$actualConfig = $config->getKeyConfig();
		$expectedConfig= $phpConf->keys->{$phpConf->default_key};

		$this->assertJsonStringEqualsJsonString (
				json_encode($expectedConfig),
				json_encode($actualConfig)
				);

	}

	/**
	 * Get config for specific (non-default) key
	 *
	 * @dataProvider configProvider
	 *
	 * @param string $filename path to sample config file
	 * @param string $jsonConf mock json config settings
	 * @param mixed $phpConf parsed json config
	 */
	public function testGetKeyConfigWithKey($filename, $jsonConf, $phpConf) {

		$configKey = 'account2';

		$config = new Config ();
		$config->loadFromString ( $jsonConf );
		$actualConfig = $config->getKeyConfig($configKey);
		$expectedConfig= $phpConf->keys->{$configKey};

		$this->assertJsonStringEqualsJsonString (
				json_encode($expectedConfig),
				json_encode($actualConfig)
		);

	}

	/**
	 *
	 * @dataProvider configProvider
	 *
	 * @param string $filename path to sample config file
	 * @param string $jsonConf mock json config settings
	 * @param mixed  $phpConf parsed json config
	 */
	public function testGetEndpoint($filename, $jsonConf, $phpConf) {

		$altConfKey = 'account2';
		$expectedEndpoint = 'https://api.nsone.net/v1/';
		$expectedEndpointAltConf = 'https://api.nsone.net:444/v1/';

		$config = new Config ();
		$config->loadFromString ( $jsonConf );
		$actualEndpoint = $config->getEndpoint();

		$this->assertEquals($expectedEndpoint, $actualEndpoint);

		# test alt endpoint, :444
		$config['port'] = 444;

		$actualEndpoint = $config->getEndpoint();
		$this->assertEquals($expectedEndpointAltConf, $actualEndpoint);

	}

	/**
	 *
	 * @dataProvider configProvider
	 *
	 * @param string $filename path to sample config file
	 * @param string $jsonConf mock json config settings
	 * @param mixed  $phpConf parsed json config
	 */
	public function testGetAPIKey($filename, $jsonConf, $phpConf) {

		if (! file_exists ( $filename )) {
			$this->markTestSkipped ( 'Missing test API config file: {$this->NSONE_config_file}.' );
		}

		$config = new Config ();
		$config->loadFromString ( $jsonConf );
		$actualKey = $config->getAPIKey ();
		$expectedKey = $phpConf->keys->{$phpConf->default_key}->key;

		$this->assertEquals ( $expectedKey, $actualKey );

	}


	/**
	 * Tests non-existent conf key
	 *
	 * @dataProvider configProvider
	 *
	 * @param string $filename path to sample config file
	 * @param string $jsonConf mock json config settings
	 * @param mixed  $phpConf parsed json config
	 */
	public function testUseKeyIDNonExistent($filename, $jsonConf, $phpConf) {

		$this->setExpectedException('Exception');

		$config = new Config ();
		$config->loadFromString ( $jsonConf );
		$config->useKeyID('foobar');

	}

	/**
	 * return nsone config from tests/data/nsone.json
	 *
	 * @return array of arrays with sample data filepath and json conf data
	 */
	public function configProvider() {

		$data = array ();

		$filename = dirname ( __FILE__ ) . "/../data/nsone.json";
		$json = file_get_contents ( $filename, "r" );

		$decoded = json_decode ( $json );
		if ($decoded === NULL)
			throw new \Exception ( "Failed parsing json config file: {$filename}" );

			// each test using this provider is passed
			// path-to-conf-file, conf json string, php struct with data
		$data [] = array (
				'filename' 			=> $filename,
				'json'		 			=> $json,
				'decoded_json'	=> $decoded
		);

		return $data;

	}

}


