<?php
include_once __DIR__ . "/../src/connectors/Autocompleter.php";
include_once __DIR__ . "/../src/connectors/RVK.php";
use \mwAutocompleteExternal\connectors\Autocompleter as Autocompleter;
use \mwAutocompleteExternal\connectors\RVK as RVK;

/**
 * Unit tests for class RVK autocompleter
 * 
 * @author Alvaro.Ortiz
 *
 */
class RVKTest extends PHPUnit_Framework_TestCase {
	public $auto;
	private $query = "Philosophie";
	private $expected = '{"sfautocomplete": [{ "title" : "Mathematik/Biographien, Geschichte und Philosophie der Mathematik"},{ "title" : "Philosophie"},{ "title" : "Philosophie/Allgemeines"},{ "title" : "Philosophie/Geschichte der Philosophie"},{ "title" : "Philosophie/Systematische Philosophie"},{ "title" : "Philosophie/Zeitschriften und Reihenwerke"},{ "title" : "Physik/Allgemeine Nachschlagewerke, Bibliographien, Geschichte und Philosophie der Physik"},{ "title" : "Politologie/Geschichte der politischen Philosophie und der Ideologien"},{ "title" : "Rechtswissenschaft/Allgemeine Rechtslehre und Rechtstheorie, Rechts- und Staatsphilosophie, Rechtssoziologie"},{ "title" : "Rechtswissenschaft/Allgemeine Staatslehre und Staatsphilosophie"},{ "title" : "Theologie und Religionswissenschaften/Philosophie"}]}';
		
	public function setUp() {
		// path to configuration .ini file
		$configPath =  __DIR__ . "/test.ini";
		$ini = parse_ini_file( $configPath );

		// Create a Snoopy (HTML client) instance
		require_once $ini[ 'snoopyPath' ];
		$snoopy = new Snoopy();
		
		// Create importer instance
		$this->auto = new Autocompleter( new RVK( $snoopy ) );
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
