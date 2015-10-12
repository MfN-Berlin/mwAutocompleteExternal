<?php
namespace mwAutocompleteExternal\connectors;
include __DIR__ . "/Autocompleter.php";

/**
 * Queries Wikipedia for a list of pages in given category
 *  
 * @author Alvaro.Ortiz
 */
class WikipediaCategories extends AbstractAutocompleter implements Autocompleter {	
	protected $categoryUrl = 'http://de.wikipedia.org/w/api.php?action=query&list=categorymembers&cmlimit=1000&cmprop=title&format=json&cmtitle=Category:';
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
		$found = [];
		
		// universities
		// get the category contents from wikipedia
		$response = $this->submit( $this->categoryUrl . $query );
		// list the category page titles
		$list = $this->parse( $response1 );
				
		// search the category page titles
		foreach( $list as $entry) {
			if ( stripos( $entry, $query ) !== FALSE ) {
				$found[] = $entry;
			} 
		}
		$result = $this->format( $found );
		return $result;
	}
	
	/**
	 * Parses the result string and returns a JSON autocomplete string.
	 * 
	 * @param String $string
	 * @return String
	 */
	protected function parse( $string ) {
		$list = [];
		$json = json_decode($string, true);
		foreach( $json["query"]["categorymembers"] as $val ) {
			$list[] = '"' . $val["title"] . '"';
		}
		return $list;
	}
}
