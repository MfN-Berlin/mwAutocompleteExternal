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
		case 'wikispecies' :
			include __DIR__ . "/connectors/Wikispecies.php";
			$auto = new \mwAutocompleteExternal\connectors\Wikispecies( $snoopy );
			
		case 'institutesDE' :
			include __DIR__ . "/connectors/InstitutesDE.php";
			$auto = new \mwAutocompleteExternal\connectors\InstitutesDE( $snoopy );
			
		default:
			throw new Exception( 'Unknown data source.' );				
	}
	
	// get the autocomplete data (as JSON)
	$query = htmlspecialchars($_GET["search"]);
	$resp = $auto->search( $query );
	echo $resp;
	
} catch( Exception $e ) {
	echo $e->getMessage();
}



