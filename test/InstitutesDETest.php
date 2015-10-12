<?php
include __DIR__ . "/../src/connectors/InstitutesDE.php";
use \mwAutocompleteExternal\connectors\InstitutesDE as InstitutesDE;

/**
 * Unit tests for class InstitutesDE autocompleter
 * 
 * @author Alvaro.Ortiz
 *
 */
class InstitutesDETest extends PHPUnit_Framework_TestCase {
	public $auto;
	private $query = "Berlin";
	private $expected = '{"sfautocomplete": [{ "title" : Freie Universität Berlin},{ "title" : Humboldt-Universität zu Berlin},{ "title" : Technische Universität Berlin},{ "title" : Universität der Künste Berlin}]}';
	
	public function setUp() {
		// path to configuration .ini file
		$configPath =  __DIR__ . "/test.ini";
		$ini = parse_ini_file( $configPath );

		// Create a Snoopy (HTML client) instance
		require_once $ini[ 'snoopyPath' ];
		$snoopy = new Snoopy();
		
		// Create importer instance
		$this->auto = new InstitutesDE( $snoopy );
	}
	
	public function testCreateInstance() {
		$this->assertNotNull( $this->auto );
	}
	
	public function testSubmit() {
		$resp = $this->auto->search( $this->query );
		$this->assertEquals( $this->expected, $resp );
	} 
}
