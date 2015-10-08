<?php
include __DIR__ . "/DummyAutocompleter.php";

/**
 * Unit tests for class AbstractAutocompleter
 * 
 * @author Alvaro.Ortiz
 *
 */
class AbstractAutocompleterTest extends PHPUnit_Framework_TestCase {
	public $auto;
	private $search = "http://species.wikimedia.org/w/api.php?action=opensearch&search=";
	private $response = '["Phoca",["Phoca","Phoca vitulina","Phocarctos","Phocarctos hookeri","Phoca largha","Phoca rosmarus","Phoca hispida","Phoca vitulina richardii","Phoca leonina","Phoca ursina"],["","","","","","","","","",""],["https://species.wikimedia.org/wiki/Phoca","https://species.wikimedia.org/wiki/Phoca_vitulina","https://species.wikimedia.org/wiki/Phocarctos","https://species.wikimedia.org/wiki/Phocarctos_hookeri","https://species.wikimedia.org/wiki/Phoca_largha","https://species.wikimedia.org/wiki/Phoca_rosmarus","https://species.wikimedia.org/wiki/Phoca_hispida","https://species.wikimedia.org/wiki/Phoca_vitulina_richardii","https://species.wikimedia.org/wiki/Phoca_leonina","https://species.wikimedia.org/wiki/Phoca_ursina"]]';
	private $query = "Phoca";
	
	public function setUp() {
		// path to configuration .ini file
		$configPath =  __DIR__ . "/test.ini";
		$ini = parse_ini_file( $configPath );

		// Create a Snoopy (HTML client) instance
		require_once $ini[ 'snoopyPath' ];
		$snoopy = new Snoopy();
		
		// Create importer instance
		$this->auto = new DummyAutocompleter( $snoopy );
	}
	
	public function testSnoopy() {
		$this->assertNotNull( $this->auto->getSnoopy() );
	}
	
	public function testSubmit() {
		$resp = $this->auto->submit( $this->search . $this->query );
		$this->assertNotNull( $resp );
	}
	
	public function testCreate() {
		$this->assertNotNull( $this->auto );
	}
	
}	