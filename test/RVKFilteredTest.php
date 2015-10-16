<?php
include __DIR__ . "/../src/connectors/RVKFiltered.php";
use \mwAutocompleteExternal\connectors\RVKFiltered as RVKFiltered;

/**
 * Unit tests for class RVKRegister autocompleter
 * 
 * @author Alvaro.Ortiz
 *
 */
class RVKFilteredTest extends PHPUnit_Framework_TestCase {	
	// filtered search
	public $filterRVK;
	private $filter = "Methode";
	private $queryFiltered = "Biologie";
	private $expectedFiltered = '{"sfautocomplete": [{ "title" : "Biologie/Methoden/&quot;Hand- und Lehrbücher&quot;/Hoppe-Seyler-Thierfelder"},{ "title" : "Biologie/Methoden/Biochemische Methoden/Allgemeines, Praktika; &quot;Methods of biochemical analysis&quot;"},{ "title" : "Biologie/Methoden/Biochemische Methoden/Enzymatische Analyse, Methoden der Enzymologie (auch: ELISA)"},{ "title" : "Biologie/Methoden/Biochemische Methoden/Methoden der Hormonforschung"},{ "title" : "Biologie/Methoden/Biogeographische Methoden"},{ "title" : "Biologie/Methoden/Bioklimatische und biometeorologische Methoden"},{ "title" : "Biologie/Methoden/Biologische Methoden insgesamt"},{ "title" : "Biologie/Methoden/Botanische Methoden"},{ "title" : "Biologie/Methoden/Cytologische Methoden (Flow Cytometry, Immuncytochemie), künstliche Zellen; &quot;Methods in cell biology&quot;"},{ "title" : "Biologie/Methoden/Genetische Methoden/Allgemeines, Praktika"},{ "title" : "Biologie/Methoden/Genetische Methoden/Cytogenetische Methoden"},{ "title" : "Biologie/Methoden/Genetische Methoden/Molekulargenetische Methoden: DNS-Analyse, Klonierung, Mutagenese etc."},{ "title" : "Biologie/Methoden/Hydrobiologische Methoden, Wasseranalyse"},{ "title" : "Biologie/Methoden/Methoden der Angewandten Ökologie, Umweltverschmutzung"},{ "title" : "Biologie/Methoden/Methoden der Parasitologie"},{ "title" : "Biologie/Methoden/Mikrobiologische, immunologische und virologische Methoden"},{ "title" : "Biologie/Methoden/Physikalisch-chemische Methoden/Allgemeines"},{ "title" : "Biologie/Methoden/Physikalisch-chemische Methoden/Kryobiologische Methoden (Gefriertrocknung, Kryofixierung)"},{ "title" : "Biologie/Methoden/Physikalisch-chemische Methoden/Laser- und Maser-Methoden"},{ "title" : "Biologie/Methoden/Physikalisch-chemische Methoden/Methoden der Strahlenbiologie; radioaktive Stoffe und Markierungen, Autoradiographie, Dosimetrie"},{ "title" : "Biologie/Methoden/Physikalisch-chemische Methoden/Methoden und Praktika der Biophysik"},{ "title" : "Biologie/Methoden/Physikalisch-chemische Methoden/Mikroskopie und mikroskopische Techniken allgemein/Histologische Techniken, Mikrotomie"},{ "title" : "Biologie/Methoden/Physiologische Methoden; Elektrophysiologie, Patch-clamp"},{ "title" : "Biologie/Methoden/Zoologische Methoden, Methoden der experimentellen Zoologie und Verhaltensforschung"},{ "title" : "Biologie/Methoden/Zoologische Methoden, Methoden der experimentellen Zoologie und Verhaltensforschung/Morphologisch-anatomische Methoden, Präparationstechniken, Methoden der Embryologie"},{ "title" : "Biologie/Methoden/Ökologische Methoden"},{ "title" : "Biologie/Pflanzenphysiologie/Stoffwechsel und Ernährung/Methoden der Pflanzenanalyse"},{ "title" : "Biologie/Ökologie/Regionale Ökologie und Biogeographie/Pflanzensoziologie, Geobotanik, Vegetationskunde/Kartierung (Geobotanische Anleitungen und Methoden)"},{ "title" : "Chemie und Pharmazie/Pharmazeutische Biologie/Pharmazeutische Biologie allgemein/Mikroskopie Mikroskopische Analyse s.a. VG 6900"}]}';
	
	public $filterRVK2;
	private $filter2 = "Forschungsmethode";
	private $queryFiltered2 = "Astronomie";
	private $expectedFiltered2 = '{"sfautocomplete": [{ "title" : "Physik/Astronomie, Astrophysik/Astrophysik, Kosmologie/Planetensysteme allgemein, Exoplaneten/Suche nach Exoplaneten"}]}';
	
	public function setUp() {
		// path to configuration .ini file
		$configPath =  __DIR__ . "/test.ini";
		$ini = parse_ini_file( $configPath );

		// Create a Snoopy (HTML client) instance
		require_once $ini[ 'snoopyPath' ];
		$snoopy = new Snoopy();
				
		// Create autocompleter instance for filtered search
		$this->filterRVK = new RVKFiltered( $snoopy, $this->filter );
		
		// Create autocompleter instance for 2 filters
		$this->filterRVK2 = new RVKFiltered( $snoopy, $this->filter2 );
	}
	
	// filtered search
	public function testCreateFilteredInstance() {
		$this->assertNotNull( $this->filterRVK );
	}
	
	public function testSubmitFiltered() {
		$resp = $this->filterRVK->search( $this->queryFiltered );
		$this->assertEquals( $this->expectedFiltered, $resp );
	} 
	
	public function testSubmitFiltered2() {
		$resp = $this->filterRVK2->search( $this->queryFiltered2 );
		$this->assertEquals( $this->expectedFiltered2, $resp );
	} 
}

