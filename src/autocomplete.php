<?php
include __DIR__ . "/connectors/Wikispecies.php";
use \mwAutocompleteExternal\connectors\Wikispecies as Wikispecies;

// path to configuration .ini file
$configPath =  __DIR__ . "/config.ini";

try {
	// Create a Snoopy (HTML client) instance
	// Snoopy is an external dependency, 
	// so it's instantiated here in the main
	$ini = parse_ini_file( $configPath );
	require_once $ini[ 'snoopyPath' ];
	$snoopy = new Snoopy();

	// Instantiate an autocompleter class
	$auto = new Wikispecies( $snoopy );

	// get the autocomplete data (as JSON)
	$query = htmlspecialchars($_GET["search"]);
	$resp = $auto->search( $query );
	echo $resp;
	
} catch( Exception $e ) {
	echo $e->getMessage();
}



