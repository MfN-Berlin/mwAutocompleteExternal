<?php
include __DIR__ . "/../src/connectors/RVKRegister.php";
use \mwAutocompleteExternal\connectors\RVKRegister as RVKRegister;

/**
 * Unit tests for class RVKRegister autocompleter
 * 
 * @author Alvaro.Ortiz
 *
 */
class RVKRegisterTest extends PHPUnit_Framework_TestCase {
	// Direct search
	public $directRVK;
	private $query = "Meeresforschung";
	private $expected = '{"sfautocomplete": [{ "title" : "Aufgabe/Meereskunde/Methode/Nachschlagewerk/Nachschlagewerke, Aufgaben, Methoden u.ä."},{ "title" : "Chemie/Meereskunde/Chemische Grundlagen"},{ "title" : "Meereskunde/Gesamtdarstellungen"},{ "title" : "Meereskunde/Meereskunde allgemein"},{ "title" : "Meereskunde/Physik/Physikalische Grundlagen"},{ "title" : "Meereskunde/Region/Regionale Ozeanographie"},{ "title" : "Meereskunde/Teilgebiete und Einzelfragen"}]}';
		
	public function setUp() {
		// path to configuration .ini file
		$configPath =  __DIR__ . "/test.ini";
		$ini = parse_ini_file( $configPath );

		// Create a Snoopy (HTML client) instance
		require_once $ini[ 'snoopyPath' ];
		$snoopy = new Snoopy();
		
		// Create autocompleter instance for direct search
		$this->directRVK = new RVKRegister( $snoopy );
		
	}

	// Direct search
	public function testCreateDirectInstance() {
		$this->assertNotNull( $this->directRVK );
	}
	
	public function testSubmitDirect() {
		$resp = $this->directRVK->search( $this->query );
		$this->assertEquals( $this->expected, $resp );
	} 
	
}
