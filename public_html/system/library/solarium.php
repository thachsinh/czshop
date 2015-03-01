<?php

function &Solarium($params = '')
{
	// Load the DB config file if a DSN string wasn't passed
	if (is_string($params) AND strpos($params, '://') === FALSE)
	{
		// Is the config file in the environment folder?
		if ( ! defined('ENVIRONMENT') OR ! file_exists($file_path = DIR_CONFIG . ENVIRONMENT.'/solr.php'))
		{
			if ( ! file_exists($file_path = DIR_CONFIG . 'solr.php'))
			{
				show_error('The configuration file solr.php does not exist.');
			}
		}

		include($file_path);

		if ( ! isset($solr) OR count($solr) == 0)
		{
			show_error('No Solr connection settings were found in the solr config file.');
		}

		$params = $solr;
	}
	
	require_once(DIR_SYSTEM.'library/Solarium/Autoloader.php');
	
	Solarium_Autoloader::register();


	// create a client instance
	$solr = new Solarium_Client($params);
	
	if(!$solr) {
		show_error('Solr Failed');
	}
	
	return $solr;
}