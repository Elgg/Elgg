<?php

	/**
	 * Elgg library
	 * Contains important functionality core to Elgg
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Getting directories and moving the browser
	 */

	/**
	 * Adds messages to the session so they'll be carried over, and forwards the browser.
	 * Returns false if headers have already been sent and the browser cannot be moved.
	 *
	 * @param string $location URL to forward to browser to
	 * @return nothing|false
	 */

		function forward($location = "") {
			
			if (!headers_sent()) {
				 $_SESSION['messages'] = system_messages();
				 if (substr_count($location, 'http://') == 0) {
				 	global $CONFIG;
				 	$location = $CONFIG->url . $location;
				 }
				 header("Location: {$location}");
				 exit;
			}
			return false;
			
		}
		
	/**
	 * Templating
	 */
		
	/**
	 * Handles templating views
	 *
	 * @param string $view The name and location of the view to use
	 * @param array $vars Any variables that the view requires, passed as an array
	 * @param string $viewtype Optionally, the type of view that we're using (most commonly 'default')
	 * @param boolean $debug If set to true, the viewer will complain if it can't find a view
	 * @return string The HTML content
	 */
		function elgg_view($view, $vars = "", $viewtype = "", $debug = false) {
		
		    global $CONFIG, $strings;
		    
		    static $usercache;
		    if (!is_array($usercache)) {
		        $usercache = array();
		    }
		
		    if (empty($vars)) {
		        $vars = array();
		    }
		
		    // Load session and configuration variables
		    if (is_array($_SESSION)) {
		        $vars = array_merge($vars, $_SESSION);
		    }
			if (!empty($CONFIG))
		    	$vars = array_merge($vars, get_object_vars($CONFIG));
		    if (is_callable('page_owner')) {
		        $vars['page_owner'] = page_owner();
		    } else {
		    	$vars['page_owner'] = -1;
		    }
		    if ($vars['page_owner'] != -1) {
		        if (!isset($usercache[$vars['page_owner']])) {
		    	       $vars['page_owner_user'] = get_user($vars['page_owner']);
		    	       $usercache[$vars['page_owner']] = $vars['page_owner_user'];
		        } else {
		            $vars['page_owner_user'] = $usercache[$vars['page_owner']];
		        }
		    }
		     
		    if (empty($_SESSION['view'])) {
		        $_SESSION['view'] = "default";
		    }
		    if (empty($viewtype) && is_callable('get_input'))
		        $viewtype = get_input('view');
		    if (empty($viewtype)) {
		        $viewtype = $_SESSION['view'];
		    }
		
		    if (isset($CONFIG->views->extensions[$view])) {
		    	$viewlist = $CONFIG->views->extensions[$view];
		    } else {
		    	$viewlist = array(500 => $view);
		    }
		    
		    ob_start();
		    foreach($viewlist as $priority => $view) {
		    
		    	if (!isset($CONFIG->views->locations[$view])) {
		    		if (!isset($CONFIG->viewpath)) {
						$location = dirname(dirname(dirname(__FILE__))) . "views/";		    			
		    		} else {
		    			$location = $CONFIG->viewpath;
		    		}
		    	} else {
		    		$location = $CONFIG->views->locations[$view];
		    	}
			    if (!@include($location . "{$viewtype}/{$view}.php")) {
			        $success = false;
			        if ($viewtype != "default") {
			            if (@include($location . "default/{$view}.php")) {
			                $success = true;
			            }
			        }
			        if (!$success && $CONFIG->debug == true) {
			            echo " [This view ({$view}) does not exist] ";
			        }
			    }
		    
		    }
		    $content = ob_get_clean();
		
		    return $content;
		
		}
		
	/**
	 * Extends a view by adding other views to be displayed at the same time.
	 *
	 * @param string $view The view to add to.
	 * @param string $view_name The name of the view to extend
	 * @param int $priority The priority, from 0 to 1000, to add at (lowest numbers will be displayed first)
	 */
		function extend_view($view, $view_name, $priority = 501) {
			
			global $CONFIG;
			if (!isset($CONFIG->views)) {
				$CONFIG->views = new stdClass;
			}
			if (!isset($CONFIG->views->extensions)) {
				$CONFIG->views->extensions = array();
			}
			if (!isset($CONFIG->views->extensions[$view])) {
				$CONFIG->views->extensions[$view][500] = "{$view}";
			}
			while(isset($CONFIG->views->extensions[$view][$priority])) {
				$priority++;
			}
			$CONFIG->views->extensions[$view][$priority] = "{$view_name}";
			ksort($CONFIG->views->extensions[$view]);
			
		}
		
	/**
	 * Set an alternative base location for a view (as opposed to the default of $CONFIG->viewpath)
	 *
	 * @param string $view The name of the view
	 * @param string $location The base location path
	 */
		function set_view_location($view, $location) {
			
			global $CONFIG;
			if (!isset($CONFIG->views)) {
				$CONFIG->views = new stdClass;
			}
			if (!isset($CONFIG->views->locations)) {
				$CONFIG->views->locations = array($view => $location);
			} else {
				$CONFIG->views->locations[$view] = $location;
			}
			
		}
		
	/**
	 * Returns a representation of a full 'page' (which might be an HTML page, RSS file, etc, depending on the current view)
	 *
	 * @param unknown_type $title
	 * @param unknown_type $body
	 * @return unknown
	 */
		
		function page_draw($title, $body) {
			
			return elgg_view('pageshell', array(
												'title' => $title,
												'body' => $body,
												'messages' => system_messages(null,"")
											  )
										);
			
		}

	/**
	 * Library loading and handling
	 */

	/**
	 * Recursive function designed to load library files on start
	 * (NB: this does not include plugins.)
	 *
	 * @param string $directory Full path to the directory to start with
	 * @param string $file_exceptions A list of filenames (with no paths) you don't ever want to include
	 * @param string $file_list A list of files that you know already you want to include
	 * @return array Array of full filenames
	 */
		function get_library_files($directory, $file_exceptions = array(), $file_list = array()) {
			
			if (is_file($directory) && !in_array($directory,$file_exceptions)) {
				$file_list[] = $directory;
			} else if ($handle = opendir($directory)) {
				while ($file = readdir($handle)) {
					if (!in_array($file,$file_exceptions)) {
						$file_list = get_library_files($directory . "/" . $file, $file_exceptions, $file_list);
					}
				}
			}
			
			return $file_list;
			
		}
		
	/**
	 * Ensures that the installation has all the correct files, that PHP is configured correctly, and so on.
	 * Leaves appropriate messages in the error register if not.
	 *
	 * @return true|false True if everything is ok (or Elgg is fit enough to run); false if not.
	 */
		function sanitised() {
			
			$sanitised = true;
			
			if (!file_exists(dirname(dirname(__FILE__)) . "/settings.php")) {
				register_error(elgg_view("messages/sanitisation/settings"));
				$sanitised = false;
			}

			if (!file_exists(dirname(dirname(dirname(__FILE__))) . "/.htaccess")) {
				if (!copy(dirname(dirname(dirname(__FILE__))) . "/htaccess_dist", dirname(dirname(dirname(__FILE__))) . "/.htaccess")) {
					register_error(elgg_view("messages/sanitisation/htaccess"));
					$sanitised = false;
				}
			}
				
			return $sanitised;
			
		}
		
	/**
	 * Registers
	 */
		
	/**
	 * Message register handling
	 * If no parameter is given, the function returns the array of messages so far and empties it.
	 * Otherwise, any message or array of messages is added.
	 *
	 * @param string|array $message Optionally, a single message or array of messages to add
	 * @param string $register By default, "errors". This allows for different types of messages, eg errors.
	 * @return true|false|array Either the array of messages, or a response regarding whether the message addition was successful
	 */
		
		function system_messages($message = null, $register = "messages", $count = false) {
			
			static $messages;
			if (!isset($messages)) {
				$messages = array();
			}
			if (!isset($messages[$register]) && !empty($register)) {
				$messages[$register] = array();
			}
			if (!$count) {
				if (!empty($message) && is_array($message)) {
					$messages[$register] = array_merge($messages[$register], $message);
					return true;
				} else if (!empty($message) && is_string($message)) {
					$messages[$register][] = $message;
					return true;
				} else if (!is_string($message) && !is_array($message)) {
					if (!empty($register)) {
						$returnarray = $messages[$register];
						$messages[$register] = array();
					} else {
						$returnarray = $messages;
						$messages = array();
					}
					return $returnarray;
				}
			} else {
				if (!empty($register)) {
					return sizeof($messages[$register]);
				} else {
					$count = 0;
					foreach($messages as $register => $submessages) {
						$count += sizeof($submessages);
					}
					return $count;
				}
			}
			return false;
			
		}
		
	/**
	 * Counts the number of messages, either globally or in a particular register
	 *
	 * @param string $register Optionally, the register
	 * @return integer The number of messages
	 */
		function count_messages($register = "") {
			return system_messages(null,$register,true);
		}
		
	/**
	 * An alias for system_messages($message) to handle standard user information messages
	 *
	 * @param string|array $message Message or messages to add
	 * @return true|false Success response
	 */
		function system_message($message) {
			return system_messages($message, "messages");
		}
		
	/**
	 * An alias for system_messages($message) to handle error messages
	 *
	 * @param string|array $message Error or errors to add
	 * @return true|false Success response
	 */
		function register_error($error) {
			return system_messages($error, "errors");
		}

	/**
	 * Event register
	 * Adds functions to the register for a particular event, but also calls all functions registered to an event when required
	 *
	 * Event handler functions must be of the form:
	 * 
	 * 		event_handler_function($event, $object_type, $object);
	 * 
	 * And must return true or false depending on success.  A false will halt the event in its tracks and no more functions will be called.
	 * 
	 * You can then simply register them using the following function. Optionally, this can be called with a priority nominally from 0 to 1000, where functions with lower priority values are called first (note that priorities CANNOT be negative):
	 * 
	 * 		register_event_handler($event, $object_type, $function_name [, $priority = 500]);
	 * 
	 * Note that you can also use 'all' in place of both the event and object type. 
	 * 
	 * To trigger an event properly, you should always use:
	 * 
	 * 		trigger_event($event, $object_type [, $object]);
	 * 
	 * Where $object is optional, and represents the $object_type the event concerns. This will return true if successful, or false if it fails. 
	 * 
	 * @param string $event The type of event (eg 'init', 'update', 'delete')
	 * @param string $object_type The type of object (eg 'system', 'blog', 'user')
	 * @param string $function The name of the function that will handle the event
	 * @param int $priority A priority to add new event handlers at. Lower numbers will be called first (default 500)
	 * @param boolean $call Set to true to call the event rather than add to it (default false)
	 * @param mixed $object Optionally, the object the event is being performed on (eg a user)
	 * @return true|false Depending on success
	 */
		
		function events($event = "", $object_type = "", $function = "", $priority = 500, $call = false, $object = null) {
			
			static $events;
			
			if (!isset($events)) {
				$events = array();
			} else if (!isset($events[$event]) && !empty($event)) {
				$events[$event] = array();
			} else if (!isset($events[$event][$object_type]) && !empty($event) && !empty($object_type)) {
				$events[$event][$object_type] = array();
			}
			
			if (!$call) {
			
				if (!empty($event) && !empty($object_type) && is_callable($function)) {
					$priority = (int) $priority;
					if ($priority < 0) $priority = 0;
					while (isset($events[$event][$object_type][$priority])) {
						$priority++;
					}
					$events[$event][$object_type][$priority] = $function;
					ksort($events[$event][$object_type]);
					return true;
				} else {
					return false;
				}
			
			} else {
			
				if (!empty($events[$event][$object_type]) && is_array($events[$event][$object_type])) {
					foreach($events[$event][$object_type] as $eventfunction) {
						if (!$eventfunction($event, $object_type, $object)) {
							return false;
						}
					}
					return true;
				}
			
			}
			
			return false;
			
		}
		
	/**
	 * Alias function for events, that registers a function to a particular kind of event
	 *
	 * @param string $event The event type
	 * @param string $object_type The object type
	 * @param string $function The function name
	 * @return true|false Depending on success 
	 */
		
		function register_event_handler($event, $object_type, $function, $priority = 500) {
			return events($event, $object_type, $function, $priority);
		}
		
	/**
	 * Alias function for events, that triggers a particular kind of event
	 *
	 * @param string $event The event type
	 * @param string $object_type The object type
	 * @param string $function The function name
	 * @return true|false Depending on success 
	 */
		function trigger_event($event, $object_type, $object = null) {
			if (events($event, $object_type, "", null, true, $object)
				&& events('all', $object_type, "", null, true, $object)
				&& events($event, 'all', "", null, true, $object)
				&& events('all', 'all', "", null, true, $object)) {
					return true;
				}
			return false;
		}
		
	/**
	 * Error handling
	 */
		
	/**
	 * PHP Error handler function.
	 * This function acts as a wrapper to catch and report PHP error messages.
	 * 
	 * @see http://www.php.net/set-error-handler
	 * @param int $errno The level of the error raised
	 * @param string $errmsg The error message
	 * @param string $filename The filename the error was raised in
	 * @param int $linenum The line number the error was raised at
	 * @param array $vars An array that points to the active symbol table at the point that the error occurred
	 */
		function __elgg_php_error_handler($errno, $errmsg, $filename, $linenum, $vars)
		{
			$error = date("Y-m-d H:i:s (T)") . ": \"" . $errmsg . "\" in file " . $filename . " (line " . $linenum . ")";
			
			switch ($errno) {
				case E_USER_ERROR:
						error_log("ERROR: " . $error);
						register_error("ERROR: " . $error);
						
						// Since this is a fatal error, we want to stop any further execution but do so gracefully.
						throw new Exception("ERROR: " . $error); 
					break;

				case E_WARNING :
				case E_USER_WARNING : 
						error_log("WARNING: " . $error);
						// register_error("WARNING: " . $error);
					break;

				default:
					error_log("DEBUG: " . $error); 
					// register_error("DEBUG: " . $error);
			}
			echo "$error <br />";
			return true;
		}
		
	/**
	 * Custom exception handler.
	 * This function catches any thrown exceptions and handles them appropriately.
	 *
	 * @see http://www.php.net/set-exception-handler
	 * @param Exception $exception The exception being handled
	 */
		
		function __elgg_php_exception_handler($exception) {
			
			$body = elgg_view("messages/exceptions/exception",array('object' => $exception));
			echo page_draw("We've encountered a problem.", $body);
			
		}

?>