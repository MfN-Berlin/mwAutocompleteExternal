<?php
include_once __DIR__ . "/../src/connectors/WikipediaCategory.php";
use \mwAutocompleteExternal\connectors\WikipediaCategory as WikipediaCategory;
use \mwAutocompleteExternal\connectors\Autocompleter as Autocompleter;

/**
 * Unit tests for class WikipediaCategory autocompleter
 * 
 * @author Alvaro.Ortiz
 *
 */
class WikipediaCategoryTest extends PHPUnit_Framework_TestCase {
	public $auto;
	private $query = "Berlin";
	private $expected = '{"sfautocomplete": [{ "title" : "Freie Universität Berlin"},{ "title" : "Humboldt-Universität zu Berlin"},{ "title" : "Technische Universität Berlin"},{ "title" : "Universität der Künste Berlin"}]}';
	
	public function setUp() {
		// path to configuration .ini file
		$configPath =  __DIR__ . "/test.ini";
		$ini = parse_ini_file( $configPath );

		// Create a Snoopy (HTML client) instance
		require_once $ini[ 'snoopyPath' ];
		$snoopy = new Snoopy();
		
		// Create importer instance
		$this->auto = new Autocompleter( new WikipediaCategory( $snoopy, 'Universität in Deutschland|Forschungsorganisation', 'de' ) );
	}
	
	public function testCreateInstance() {
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
		$resp = $this->auto->search( 'test;' . $this->query );
		$this->assertEquals( $this->expected, $resp );
		// what happens if the string is terminated by a separator?
		$resp = $this->auto->search( 'test;' . $this->query . ';' );
		$this->assertEquals( $this->expected, $resp );
		// what happens if the string starts by a separator?
		$resp = $this->auto->search( ';' . $this->query );
		$this->assertEquals( $this->expected, $resp );
	}
}
