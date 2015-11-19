<?php
namespace mwAutocompleteExternal\connectors;
include_once __DIR__ . "/AbstractSearcher.php";
include_once __DIR__ . "/Autocompleter.php";

/**
 * Queries Regensburger Verbundklassifikation (German library reference).
 * Use this class to query the first two levels of the entire tree.
 *  
 * @author Alvaro.Ortiz
 */
class RVK extends Abstractsearcher implements Searchable {
	protected $url = 'http://rvk.uni-regensburg.de/api/json/children/'; // the root of the tree
	
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
		// call the api
		$json = $this->getJson( $this->url );
		// list the nodes
		$list = $this->parseNodes( $json );
		
		// search the results
		$found = [];
		foreach( $list as $entry) {
			if ( stripos( $entry, $query ) !== FALSE ) {
				$found[] = $entry;
			} 
		}
		
		return $found;
	}
	
	/**
	 * Parses the query result and returns an array of nodes names
	 *
	 * @param array $json a JSON query result
	 * @return array<String> an array of node ids
	 */
	protected function parseNodes( $json ) {
		$list = [];
		// get the names of the nodes
		foreach( $json["node"]["children"]["node"] as $val ) {
			$rootNodeName = $val["benennung"];
			$list[] = $rootNodeName;
			// get the subnodes
			if ( $val["has_children"] == "yes" ) {
				$notation = $val["notation"];
				$json2 = $this->getJson( $this->url . urlencode($notation) );
				// get subnode names
				foreach( $json2["node"]["children"]["node"] as $val2 ) {
					$list[] = $rootNodeName . "/" . $val2["benennung"];
				}
			}
		}
		return $list;
	}
			
}