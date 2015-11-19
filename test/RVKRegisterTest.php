<?php
include_once __DIR__ . "/../src/connectors/Autocompleter.php";
include_once __DIR__ . "/../src/connectors/RVKRegister.php";
use \mwAutocompleteExternal\connectors\RVKRegister as RVKRegister;
use \mwAutocompleteExternal\connectors\Autocompleter as Autocompleter;

/**
 * Unit tests for class RVKRegister autocompleter
 * 
 * @author Alvaro.Ortiz
 *
 */
class RVKRegisterTest extends PHPUnit_Framework_TestCase {
	// Direct search
	public $auto;
	private $query = "Meeresforschung";
	private $expected = '{"sfautocomplete": [{ "title" : "Biologie/Ökologie/Hydrobiologie (Meereskunde und Limnologie)/Meereskunde allgemein"},{ "title" : "Biologie/Ökologie/Hydrobiologie (Meereskunde und Limnologie)/Meereskunde allgemein/Chemische Grundlagen"},{ "title" : "Biologie/Ökologie/Hydrobiologie (Meereskunde und Limnologie)/Meereskunde allgemein/Physikalische Grundlagen"},{ "title" : "Geographie/Nicht regional gebundene Darstellungen/Allgemeine Geographie/Mathematische Geographie und Physiogeographie/Hydrogeographie/Meereskunde (Ozeanographie)/Gesamtdarstellungen"},{ "title" : "Geographie/Nicht regional gebundene Darstellungen/Allgemeine Geographie/Mathematische Geographie und Physiogeographie/Hydrogeographie/Meereskunde (Ozeanographie)/Nachschlagewerke, Aufgaben, Methoden u.ä."},{ "title" : "Geographie/Nicht regional gebundene Darstellungen/Allgemeine Geographie/Mathematische Geographie und Physiogeographie/Hydrogeographie/Meereskunde (Ozeanographie)/Teilgebiete und Einzelfragen"},{ "title" : "Geographie/Nicht regional gebundene Darstellungen/Allgemeine Geographie/Mathematische Geographie und Physiogeographie/Hydrogeographie/Meereskunde (Ozeanographie)/Teilgebiete und Einzelfragen/Regionale Ozeanographie"}]}';
		
	public function setUp() {
		// path to configuration .ini file
		$configPath =  __DIR__ . "/test.ini";
		$ini = parse_ini_file( $configPath );

		// Create a Snoopy (HTML client) instance
		require_once $ini[ 'snoopyPath' ];
		$snoopy = new Snoopy();
		
		// Create autocompleter instance for direct search
		$this->auto = new Autocompleter( new RVKRegister( $snoopy ) );
		
	}

	// Direct search
	public function testCreateDirectInstance() {
		$this->assertNotNull( $this->auto );
	}
	
	public function testSubmitDirect() {
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
