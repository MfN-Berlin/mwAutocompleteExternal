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
	
	/**
	 * Query with multiple entries separated by ';'
	 * Only the last one is processed
	 */
	public function testMultiple() {
		// only the last will be processed
		$resp = $this->auto->search( 'test;' . $this->query );
		$this->assertEquals( $this->expected, $resp );
		// what happens if the string is terminated by a separator?
		$resp = $this->auto->search( 'test;' . $this->query . ';' );
		$this->assertEquals( $this->expected, $resp );
		// what happens if the string starts by a separator?
		$resp = $this->auto->search( ';' . $this->query );
		$this->assertEquals( $this->expected, $resp );
		// what happens if there's a space after the separator?
		$resp = $this->auto->search( 'test; ' . $this->query );
		$this->assertEquals( $this->expected, $resp );
	}
	
}	