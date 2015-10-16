<?php
namespace mwAutocompleteExternal\connectors;
include __DIR__ . "/Autocompleter.php";

/**
 * Queries Regensburger Verbundklassifikation (German library reference).
 * Use this class to query the register for a term.
 * 
 * Will do a direct search for $query in the RVK
 *  
 * @author Alvaro.Ortiz
 */
class RVKRegister extends AbstractAutocompleter implements Autocompleter {
	
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
		$url = sprintf( 'http://rvk.uni-regensburg.de/api/json/ancestors/' );
		$path = [];
		foreach( $nodes as $id ) {
			$response = $this->submit( $url . urlencode( $id ) );
			$string = utf8_encode( $response );
			$json = json_decode($string, true);
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
	
	/**
	 * Gets the complete paths of nodes in array
	 *
	 * @param array $nodes
	 */
	protected function getNodePaths_ALT( array $nodes ) {
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
	
}
