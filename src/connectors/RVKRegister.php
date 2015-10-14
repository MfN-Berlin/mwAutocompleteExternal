<?php
namespace mwAutocompleteExternal\connectors;
include __DIR__ . "/Autocompleter.php";

/**
 * Queries Regensburger Verbundklassifikation (German library reference).
 * Use this class to query the register for a term.
 * 
 * If instatiated without a filter, will do a direct search in the RVK
 * If instantiated with a filter, will search for the filter term and then search the results.
 *  
 * @author Alvaro.Ortiz
 */
class RVKRegister extends AbstractAutocompleter implements Autocompleter {
	/** A term to narrow the search */
	protected $filter;
	
	/**
	 * Constructor
	 *
	 * @param Snoopy $snoopy Class to simulate a web browser
	 */
	public function __construct( $snoopy, $filter='' ) {
		$this->init( $snoopy );
		$this->filter = $filter;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Autocompleter::search()
	 */
	public function search( $query ) {
		if ( $this->filter != "" ) {
			return $this->filterSearch( $query );
		} else {
			return $this->directSearch( $query );
		}
	}
	
	/**
	 * Will search for $filter in RVK and then search the results for $query.
	 *
	 * @param string $query
	 * @return string a json string suitable for autocomplete
	 */
	protected function filterSearch( $query ) {
		$found = [];
		$url = sprintf( 'http://rvk.uni-regensburg.de/api/json/register/' . $this->filter );
		$response = $this->getJson( $url );
		
		// list the node paths
		$nodes = $this->parseNodes( $response );
		$list = $this->getNodePaths( $nodes );
		
		// search the node paths titles for $query
		foreach( $list as $entry) {
			if ( stripos( $entry, $query ) !== FALSE ) {
				$found[] = $entry;
			} 
		}
		$result = $this->format( $found );

		return $result;
	}
	
	/**
	 * Will do a direct search for $query in the RVK
	 * 
	 * @param string $query
	 * @return string a json string suitable for autocomplete
	 */
	protected function directSearch( $query ) {
		$url = sprintf( 'http://rvk.uni-regensburg.de/api/json/register/' . $query );
		$response = $this->getJson( $url );
		
		// list the node paths
		$nodes = $this->parseNodes( $response );
		$list = $this->getNodePaths( $nodes );
		
		$result = $this->format( $list );
		return $result;
	}
	
	/**
	 * Parses the query result and returns an array of node ids
	 *
	 * @param String $string a JSON query result
	 * @return array<String> an array of node ids
	 */
	protected function parseNodes( $json ) {
		$list = [];
		// make sure encoding is correct
		foreach( $json["Register"] as $val ) {
			// pick up good results
			if ( array_key_exists( "match", $val ) && ( $val["match"] == "following" || $val["match"] == "exact" ) ) {
				$list[] = $val["notation"];
			}
		}
		return $list;
	}
	
	/**
	 * Gets the complete paths of nodes in array
	 *
	 * @param array $nodes
	 */
	protected function getNodePaths( array $nodes ) {
		$url = sprintf( 'http://rvk.uni-regensburg.de/api/json/node/' );
		$path = [];
		foreach( $nodes as $id ) {
			$json = $this->getJson( $url . urlencode( $id ) );
			$crumbs = $json[ "node" ][ "register" ];
			$crumbs[] = $json[ "node" ][ "benennung" ];
			$path[] = implode( "/", $crumbs );
		}
	
		return $path;
	}
	
	/**
	 * Gets the complete paths of nodes in array
	 * 
	 * @param array $nodes
	 */
	protected function getNodePaths_BAK( array $nodes ) {
		$url = sprintf( 'http://rvk.uni-regensburg.de/api/json/ancestors/' );
		$path = [];
		foreach( $nodes as $id ) {
			$json = $this->getJson( $url . urlencode( $id ) );
			$crumbs = $this->parseNodePath( $json[ "node" ] );
			$path[] = implode( "/", array_reverse( $crumbs ) );
		}
		
		return $path;
	}
	
	/**
	 * Recursivelly parse the path to the given node.
	 * 
	 * @param array<String> $crumbs
	 * @param Node $node
	 */
	protected function parseNodePath( $node, $crumbs = [] ) {
		$crumbs[] = $node[ "benennung" ];
		
		if ( array_key_exists( "ancestor", $node ) ) {
			$ancestor = $node[ "ancestor" ];
			return $this->parseNodePath( $ancestor[ "node" ], $crumbs );
			
		} else {
			return $crumbs;
		}
	}
	
}
