<?php
namespace mwAutocompleteExternal\connectors;

class Autocompleter {
	protected $searcher;
	
	/**
	 * # Inject the Snoopy (HTML client) instance
	 * # Set the searchable class
	 */
	public function __construct( Searchable $searcher ) {
		$this->searcher = $searcher;
	}

	public function search( $query ) {
		$query = $this->prepareQueryString($query);
		$json = $this->searcher->search($query); 
		$result = $this->format( $json );
		return $result;
	}
	
	/**
	 * Process the given query string before it is used to search autocomplete values.
	 * * Detect multiple values in a field, default separator is ';'
	 */
	protected function prepareQueryString( $query, $separator=';' ) {
		$query = trim($query);
		// pop last char if last char is a separator
		if ( substr($query, -1) == $separator ) $query = substr( $query, 0, -1 );
		$result = "";
		// if no separator present, return the query string as is
		if (strpos( $query, $separator ) === FALSE ) $result = $query;
		// otherwise return the last part of the query string
		$parts = explode( $separator, $query );
		$result = array_pop($parts);
		return trim( $result );
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
