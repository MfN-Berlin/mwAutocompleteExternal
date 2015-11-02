<?php
namespace mwAutocompleteExternal\connectors;
include __DIR__ . "/Autocompleter.php";

/**
 * Queries Wikispecies for taxon names.
 * 
 * @author Alvaro.Ortiz
 */
class Wikispecies extends AbstractAutocompleter implements Autocompleter {	
	protected $wikispeciesUrl = "http://species.wikimedia.org/w/api.php?action=opensearch&format=json&search=";
	
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
		$query = $this->prepareQueryString( $query );
		$json = $this->getJson( $this->wikispeciesUrl . $query );
		$result = $this->format( $json[1] );
		return $result;
	}
	
}
