<?php
namespace mwAutocompleteExternal\connectors;

interface Searchable {
	/**
	 * Submit a query to the web service 
	 * and parse the results into a string
	 * suitable for autocompletion.
	 * 
	 * @param string $query the string to autocomplete
	 */
	function search( $query );
}

abstract class AbstractSearcher {
	protected $snoopy;

	protected function init( $snoopy ) {
		$this->snoopy = $snoopy;		
	}

	/**
	 * Submit them to the web service and return the raw (unparsed) result 
	 * 
	 * @param String $searchUrl
	 */
	protected function submit( $searchUrl ) {
		$this->snoopy->submit( $searchUrl );
		return $this->snoopy->results;
	}
	
	/**
	 * Get a json object from the api
	 *
	 * @param unknown $url
	 * @return mixed
	 */
	protected function getJson( $url ) {
		$response = $this->submit( $url );
		// make sure encoding is correct
		$response = utf8_encode( $response );
		$json = json_decode($response, true);
		return $json;
	}
	
	/**
	 * Format an array of results into an autocompletion string
	 * 
	 * @param array $parts
	 */
	protected function format( array $parts ) {
		sort($parts);
		
		$resp = '{"sfautocomplete": [';
		for( $i = 0; $i < count( $parts ); $i++ ) {
			$resp .= '{ "title" : "' . $parts[ $i ] . '"},';
		}
		$resp = substr( $resp, 0, -1 );
		$resp .= ']}';
		return $resp;
	}
} 
