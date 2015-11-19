<?php
include_once __DIR__ . "/Autocompleter.php";
use \mwAutocompleteExternal\connectors\Autocompleter as Autocompleter;

// path to configuration .ini file
$configPath =  __DIR__ . "/config.ini";

try {
		// parse the config.ini file
		$ini = parse_ini_file( $configPath );
		// Create a Snoopy (HTML client) instance
		// Snoopy is an external dependency,
		// so it's instantiated here in the main
		require_once $ini[ 'snoopyPath' ];
		$snoopy = new Snoopy();

		// get the requested reference data source
		// and instantiate the corresponding autocomplete class
		$source = htmlspecialchars($_GET["source"]);
		if ( $source == null ) throw new Exception( 'No data source given.' );
		switch( $source ) {
				// search wikispecies for scientific names of animals or plants
				case 'wikispecies' :
					include __DIR__ . "/connectors/Wikispecies.php";
					$searcher = new \mwAutocompleteExternal\connectors\Wikispecies( $snoopy );
					break;
					
				// search wikipedia for pages in a category
				case 'wikipediacategory' :
					include __DIR__ . "/connectors/WikipediaCategory.php";
					$category = htmlspecialchars($_GET["category"]);
					$lang = htmlspecialchars($_GET["lang"]);
					$searcher = new \mwAutocompleteExternal\connectors\WikipediaCategory( $snoopy, $category, $lang );
					break;
					
				// Queries Regensburger Verbundklassifikation (German library reference): browse the tree
				case 'rvk' :
					include __DIR__ . "/connectors/RVK.php";
					$filter = htmlspecialchars($_GET["filter"]);
					$searcher = new \mwAutocompleteExternal\connectors\RVK( $snoopy, $filter );
					break;
					
				// Queries Regensburger Verbundklassifikation (German library reference): query registry
				case 'rvkregister' :
					include __DIR__ . "/connectors/RVKRegister.php";
					$searcher = new \mwAutocompleteExternal\connectors\RVKRegister( $snoopy );
					break;
					
				// Queries Regensburger Verbundklassifikation (German library reference): filter registry, then query
				case 'rvkfiltered' :
					include __DIR__ . "/connectors/RVKFiltered.php";
					$filter = htmlspecialchars($_GET["filter"]);
					$searcher = new \mwAutocompleteExternal\connectors\RVKFiltered( $snoopy, $filter );
					break;

				default:
					throw new Exception( 'Unknown data source.' );
		}
		// instantiate the autocompleter with the searcher object
		$auto = new Autocompleter( $searcher );

		// get the autocomplete data (as JSON)
		$query = htmlspecialchars($_GET["search"]);
		$resp = $auto->search( $query );
		echo $resp;

} catch( Exception $e ) {
		file_put_contents( $ini[ 'log' ], $e->getMessage() . "\n", FILE_APPEND );
}
