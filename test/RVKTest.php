<?php
include __DIR__ . "/../src/connectors/RVK.php";
use \mwAutocompleteExternal\connectors\RVK as RVK;

/**
 * Unit tests for class RVK autocompleter
 * 
 * @author Alvaro.Ortiz
 *
 */
class RVKTest extends PHPUnit_Framework_TestCase {
	public $auto;
	private $query = "Meeresforschung";
	private $expected = '{"sfautocomplete": [{ "title" : "Geographie/Nicht regional gebundene Darstellungen/Allgemeine Geographie/Mathematische Geographie und Physiogeographie/Hydrogeographie/Meereskunde (Ozeanographie)/Nachschlagewerke, Aufgaben, Methoden u.ä."},{ "title" : "Geographie/Nicht regional gebundene Darstellungen/Allgemeine Geographie/Mathematische Geographie und Physiogeographie/Hydrogeographie/Meereskunde (Ozeanographie)/Gesamtdarstellungen"},{ "title" : "Geographie/Nicht regional gebundene Darstellungen/Allgemeine Geographie/Mathematische Geographie und Physiogeographie/Hydrogeographie/Meereskunde (Ozeanographie)/Teilgebiete und Einzelfragen"},{ "title" : "Geographie/Nicht regional gebundene Darstellungen/Allgemeine Geographie/Mathematische Geographie und Physiogeographie/Hydrogeographie/Meereskunde (Ozeanographie)/Teilgebiete und Einzelfragen/Regionale Ozeanographie"},{ "title" : "Biologie/Ökologie/Hydrobiologie (Meereskunde und Limnologie)/Meereskunde allgemein"},{ "title" : "Biologie/Ökologie/Hydrobiologie (Meereskunde und Limnologie)/Meereskunde allgemein/Physikalische Grundlagen"},{ "title" : "Biologie/Ökologie/Hydrobiologie (Meereskunde und Limnologie)/Meereskunde allgemein/Chemische Grundlagen"}]}';
		
	public function setUp() {
		// path to configuration .ini file
		$configPath =  __DIR__ . "/test.ini";
		$ini = parse_ini_file( $configPath );

		// Create a Snoopy (HTML client) instance
		require_once $ini[ 'snoopyPath' ];
		$snoopy = new Snoopy();
		
		// Create importer instance
		$this->auto = new RVK( $snoopy );
	}
	
	public function testCreateInstance() {
		$this->assertNotNull( $this->auto );
	}
	
	public function testSubmit() {
		$resp = $this->auto->search( $this->query );
		$this->assertEquals( $this->expected, $resp );
	} 
}
