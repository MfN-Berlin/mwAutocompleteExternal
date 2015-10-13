<?php
namespace mwAutocompleteExternal\connectors;
include __DIR__ . "/Autocompleter.php";

/**
 * Queries Regensburger Verbundklassifikation (German library reference).
 *  
 * @author Alvaro.Ortiz
 */
class RVK extends AbstractAutocompleter implements Autocompleter {
	
	/**
	 * Constructor
	 *
	 * @param Snoopy $snoopy Class to simulate a web browser
	 */
	public function __construct( $snoopy, $category='', $lang='' ) {
		$this->init( $snoopy );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Autocompleter::search()
	 */
	public function search( $query ) {
		$url = sprintf( 'http://rvk.uni-regensburg.de/api/json/register/' . $query );
		$response = $this->submit( $url );
		// list the nodes
		$nodes = $this->parseNodes( $response );
		$found = $this->getNodePaths( $nodes );
		$result = $this->format( $found );
		return $result;
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
	 * Parses the query result and returns an array of nodes ids
	 * 
	 * @param String $string a JSON query result
	 * @return array<String> an array of node ids
	 */
	protected function parseNodes( $string ) {
		$list = [];
		// make sure encoding is correct
		$string = utf8_encode( $string );
		$json = json_decode($string, true);
		foreach( $json["Register"] as $val ) {
			// pick up good results
			if ( array_key_exists( "match", $val ) && ( $val["match"] == "following" || $val["match"] == "exact" ) ) {
				$list[] = $val["notation"];
			}
		}
		return $list;
	}
}
