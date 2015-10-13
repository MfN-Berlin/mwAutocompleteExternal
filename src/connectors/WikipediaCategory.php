<?php
namespace mwAutocompleteExternal\connectors;
include __DIR__ . "/Autocompleter.php";

/**
 * Queries Wikipedia for a list of pages in given category
 *  
 * @author Alvaro.Ortiz
 */
class WikipediaCategory extends AbstractAutocompleter implements Autocompleter {
	/** The Wikipedia category to search */
	protected $category;
	
	/** The Wikipedia language to search */
	protected $lang;
	
	/**
	 * Constructor
	 *
	 * @param Snoopy $snoopy Class to simulate a web browser
	 * @param string $category a category to narrow the results 
	 * @param string $lang e.g. de, en
	 */
	public function __construct( $snoopy, $category='', $lang='' ) {
		$this->init( $snoopy );
		$this->category = explode('|', $category);
		$this->lang = $lang;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Autocompleter::search()
	 */
	public function search( $query ) {
		$found = [];
		$list = [];
		foreach( $this->category as $entry ) {
			// get the category from Wikipedia
			$url = sprintf( "http://%s.wikipedia.org/w/api.php?action=query&list=categorymembers&cmlimit=500&cmprop=title&format=json&cmtitle=Category:%s", $this->lang, urlencode( $entry ) );
			$response = $this->submit( $url );
			// list the category page titles
			$parsed = $this->parse( $response );
			$list = array_merge( $list, $parsed );
		}
						
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
