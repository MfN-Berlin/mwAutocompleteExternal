<?php
namespace mwAutocompleteExternal\connectors;
include __DIR__ . "/Autocompleter.php";

/**
 * Queries Wikispedia for names of universities in Germany,
 * and research organisations in the German Wikipedia.
 * 
 * @author Alvaro.Ortiz
 */
class InstitutesDE extends AbstractAutocompleter implements Autocompleter {	
	protected $institutesUrl = 'http://de.wikipedia.org/w/api.php?action=query&list=categorymembers&cmlimit=100&cmprop=title&format=json&cmtitle=Category:Universität_in_Deutschland';
	protected $organisationsUrl = 'http://de.wikipedia.org/w/api.php?action=query&list=categorymembers&cmlimit=200&cmprop=title&format=json&cmtitle=Category:Forschungsorganisation';
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
		$response1 = $this->submit( $this->institutesUrl );
		// list the category page titles
		$list1 = $this->parse( $response1 );
		
		// organisations
		// get the category contents from wikipedia
		$response2 = $this->submit( $this->organisationsUrl );
		// list the category page titles
		$list2 = $this->parse( $response2 );
		
		// bring all results into 1 list
		$list = array_merge( $list1, $list2 );
		
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
