<?php
namespace mwAutocompleteExternal\connectors;

interface Autocompleter {
	/**
	 * Submit a query to the web service 
	 * and parse the results into a string
	 * suitable for autocompletion.
	 * 
	 * @param unknown $query
	 */
	function search( $query );
}

abstract class AbstractAutocompleter {
	/** Instance of the Snoopy HTTP client */
	protected $snoopy;
	
	/**
	 * Initialize the class. Call from child class
	 * # Inject the Snoopy (HTML client) instance
	 * # Set the base URL
	 */
	protected function init( $snoopy ) {
		// The Snoopy instance
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
	 * Format an array of results into an autocompletion string
	 * 
	 * @param array $parts
	 */
	protected function format( array $parts ) {
		$resp = '{"sfautocomplete": [';
		for( $i = 0; $i < count( $parts ); $i++ ) {
			$resp .= '{ "title" : ' . $parts[ $i ] . '},';
		}
		$resp = substr( $resp, 0, -1 );
		$resp .= ']}';
		return $resp;
	}
} 
