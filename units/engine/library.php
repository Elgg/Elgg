<?php

	// Global variables

	// Initialise array of functions
	
		global $function;
		$function = array();
	
	// Array of useful data
	
		global $data;
		$data = array();
	
	// Array of menus
	
		global $menu;
		$menu = array();

	// Plug-in library functions
	
		function run($context, $parameter = array()) {
			
			global $function;
			global $log;
			global $actionlog;
			global $errorlog;
			global $messages;
			global $data;
			
			global $run_context;
			
			$run_result = NULL;
			
			if (isset($function[$context])) {
				
				if (isset($_REQUEST['debug'])) {
					echo "<small>\{$context}</small>";
				}
				
				foreach($function[$context] as $include) {
					include($include);
				}
				
			} else if (isset($_REQUEST['debug'])) {
				
				echo "{<b>$context not found</b>}";
				
			}
			
			return $run_result;
			
		}

	// Time page execution
	
	
	function timer($finish = false){
    static $start_frac_sec, $start_sec, $end_frac_sec, $end_sec;
    if($finish){
        list($end_frac_sec,$end_sec) = explode(" ", microtime());
        echo '<p style="font-size: smaller">This page took about ' .
            round(
                (
                    ($end_sec - $start_sec)
                    + ($end_frac_sec - $start_frac_sec)
                ),
            4) . " seconds to generate.</p>\n";
    }else{
        list($start_frac_sec,$start_sec) = explode(" ", microtime());
    }
}
	
	timer();
		
?>