<?php
namespace mwAutocompleteExternal\connectors;
include_once __DIR__ . "/RVKRegister.php";

/**
 * Queries Regensburger Verbundklassifikation (German library reference).
 * Use this class to query the register for a term.
 * 
 * Will search for $filter in RVK and then search the results for $query.
 *  
 * @author Alvaro.Ortiz
 */
class RVKFiltered extends RVKRegister implements Autocompleter {
	/** A term to narrow the search */
	protected $filter;
	
	/**
	 * Constructor
	 *
	 * @param Snoopy $snoopy Class to simulate a web browser
	 */
	public function __construct( $snoopy, $filter='' ) {
		$this->init( $snoopy );
		$this->filter = explode('|', $filter);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Autocompleter::search()
	 */
	public function search( $query ) {
		$found = [];
		$list = [];
		foreach( $this->filter as $entry ) {
			$url = sprintf( 'http://rvk.uni-regensburg.de/api/json/register/' . $entry );
			$response = $this->getJson( $url );
			
			// list the node paths
			$nodes = $this->parseNodes( $response );
			$parsed = $this->getNodePaths( $nodes );
			$list = array_merge( $list, $parsed );
		}
		
		// search the node paths titles for $query
		foreach( $list as $entry) {
			if ( stripos( $entry, $query ) !== FALSE ) {
				$found[] = $entry;
			} 
		}
		$result = $this->format( $found );

		return $result;
	}
	
}
