<?php
include_once __DIR__ . "/../src/connectors/Wikispecies.php";
use \mwAutocompleteExternal\connectors\Autocompleter as Autocompleter;
use \mwAutocompleteExternal\connectors\Wikispecies as Wikispecies;

/**
 * Unit tests for class Wikispecies autocompleter
 * 
 * @author Alvaro.Ortiz
 *
 */
class WikispeciesTest extends PHPUnit_Framework_TestCase {
	public $auto;
	private $query = 'Primula_palinuri'; // the Palinuro Primrose
	private $expected = '{"sfautocomplete": [{ "title" : "Primula palinuri"}]}';
	
	public function setUp() {
		// path to configuration .ini file
		$configPath =  __DIR__ . "/test.ini";
		$ini = parse_ini_file( $configPath );

		// Create a Snoopy (HTML client) instance
		require_once $ini[ 'snoopyPath' ];
		$snoopy = new Snoopy();
		
		// Create importer instance
		$this->auto = new Autocompleter( new Wikispecies( $snoopy ) );
	}
	
	public function testCreateWikispecies() {
		$this->assertNotNull( $this->auto );
	}
	
	public function testSubmit() {
		$resp = $this->auto->search( $this->query );
		$this->assertEquals( $this->expected, $resp );
	}
}	