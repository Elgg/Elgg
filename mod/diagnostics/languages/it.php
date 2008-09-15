<?php

     /**
	 * Elgg Plugin language pack
	 * 
	 * @package Elgg Diagnostic
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * ****************************************
     * @Italian Language Pack
     * @Plugin: Diagnostic
     * @version: beta 
     * english_revision: 2061
     * @translation by Lord55  
     * @link http://www.nobilityofequals.com
     ****************************************/

	$italian = array(
	
			'diagnostics' => 'Sistema di Diagnostica',
	
			'diagnostics:description' => 'Il seguente rapporto diagnostico è utile per una diagnosi di qualsiasi problema con Elgg, e potrebbe essere allegato e archiviato a qualsiasi rapporto sui bug di sistema.',
	
			'diagnostics:download' => 'Scarica .txt',
	
	
			'diagnostics:header' => '========================================================================
Rapporto di diagnostica di Elgg
Generato %s da %s
========================================================================
			
',
			'diagnostics:report:basic' => '
Elgg Release %s, versione %s

------------------------------------------------------------------------',
			'diagnostics:report:php' => '
PHP info:
%s
------------------------------------------------------------------------',
			'diagnostics:report:plugins' => '
Installazione plugins and dettagli:

%s
------------------------------------------------------------------------',
			'diagnostics:report:md5' => '
Installazione files and checksums:

%s
------------------------------------------------------------------------',
			'diagnostics:report:globals' => '
Variabili globali:

%s
------------------------------------------------------------------------',
	
	);
					
	add_translation("it",$italian);
?>