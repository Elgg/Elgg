<?php
    // units/engine/main.php
    // units/engine/library.php
    // units/engine/function_init.php

    // Plug-in engine intialisation routines

    // Global variables
    global $log;
    global $errorlog;
    global $actionlog;
    $log = array();
    $errorlog = array();
    $actionlog = array();

    // Message arrays
    global $messages;
    if (empty($messages)) { // might be set up already...
        $messages = array();
    }

    // Add the site root to the metatags
    global $metatags;
    $metatags .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . "\n";
    // $metatags .= "     <base href=\"".url."\" />";

    // Initialise array of functions
    global $function;
    $function = array();
    
    // Array of useful data
    global $data;
    $data = array();
    
    // Plug-in library functions
    function run($context, $parameter = array()) {
        global $function;
        global $log;
        global $actionlog;
        global $errorlog;
        global $messages;
        global $data;
        global $CFG;
        
        global $run_context;

        static $code_cache;
        static $use_cache;

        if (!isset($use_cache)) $use_cache = ($CFG->debug > 7) ? false : true;
        if ($use_cache && !isset($code_cache)) $code_cache = array();
        //$code_cache=null; //turn off cache
        
        $run_result = false;
        
        if (isset($function[$context])) {
            if (isset($_REQUEST['debug'])) {
                echo "<small>\{$context}</small>";
            }
            foreach($function[$context] as $include) {
                if (!is_readable($include)) {
                    trigger_error(__FUNCTION__.": can not load elgg function $include", E_USER_ERROR);
                } else {
                    if ($use_cache) {
                        if (isset($code_cache[$context][$include])) {
                            eval('?>'.$code_cache[$context][$include]);
                            //echo "cache: run('$context')<br/>";
                        } else {
                            $code = @file_get_contents($include);
                            // run code
                            eval('?>'.$code);

                            if (!isset($code_cache[$context])) $code_cache[$context] = array();
                            // cache code
                            $code_cache[$context][$include] = $code;
                        }
                    } else {
                        include($include);
                    }
                }
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
        
    // Set default charset to UTF-8
    @ini_set("default_charset","UTF-8");
    header("Content-Type: text/html; charset=utf-8");

?>
