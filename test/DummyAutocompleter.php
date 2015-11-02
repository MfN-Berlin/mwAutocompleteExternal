<?php
include_once __DIR__ . "/../src/connectors/Wikispecies.php";
use mwAutocompleteExternal\connectors\AbstractAutocompleter as AbstractAutocompleter;
use mwAutocompleteExternal\connectors\Autocompleter as Autocompleter;

/**
 * A dummy class to for testing AbstractAutocompleter (which is abstract, so cannot be instantiated directly).
 * 
 * @author Alvaro.Ortiz
 *
 */
class DummyAutocompleter extends AbstractAutocompleter implements Autocompleter {
	/**
	 * Constructor
	 *
	 * @param Snoopy $snoopy Class to simulate a web browser
	 * @param String $configPath path to configuration .ini file
	 */
	public function __construct( $snoopy ) {
		$this->init( $snoopy );
	}
	
	function getSnoopy() {
		return $this->snoopy;
	}
	
	public function submit( $searchUrl ) {
		return parent::submit($searchUrl);
	}
	
	function search( $query ) {}
	
}
