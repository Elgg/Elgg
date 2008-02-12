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

		function forward($location) {
			
			if (!headers_sent()) {
				 $_SESSION['messages'] = system_messages();
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
		
		    ob_start();
		    if (!@include($CONFIG->viewpath . "views/{$viewtype}/{$view}.php")) {
		        $success = false;
		        if ($viewtype != "default") {
		            if (@include($CONFIG->viewpath . "views/default/{$view}.php")) {
		                $success = true;
		            }
		        }
		        if (!$success && $debug == true) {
		            echo " [This view ({$view}) does not exist] ";
		        }
		    }
		    $content = ob_get_clean();
		
		    return $content;
		
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
			
			if (is_file($directory)) {
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
	 * Counts the number of messages, either globally or in a particular register
	 *
	 * @param string $register Optionally, the register
	 * @return integer The number of messages
	 */
		function count_messages($register = "") {
			return system_messages(null,$register,true);
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
	 * You can then simply register them using the alias function:
	 * 
	 * 		register_event_handler('event', 'object_type', 'function_name');
	 * 
	 * @param string $event The type of event (eg 'init', 'update', 'delete')
	 * @param string $object_type The type of object (eg 'system', 'blog', 'user')
	 * @param string $function The name of the function that will handle the event
	 * @param boolean $call Set to true to call the event rather than add to it (default false)
	 * @param mixed $object Optionally, the object the event is being performed on (eg a user)
	 * @return true|false Depending on success
	 */
		
		function events($event = "", $object_type = "", $function = "", $call = false, $object = null) {
			
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
					$events[$event][$object_type][] = $function;
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
					if ($event != 'all' && !empty($events['all'][$object_type]) && is_array($events['all'][$object_type]))
					foreach($events['all'][$object_type] as $eventfunction) {
						if (!$eventfunction('all', $object_type, $object)) {
							return false;
						}
					}
					if ($object_type != 'all' && !empty($events[$event]['all']) && is_array($events[$event]['all']))
					foreach($events[$event]['all'] as $eventfunction) {
						if (!$eventfunction($event, 'all', $object)) {
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
		
		function register_event_handler($event, $object_type, $function) {
			return events($event, $object_type, $function);
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
			return events($event, $object_type, "", true, $object);
		}
		
?>