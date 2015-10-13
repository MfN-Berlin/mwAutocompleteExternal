<?php
// path to configuration .ini file
$configPath =  __DIR__ . "/config.ini";

try {
		// Create a Snoopy (HTML client) instance
		// Snoopy is an external dependency,
		// so it's instantiated here in the main
		$ini = parse_ini_file( $configPath );
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
					$auto = new \mwAutocompleteExternal\connectors\Wikispecies( $snoopy );
					break;
					
				// search wikipedia for pages in a category
				case 'wikipediacategory' :
					include __DIR__ . "/connectors/WikipediaCategory.php";
					$category = htmlspecialchars($_GET["category"]);
					$lang = htmlspecialchars($_GET["lang"]);
					$auto = new \mwAutocompleteExternal\connectors\WikipediaCategory( $snoopy, $category, $lang );
					break;
					
				// Queries Regensburger Verbundklassifikation (German library reference).
				case 'rvk' :
					include __DIR__ . "/connectors/RVK.php";
					$auto = new \mwAutocompleteExternal\connectors\RVK( $snoopy );
					break;
							
				default:
					throw new Exception( 'Unknown data source.' );
		}

		// get the autocomplete data (as JSON)
		$query = htmlspecialchars($_GET["search"]);
		$resp = $auto->search( $query );
		echo $resp;

} catch( Exception $e ) {
		file_put_contents( $ini[ 'log' ], $e->getMessage() . "\n", FILE_APPEND );
}
