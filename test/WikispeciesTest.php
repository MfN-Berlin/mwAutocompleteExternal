<?php
include __DIR__ . "/../src/connectors/Wikispecies.php";
use \mwAutocompleteExternal\connectors\Wikispecies as Wikispecies;

/**
 * Unit tests for class Wikispecies autocompleter
 * 
 * @author Alvaro.Ortiz
 *
 */
class WikispeciesTest extends PHPUnit_Framework_TestCase {
	public $auto;
	private $parsed = '{"sfautocomplete": [{ "title" : "Phoca"},{ "title" : "Phoca vitulina"},{ "title" : "Phocarctos"},{ "title" : "Phocarctos hookeri"},{ "title" : "Phoca largha"},{ "title" : "Phoca rosmarus"},{ "title" : "Phoca hispida"},{ "title" : "Phoca vitulina richardii"},{ "title" : "Phoca leonina"},{ "title" : "Phoca vitulina vitulina"}]}';
	private $query = "Phoca vitulina";
	
	public function setUp() {
		// path to configuration .ini file
		$configPath =  __DIR__ . "/test.ini";
		$ini = parse_ini_file( $configPath );

		// Create a Snoopy (HTML client) instance
		require_once $ini[ 'snoopyPath' ];
		$snoopy = new Snoopy();
		
		// Create importer instance
		$this->auto = new Wikispecies( $snoopy );
	}
	
	public function testCreateWikispecies() {
		$this->assertNotNull( $this->auto );
	}
	
	public function testSubmit() {
		$resp = $this->auto->search( $this->query );
		$this->assertEquals( $this->parsed, $resp );
	} 
}	