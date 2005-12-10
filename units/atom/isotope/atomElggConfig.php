<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * atomElgConfig.php - Elgg specific overrides.                       *
 *                                                                    *
 *********************************************************************/

	// Isotope version number
	$isoTopeVersion = $isoTopeVersion . "/Elgg";
	$isoTopeUrl     = "http://www.isolani.co.uk/projects/atom/isoTope/";

	$isoTopeUserAgent = $isoTope . " " . $isoTopeVersion . " - " . $isoTopeUrl;

	// Toggle between DEV and LIVE configuration settings
	//$env = "LIVE";		// public webserver details
	
	// Toggle logging ON or OFF
	$logging         = "OFF";
	##$logging         = "OFF";

    // Extract the url from the elgg includes
    $url = parse_url(url);

    // $sitename is a kludge, isotope adds Blog at the end, no time to fix
    $weblog = run("weblogs:instance", array('user_id' => $page_owner,
                                            'blog_id' => $page_owner));

	// Initialise global settings
	$atomDomain      = $url['host'];
	$atomPort        = "";
	$atomRootUrl     = "atom";
	$timezone        = "+01:00";
	$siteName        = substr($weblog->getTitle(),0,-6);

	// Where to log all the messages
	$logDir   = "/tmp/";
	
	// $rootUri is the path to the Atom service
	$rootUri         = "/";
	if ($atomRootUrl) {
		$rootUri .= $atomRootUrl . "/";
	}
?>
