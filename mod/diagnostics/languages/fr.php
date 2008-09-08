<?php
	/**
	 * translation by RONNEL Jérémy
	 * jeremy.ronnel@elbee.fr
	 */

	$french = array(
	
			'diagnostics' => 'Diagnostic système',
	
			'diagnostics:description' => 'Ce rapport vous sera utile pour identifier tous les problèmes rencontrés par Elgg, il pourra être joint à votre tracker de bug.',
	
			'diagnostics:download' => 'Diagnostic.txt',
	
	
			'diagnostics:header' => '========================================================================
Elgg Diagnostic Report
Generated %s by %s
========================================================================
			
',
			'diagnostics:report:basic' => '
Elgg Release %s, version %s

------------------------------------------------------------------------',
			'diagnostics:report:php' => '
PHP info:
%s
------------------------------------------------------------------------',
			'diagnostics:report:plugins' => '
Installed plugins and details:

%s
------------------------------------------------------------------------',
			'diagnostics:report:md5' => '
Installed files and checksums:

%s
------------------------------------------------------------------------',
			'diagnostics:report:globals' => '
Global variables:

%s
------------------------------------------------------------------------',
	
	);
					
	add_translation("fr",$french);
?>