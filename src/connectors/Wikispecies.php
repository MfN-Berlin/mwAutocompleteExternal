<?php
namespace mwAutocompleteExternal\connectors;
include_once __DIR__ . "/AbstractSearcher.php";
include_once __DIR__ . "/Autocompleter.php";

/**
 * Queries Wikispecies for taxon names.
 * 
 * @author Alvaro.Ortiz
 */
class Wikispecies extends AbstractSearcher implements Searchable {	
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
	 * @see Searchable::search()
	 */
	public function search( $query ) {
		$json = $this->getJson( $this->wikispeciesUrl . $query );
		return $json[1];
	}
	
}
