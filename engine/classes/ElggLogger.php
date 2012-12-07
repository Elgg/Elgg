<?php

/**
 * @since 1.9
 */
class ElggLogger {
    /**
     * The logging level. Determines how many of the logs get shown on-screen.
     * Defaults to off (i.e., no logging).
     * @var string
     */
    private $level = '';
    
    /** @var ElggPluginHookService */
    private $hooks;
    
    
    function __construct(ElggPluginHookService $hooks) {
        $this->hooks = $hooks;
    }
    
    
    /**
     * Display or log a message.
     *
     * If $level is >= to the debug setting in {@link $CONFIG->debug}, the
     * message will be sent to {@link elgg_dump()}.  Messages with lower
     * priority than {@link $CONFIG->debug} are ignored.
     *
     * {@link elgg_dump()} outputs all levels but NOTICE to screen by default.
     *
     * @note No messages will be displayed unless debugging has been enabled.
     *
     * @param string $message User message
     * @param string $level   NOTICE | WARNING | ERROR | DEBUG
     *
     * @return bool
     * @since 1.9.0
     * @todo This is complicated and confusing.  Using int constants for debug
     * levels will make things easier.
     */
    public function log($message, $level = 'NOTICE') {
    	// only log when debugging is enabled
    	if ($this->level) {
    		// debug to screen or log?
    		$to_screen = !($this->level == 'NOTICE');
    
    		switch ($level) {
    			case 'ERROR':
    				// always report
    				$this->dump("$level: $message", $to_screen, $level);
    				break;
    			case 'WARNING':
    			case 'DEBUG':
    				// report except if user wants only errors
    				if ($this->level != 'ERROR') {
    					$this->dump("$level: $message", $to_screen, $level);
    				}
    				break;
    			case 'NOTICE':
    			default:
    				// only report when lowest level is desired
    				if ($this->level == 'NOTICE') {
    					$this->dump("$level: $message", FALSE, $level);
    				}
    				break;
    		}
            
            // The message was logged
    		return TRUE;
    	}
    
        // Logging is disabled
    	return FALSE;
    }
    
    
    /**
     * Logs or displays $value.
     *
     * If $to_screen is true, $value is displayed to screen.  Else,
     * it is handled by PHP's {@link error_log()} function.
     *
     * A {@elgg_plugin_hook debug log} is called.  If a handler returns
     * false, it will stop the default logging method.
     *
     * @param mixed  $value     The value
     * @param bool   $to_screen Display to screen?
     * @param string $level     The debug level
     *
     * @since 1.9.0
     * @access private
     */
    public function dump($value, $to_screen = TRUE, $level = 'NOTICE') {
        global $CONFIG;
    
    	// plugin can return false to stop the default logging method
    	$params = array(
    		'level' => $level,
    		'msg' => $value,
    		'to_screen' => $to_screen,
    	);
        
    	if (!$this->hooks->trigger('debug', 'log', $params, true)) {
    		return;
    	}
    
    	// Do not want to write to screen before page creation has started.
    	// This is not fool-proof but probably fixes 95% of the cases when logging
    	// results in data sent to the browser before the page is begun.
    	if (!isset($CONFIG->pagesetupdone)) {
    		$to_screen = FALSE;
    	}
    
    	if ($to_screen == TRUE) {
    		echo '<pre>';
    		print_r($value);
    		echo '</pre>';
    	} else {
    		error_log(print_r($value, TRUE));
    	}
    }
    
    
    function setLevel($level) {
        $this->level = $level;
    }
}