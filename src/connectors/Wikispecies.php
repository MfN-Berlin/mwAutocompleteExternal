<?php
namespace mwAutocompleteExternal\connectors;
include __DIR__ . "/Autocompleter.php";

/**
 * Queries Wikispecies for taxon names.
 * 
 * @author Alvaro.Ortiz
 */
class Wikispecies extends AbstractAutocompleter implements Autocompleter {	
	protected $wikispeciesUrl = "http://species.wikimedia.org/w/api.php?action=opensearch&search=";
	
	/**
	 * Constructor
	 *
	 * @param Snoopy $snoopy Class to simulate a web browser
	 */
	public function __construct( $snoopy ) {
		$this->init( $snoopy );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Autocompleter::search()
	 */
	public function search( $query ) {
		$response = $this->submit( $this->wikispeciesUrl . $query );
		$result = $this->parse( $response );
		return $result;
	}
	
	/**
	 * Parses the result string and returns a JSON autocomplete string.
	 * 
	 * @param String $string
	 * @return String
	 */
	protected function parse( $string ) {
		$parts1 = explode( '[', $string);
		$parts2 = explode( ']', $parts1[2]);
		$parts3 = explode( ',', $parts2[0] );
		// drop the quotes
		for( $i = 0; $i < count($parts3); $i++) {
			$parts3[$i] = str_replace('"', "", $parts3[$i] );
		}
		$resp = $this->format( $parts3 );
		return $resp;
	}
}
