<?php
include __DIR__ . "/../src/connectors/RVKFiltered.php";
use \mwAutocompleteExternal\connectors\RVKFiltered as RVKFiltered;

/**
 * Unit tests for class RVKRegister autocompleter
 * 
 * @author Alvaro.Ortiz
 *
 */
class RVKRegisterTest extends PHPUnit_Framework_TestCase {	
	// filtered search
	public $filterRVK;
	private $filter = "Methode";
	private $queryFiltered = "Biologie";
	private $expectedFiltered = '{"sfautocomplete": [{ "title" : "Angewandte Biologie/Biologie/Methode/Naturschutz/Ökologie/Umweltverschmutzung/Methoden der Angewandten Ökologie, Umweltverschmutzung"},{ "title" : "Autoradiographie/Dosimetrie/Isotopenmarkierung/Methode/Strahlenbiologie/Methoden der Strahlenbiologie; radioaktive Stoffe und Markierungen, Autoradiographie, Dosimetrie"},{ "title" : "Biologie/Handbuch/Lehrbuch/Methode/Hoppe-Seyler-Thierfelder"},{ "title" : "Biologie/Methode/Biologische Methoden insgesamt"},{ "title" : "Gefriertrocknung/Kryobiologie/Methode/Kryobiologische Methoden (Gefriertrocknung, Kryofixierung)"},{ "title" : "Hydrobiologie/Methode/Hydrobiologische Methoden, Wasseranalyse"},{ "title" : "Immunologie/Methode/Mikrobiologie/Mikrobiologische, immunologische und virologische Methoden"},{ "title" : "Methode/Mikroskopie/Pharmazeutische Biologie/Pharmazie/Mikroskopie Mikroskopische Analyse s.a. VG 6900"}]}';
	
	public function setUp() {
		// path to configuration .ini file
		$configPath =  __DIR__ . "/test.ini";
		$ini = parse_ini_file( $configPath );

		// Create a Snoopy (HTML client) instance
		require_once $ini[ 'snoopyPath' ];
		$snoopy = new Snoopy();
				
		// Create autocompleter instance for filtered search
		$this->filterRVK = new RVKFiltered( $snoopy, $this->filter );
	}
	
	// filtered search
	public function testCreateFilteredInstance() {
		$this->assertNotNull( $this->filterRVK );
	}
	
	public function testSubmitFiltered() {
		$resp = $this->filterRVK->search( $this->queryFiltered );
		$this->assertEquals( $this->expectedFiltered, $resp );
	} 
}
