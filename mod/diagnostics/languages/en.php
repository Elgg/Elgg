<?php
	/**
	 * Elgg diagnostics language pack.
	 * 
	 * @package ElggDiagnostics
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	$english = array(
	
			'diagnostics' => 'System diagnostics',
	
			'diagnostics:description' => 'The following diagnostic report is useful for diagnosing any problems with Elgg, and should be attached to any bug reports you file.',
	
			'diagnostics:download' => 'Download .txt',
	
	
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
					
	add_translation("en",$english);
?>