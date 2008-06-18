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
		 * Return the current page URL.
		 */
		function current_page_url()
		{
			global $CONFIG;
			
			$url = parse_url($CONFIG->wwwroot);
		
			$page = $url['scheme'] . "://";
	
			// user/pass
			if ($url['user']) $page .= $url['user'];
			if ($url['pass']) $page .= ":".$url['pass'];
			if (($url['user']) || $url['pass']) $page .="@";
			
			$page .= $url['host'];
			$page = trim($page, "/"); $page.="/";
			
			$page .= $_SERVER['REQUEST_URI'];
			
			return $page;
		}
		
	/**
	 * Templating and visual functionality
	 */
		
	/**
	 * Handles templating views
	 *
	 * @see set_template_handler
	 * 
	 * @param string $view The name and location of the view to use
	 * @param array $vars Any variables that the view requires, passed as an array
	 * @param string $viewtype Optionally, the type of view that we're using (most commonly 'default')
	 * @param boolean $bypass If set to true, elgg_view will bypass any specified alternative template handler; by default, it will hand off to this if requested (see set_template_handler)
	 * @param boolean $debug If set to true, the viewer will complain if it can't find a view
	 * @return string The HTML content
	 */
		function elgg_view($view, $vars = "", $viewtype = "", $bypass = true, $debug = false) {

		    global $CONFIG;
		    static $usercache;
		    
		    if (!is_array($usercache)) {
		        $usercache = array();
		    }
		
		    if (empty($vars)) {
		        $vars = array();
		    }
		
		// Load session and configuration variables into $vars
		    if (isset($_SESSION) && is_array($_SESSION) ) {
		        $vars = array_merge($vars, $_SESSION);
		    }
		    $vars['config'] = array();
			if (!empty($CONFIG))
		    	$vars['config'] = $CONFIG;
		    	
			$vars['url'] = $CONFIG->url;
		    	
		// Load page owner variables into $vars
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
		    if (!isset($vars['js'])) {
		    	$vars['js'] = "";
		    }
		     
		// If it's been requested, pass off to a template handler instead
		    if ($bypass == false && isset($CONFIG->template_handler) && !empty($CONFIG->template_handler)) {
		    	$template_handler = $CONFIG->template_handler;
		    	return $template_handler($view, $vars);
		    }
		    
		// If we haven't been asked for a specific view, assume default
		    if (empty($_SESSION['view'])) {
		        $_SESSION['view'] = "default";
		        
		        // If we have a config default view for this site then use that instead of 'default'
		        if ((is_installed()) && (!empty($CONFIG->view)))
		        	$_SESSION['view'] = $CONFIG->view;
		    }
		    if (empty($viewtype) && is_callable('get_input'))
		        $viewtype = get_input('view');
		    if (empty($viewtype)) {
		        $viewtype = $_SESSION['view'];
		    }
		
		// Set up any extensions to the requested view
		    if (isset($CONFIG->views->extensions[$view])) {
		    	$viewlist = $CONFIG->views->extensions[$view];
		    } else {
		    	$viewlist = array(500 => $view);
		    }
		    
		// Start the output buffer, find the requested view file, and execute it
		    ob_start();
		    foreach($viewlist as $priority => $view) {
		    
		    	if (!isset($CONFIG->views->locations[$view])) {
		    		if (!isset($CONFIG->viewpath)) {
						$location = dirname(dirname(dirname(__FILE__))) . "/views/";		    			
		    		} else {
		    			$location = $CONFIG->viewpath;
		    		}
		    	} else {
		    		$location = $CONFIG->views->locations[$view];
		    	}
			    if (file_exists($location . "{$viewtype}/{$view}.php") && !@include($location . "{$viewtype}/{$view}.php")) {
			        $success = false;
			        
			        if ($viewtype != "default") {
			            if (@include($location . "default/{$view}.php")) {
			                $success = true;
			            }
			        }
			        if (!$success && isset($CONFIG->debug) && $CONFIG->debug == true) {
			            error_log(" [This view ({$view}) does not exist] ");
			        }
			    } else if ($CONFIG->debug == true && !file_exists($location . "{$viewtype}/{$view}.php")) {
			    	error_log($location . "{$viewtype}/{$view}.php");
			    	error_log(" [This view ({$view}) does not exist] ");
			    }
		    
		    }

		// Save the output buffer into the $content variable
		    $content = ob_get_clean();

		// Return $content
		    return $content;
		
		}
		
	/**
	 * When given an entity, views it intelligently.
	 * 
	 * Expects a view to exist called entity-type/subtype, or for the entity to have a parameter
	 * 'view' which lists a different view to display.  In both cases, elgg_view will be called with
	 * array('entity' => $entity) as its parameters, and therefore this is what the view should expect
	 * to receive. 
	 *
	 * @param ElggEntity $entity The entity to display
	 * @param string $viewtype Optionally, the type of view that we're using (most commonly 'default')
	 * @param boolean $full Determines whether or not to display the full version of an object, or a smaller version for use in aggregators etc
	 * @param boolean $debug If set to true, elgg_view will bypass any specified alternative template handler; by default, it will hand off to this if requested (see set_template_handler)
	 * @param boolean $debug If set to true, the viewer will complain if it can't find a view
	 * @return string HTML (etc) to display
	 */
		function elgg_view_entity(ElggEntity $entity, $viewtype = "", $full = false, $bypass = true, $debug = false) {
			
			$view = $entity->view;
			if (is_string($view)) {
				return elgg_view($view,array('entity' => $entity), $viewtype, $bypass, $debug);
			}
			
			$classes = array(
								'ElggUser' => 'user',
								'ElggObject' => 'object',
								'ElggSite' => 'site',
								'ElggCollection' => 'collection'
							);
			
			$entity_class = get_class($entity);
			if (isset($classes[$entity_class])) {
				$entity_type = $classes[$entity_class];
			} else {
				foreach($classes as $class => $type) {
					if (is_subclass_of($entity,$class)) {
						$entity_type = $class;
						break;
					}
				}
			}
			if (!isset($entity_class)) return false;
			
			$subtype = $entity->getSubtype();
			if (empty($subtype)) { $subtype = $entity_type; }

			return elgg_view("{$entity_type}/{$subtype}",array(
																'entity' => $entity,
																'full' => $full
																), $viewtype, $bypass, $debug);
			
		}
		
	/**
	 * Displays an internal layout for the use of a plugin canvas.
	 * Takes a variable number of parameters, which are made available 
	 * in the views as $vars['area1'] .. $vars['areaN'].
	 *
	 * @param string $layout The name of the views in canvas/layouts/.
	 * @return string The layout
	 */
		function elgg_view_layout($layout) {
			
			$arg = 1;
			$param_array = array();
			while ($arg < func_num_args()) {
				$param_array['area' . $arg] = func_get_arg($arg);
				$arg++;		
			}
			return elgg_view("canvas/layouts/{$layout}",$param_array);
				
		}
		
	/**
	 * Sets an alternative function to handle templates, which will be passed to by elgg_view.
	 * This function must take the $view and $vars parameters from elgg_view:
	 * 
	 * 		function my_template_function(string $view, array $vars = array())
	 * 
	 * @see elgg_view
	 *
	 * @param string $function_name The name of the function to pass to.
	 * @return true|false
	 */
		function set_template_handler($function_name) {
			global $CONFIG;
			if (!empty($function_name) && is_callable($function_name)) {
				$CONFIG->template_handler = $function_name;			
				return true;
			}
			return false;
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
	 * Auto-registers views from a particular starting location
	 *
	 * @param string $view_base The base of the view name
	 * @param string $folder The folder to begin looking in
	 * @param string $base_location_path The base views directory to use with set_view_location
	 */		
		function autoregister_views($view_base, $folder, $base_location_path) {
			
			if (!isset($i)) $i = 0;
			if ($handle = opendir($folder)) {
				while ($view = readdir($handle)) {
					if (!in_array($view,array('.','..','.svn','CVS')) && !is_dir($folder . "/" . $view)) {
						if (substr_count($view,".php") > 0) {
							if (!empty($view_base)) { $view_base_new = $view_base . "/"; } else { $view_base_new = ""; }
							set_view_location($view_base_new . str_replace(".php","",$view), $base_location_path);
						}
					} else if (!in_array($view,array('.','..','.svn','CVS')) && is_dir($folder . "/" . $view)) {
						if (!empty($view_base)) { $view_base_new = $view_base . "/"; } else { $view_base_new = ""; }
						autoregister_views($view_base_new . $view, $folder . "/" . $view, $base_location_path);
					}
				}
			}
			
		}
		
	/**
	 * Returns a representation of a full 'page' (which might be an HTML page, RSS file, etc, depending on the current view)
	 *
	 * @param unknown_type $title
	 * @param unknown_type $body
	 * @return unknown
	 */
		function page_draw($title, $body, $sidebar = "") {

			echo elgg_view('pageshells/pageshell', array(
												'title' => $title,
												'body' => $body,
												'sidebar' => $sidebar,
												'sysmessages' => system_messages(null,"")
											  )
										);
			
		}
		
	/**
	 * Displays a UNIX timestamp in a friendly way (eg "less than a minute ago")
	 *
	 * @param int $time A UNIX epoch timestamp
	 * @return string The friendly time
	 */
		function friendly_time(int $time) {
			
			$diff = time() - $time;
			if ($diff < 60) {
				return elgg_echo("friendlytime:justnow");
			} else if ($diff < 3600) {
				$diff = round($diff / 60);
				if ($diff == 0) $diff = 1;
				if ($diff > 1)
					return sprintf(elgg_echo("friendlytime:minutes"),$diff);
				return sprintf(elgg_echo("friendlytime:minutes:singular"),$diff);
			} else if ($diff < 86400) {
				$diff = round($diff / 3600);
				if ($diff == 0) $diff = 1;
				if ($diff > 1)
					return sprintf(elgg_echo("friendlytime:hours"),$diff);
				return sprintf(elgg_echo("friendlytime:hours:singular"),$diff);
			} else {
				$diff = round($diff / 86400);
				if ($diff == 0) $diff = 1;
				if ($diff > 1)
					return sprintf(elgg_echo("friendlytime:days"),$diff);
				return sprintf(elgg_echo("friendlytime:days:singular"),$diff);
			}
			
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
			$extensions_allowed = array('.php'); 	
			if (is_file($directory) && !in_array($directory,$file_exceptions)) {
				$file_list[] = $directory;
			} else if ($handle = opendir($directory)) {
				while ($file = readdir($handle)) {
					if (in_array(strrchr($file, '.'), $extensions_allowed) && !in_array($file,$file_exceptions)) {
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
				// See if we are being asked to save the file
				$save_vars = get_input('db_install_vars');
				$result = "";
				if ($save_vars)
				{
					$result = create_settings($save_vars, dirname(dirname(__FILE__)) . "/settings.example.php");
					
					if (file_put_contents(dirname(dirname(__FILE__)) . "/settings.php", $result))
						$result = ""; // blank result to stop it being displayed in textarea
					
				}
				
				// Recheck to see if the file is still missing
				if (!file_exists(dirname(dirname(__FILE__)) . "/settings.php")) {
					register_error(elgg_view("messages/sanitisation/settings", array('settings.php' => $result)));
					$sanitised = false;
				}
			}

			if (!file_exists(dirname(dirname(dirname(__FILE__))) . "/.htaccess")) {
				if (!@copy(dirname(dirname(dirname(__FILE__))) . "/htaccess_dist", dirname(dirname(dirname(__FILE__))) . "/.htaccess")) {
					register_error(elgg_view("messages/sanitisation/htaccess", array('.htaccess' => file_get_contents(dirname(dirname(dirname(__FILE__))) . "/htaccess_dist"))));
					$sanitised = false;
				}
			}
				
			return $sanitised;
			
		}
		
	/**
	 * Registers
	 */
		
	/**
	 * Adds an array with a name to a given generic array register.
	 * For example, these are used for menus.
	 *
	 * @param string $register_name The name of the top-level register
	 * @param string $subregister_name The name of the subregister
	 * @param mixed $subregister_value The value of the subregister
	 * @param array $children_array Optionally, an array of children
	 * @return true|false Depending on success
	 */
		function add_to_register($register_name, $subregister_name, $subregister_value, $children_array = array()) {
			
			global $CONFIG;
			
			if (empty($register_name) || empty($subregister_name) || empty($children_array))
				return false;
			
			if (!isset($CONFIG->registers))
				$CONFIG->registers = array();
				
			if (!isset($CONFIG->registers[$register_name]))
				$CONFIG->registers[$register_name]  = array();
			
			$subregister = new stdClass;
			$subregister->name = $subregister_name;
			$subregister->value = $subregister_value;
				
			if (is_array($children_array)) {	
				$subregister->children = $children_array;
			}
			
			$CONFIG->registers[$register_name][$subregister_name] = $subregister;
			return true;
				
		}
		
	/**
	 * Returns a register object
	 *
	 * @param string $register_name The name of the register
	 * @param mixed $register_value The value of the register
	 * @param array $children_array Optionally, an array of children
	 * @return false|stdClass Depending on success
	 */
		function make_register_object($register_name, $register_value, $children_array = array()) {
			
			if (empty($register_name) || empty($register_value))
				return false;
			
			$register = new stdClass;
			$register->name = $register_name;
			$register->value = $register_value;
			$register->children = $children_array;
			
			return $register;
			
		}
		
	/**
	 * If it exists, returns a particular register as an array
	 *
	 * @param string $register_name The name of the register
	 * @return array|false Depending on success
	 */
		function get_register($register_name) {
			
			global $CONFIG;
			
			if (isset($CONFIG->registers[$register_name]))
				return $CONFIG->registers[$register_name];
			
			return false;
				
		}
		
	/**
	 * Adds an item to the menu register
	 *
	 * @param string $menu_name The name of the top-level menu
	 * @param string $menu_url The URL of the page
	 * @param array $menu_children Optionally, an array of submenu items
	 * @return true|false Depending on success
	 */
		function add_menu($menu_name, $menu_url, $menu_children = array(), $context = "") {
			global $CONFIG;
			if (!isset($CONFIG->menucontexts)) {
				$CONFIG->menucontexts = array();
			}
			if (empty($menu)) {
				get_plugin_name();
			}
			if (empty($context)) {
				$context = get_plugin_name();
			}
			$CONFIG->menucontexts[] = $context;
			return add_to_register('menu',$menu_name,$menu_url, $menu_children);
		}
		
	/**
	 * Returns a menu item for use in the children section of add_menu()
	 *
	 * @param string $menu_name The name of the menu item
	 * @param string $menu_url Its URL
	 * @return stdClass|false Depending on success
	 */
		function menu_item($menu_name, $menu_url) {
			return make_register_object($menu_name, $menu_url);
		}
		
		
	/**
	 * Message register handling
	 * If no parameter is given, the function returns the array of messages so far and empties it.
	 * Otherwise, any message or array of messages is added.
	 *
	 * @param string|array $message Optionally, a single message or array of messages to add
	 * @param string $register By default, "errors". This allows for different types of messages, eg errors.
	 * @return true|false|array Either the array of messages, or a response regarding whether the message addition was successful
	 */
		
		function system_messages($message = "", $register = "messages", $count = false) {
			
			if (!isset($_SESSION['msg'])) {
				$_SESSION['msg'] = array();
			}
			if (!isset($_SESSION['msg'][$register]) && !empty($register)) {
				$_SESSION['msg'][$register] = array();
			}
			if (!$count) {
				if (!empty($message) && is_array($message)) {
					$_SESSION['msg'][$register] = array_merge($_SESSION['msg'][$register], $message);
					var_export($_SESSION['msg']); exit;
					return true;
				} else if (!empty($message) && is_string($message)) {
					$_SESSION['msg'][$register][] = $message;
					return true;
				} else if (is_null($message)) {
					if ($register != "") {
						$returnarray = $_SESSION['msg'][$register];
						$_SESSION['msg'][$register] = array();
					} else {
						$returnarray = $_SESSION['msg'];
						$_SESSION['msg'] = array();
					}
					return $returnarray;
				}
			} else {
				if (!empty($register)) {
					return sizeof($_SESSION['msg'][$register]);
				} else {
					$count = 0;
					foreach($_SESSION['msg'] as $register => $submessages) {
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
	 * 		register_elgg_event_handler($event, $object_type, $function_name [, $priority = 500]);
	 * 
	 * Note that you can also use 'all' in place of both the event and object type. 
	 * 
	 * To trigger an event properly, you should always use:
	 * 
	 * 		trigger_elgg_event($event, $object_type [, $object]);
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
			
			global $CONFIG;
			
			if (!isset($CONFIG->events)) {
				$CONFIG->events = array();
			} else if (!isset($CONFIG->events[$event]) && !empty($event)) {
				$CONFIG->events[$event] = array();
			} else if (!isset($CONFIG->events[$event][$object_type]) && !empty($event) && !empty($object_type)) {
				$CONFIG->events[$event][$object_type] = array();
			}
			
			if (!$call) {
			
				if (!empty($event) && !empty($object_type) && is_callable($function)) {
					$priority = (int) $priority;
					if ($priority < 0) $priority = 0;
					while (isset($CONFIG->events[$event][$object_type][$priority])) {
						$priority++;
					}
					$CONFIG->events[$event][$object_type][$priority] = $function;
					ksort($CONFIG->events[$event][$object_type]);
					return true;
				} else {
					return false;
				}
			
			} else {
				
				$return = true;
				if (!empty($CONFIG->events[$event][$object_type]) && is_array($CONFIG->events[$event][$object_type])) {
					foreach($CONFIG->events[$event][$object_type] as $eventfunction) {
						if ($eventfunction($event, $object_type, $object) === false) {
							$return = false;
						}
					}
				}
				if (!empty($CONFIG->events['all'][$object_type]) && is_array($CONFIG->events['all'][$object_type])) {					
					foreach($CONFIG->events['all'][$object_type] as $eventfunction) {
						if ($eventfunction($event, $object_type, $object) === false) {
							$return = false;
						}
					}
				}
				if (!empty($CONFIG->events[$event]['all']) && is_array($CONFIG->events[$event]['all'])) {						
					foreach($CONFIG->events[$event]['all'] as $eventfunction) {
						if ($eventfunction($event, $object_type, $object) === false) {
							$return = false;
						}
					}
				}
				if (!empty($CONFIG->events['all']['all']) && is_array($CONFIG->events['all']['all'])) {					
					foreach($CONFIG->events['all']['all'] as $eventfunction) {
						if ($eventfunction($event, $object_type, $object) === false) {
							$return = false;
						}
					}
				}
				return $return;
			
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
		function register_elgg_event_handler($event, $object_type, $function, $priority = 500) {
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
		function trigger_elgg_event($event, $object_type, $object = null) {
			$return = true;
			$return1 = events($event, $object_type, "", null, true, $object);
			if (!is_null($return1)) $return = $return1;
			return $return;
		}
		
	/**
	 * Register a function to a plugin hook for a particular entity type, with a given priority.
	 * 
	 * eg if you want the function "export_user" to be called when the hook "export" for "user" entities 
	 * is run, use:
	 * 
	 * 		register_plugin_hook("export", "user", "export_user");
	 * 
	 * "all" is a valid value for both $hook and $entity_type. "none" is a valid value for $entity_type.
	 *
	 * The export_user function would then be defined as:
	 * 
	 * 		function export_user($hook, $entity_type, $returnvalue, $params);
	 * 
	 * Where $returnvalue is the return value returned by the last function returned by the hook, and
	 * $params is an array containing a set of parameters (or nothing).
	 * 
	 * @param string $hook The name of the hook
	 * @param string $entity_type The name of the type of entity (eg "user", "object" etc)
	 * @param string $function The name of a valid function to be run
	 * @param string $priority The priority - 0 is first, 1000 last, default is 500
	 * @return true|false Depending on success
	 */
		function register_plugin_hook($hook, $entity_type, $function, $priority = 500) {
			global $CONFIG;
			
			if (!isset($CONFIG->hooks)) {
				$CONFIG->hooks = array();
			} else if (!isset($CONFIG->hooks[$hook]) && !empty($hook)) {
				$CONFIG->hooks[$hook] = array();
			} else if (!isset($CONFIG->hooks[$hook][$entity_type]) && !empty($entity_type)) {
				$CONFIG->hooks[$hook][$entity_type] = array();
			}
			
			if (!empty($hook) && !empty($entity_type) && is_callable($function)) {
				$priority = (int) $priority;
				if ($priority < 0) $priority = 0;
				while (isset($CONFIG->hooks[$hook][$entity_type][$priority])) {
					$priority++;
				}
				$CONFIG->hooks[$hook][$entity_type][$priority] = $function;
				ksort($CONFIG->hooks[$hook][$entity_type]);
				return true;
			} else {
				return false;
			}
			
		}
		
	/**
	 * Triggers a plugin hook, with various parameters as an array. For example, if you're 
	 *
	 * @see register_plugin_hook
	 * @param string $hook The name of the hook to trigger
	 * @param string $entity_type The name of the entity type to trigger it for (or "all", or "none")
	 * @param array $params Any parameters. It's good practice to name the keys, i.e. by using array('name' => 'value', 'name2' => 'value2')
	 * @param mixed $returnvalue An initial return value
	 * @return mixed|null The cumulative return value for the plugin hook functions
	 */
		function trigger_plugin_hook($hook, $entity_type, $params = null, $returnvalue = null) {
			global $CONFIG;
		
			//if (!isset($CONFIG->hooks) || !isset($CONFIG->hooks[$hook]) || !isset($CONFIG->hooks[$hook][$entity_type]))
			//	return $returnvalue;

			if (!empty($CONFIG->hooks[$hook][$entity_type]) && is_array($CONFIG->hooks[$hook][$entity_type])) {
				foreach($CONFIG->hooks[$hook][$entity_type] as $hookfunction) {
					$temp_return_value = $hookfunction($hook, $entity_type, $returnvalue, $params);
					if (!is_null($temp_return_value)) $returnvalue = $temp_return_value;
				}
			}
			
			//if (!isset($CONFIG->hooks['all'][$entity_type]))
			//	return $returnvalue;
			
			if (!empty($CONFIG->hooks['all'][$entity_type]) && is_array($CONFIG->hooks['all'][$entity_type])) {
				foreach($CONFIG->hooks['all'][$entity_type] as $hookfunction) {
					$temp_return_value = $hookfunction($hook, $entity_type, $returnvalue, $params);
					if (!is_null($temp_return_value)) $returnvalue = $temp_return_value;
				}
			}
			
			//if (!isset($CONFIG->hooks[$hook]['all']))
			//	return $returnvalue;
			
			if (!empty($CONFIG->hooks[$hook]['all']) && is_array($CONFIG->hooks[$hook]['all'])) {
				foreach($CONFIG->hooks[$hook]['all'] as $hookfunction) {
					$temp_return_value = $hookfunction($hook, $entity_type, $returnvalue, $params);
					if (!is_null($temp_return_value)) $returnvalue = $temp_return_value;
				}
			}
			
			//if (!isset($CONFIG->hooks['all']['all']))
			//	return $returnvalue;
			
			if (!empty($CONFIG->hooks['all']['all']) && is_array($CONFIG->hooks['all']['all'])) {
				foreach($CONFIG->hooks['all']['all'] as $hookfunction) {
					$temp_return_value = $hookfunction($hook, $entity_type, $returnvalue, $params);
					if (!is_null($temp_return_value)) $returnvalue = $temp_return_value;
				}
			}
				
			return $returnvalue;
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
						throw new Exception($error); 
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

			error_log("*** FATAL EXCEPTION *** : " . $exception);
			
			$body = elgg_view("messages/exceptions/exception",array('object' => $exception));
			echo page_draw(elgg_echo('exception:title'), $body);
			
		}
		
	/**
	 * Data lists
	 */
		
	/**
	 * Get the value of a particular piece of data in the datalist
	 *
	 * @param string $name The name of the datalist
	 * @return string|false Depending on success
	 */	
		function datalist_get($name) {
			
			global $CONFIG;
			
			// We need this, because sometimes datalists are received before the database is created
			if (!is_db_installed()) return false;
			
			$name = sanitise_string($name);
			if ($row = get_data_row("select value from {$CONFIG->dbprefix}datalists where name = '{$name}'")) {
				return $row->value;
			}
			return false;
			
		}
		
	/**
	 * Sets the value for a system-wide piece of data (overwriting a previous value if it exists)
	 *
	 * @param string $name The name of the datalist
	 * @param string $value The new value
	 * @return true
	 */
		function datalist_set($name, $value) {
			
			global $CONFIG;
			$name = sanitise_string($name);
			$value = sanitise_string($value);
			delete_data("delete from {$CONFIG->dbprefix}datalists where name = '{$name}'");
			insert_data("insert into {$CONFIG->dbprefix}datalists set name = '{$name}', value = '{$value}'");
			return true;
			
		}

	/**
	 * Runs a function once - not per page load, but per installation.
	 * If you like, you can also set the threshold for the function execution - i.e.,
	 * if the function was executed before or on $timelastupdatedcheck, this
	 * function will run it again.
	 *
	 * @param string $functionname The name of the function you want to run.
	 * @param int $timelastupdatedcheck Optionally, the UNIX epoch timestamp of the execution threshold
	 * @return true|false Depending on success.
	 */
		function run_function_once($functionname, $timelastupdatedcheck = 0) {
			if ($lastupdated = datalist_get($functionname)) {
				$lastupdated = (int) $lastupdated;
			} else {
				$lastupdated = 0;
			}
			if (is_callable($functionname) && $lastupdated <= $timelastupdatedcheck) {
				$functionname();
				datalist_set($functionname,time());
				return true;
			} else {
				return false;
			}
		}

		
		
	/**
	 * Privilege elevation and gatekeeper code
	 */

	
	/**
	 * Gatekeeper function which ensures that a we are being executed from
	 * a specified location.
	 * 
	 * To use, call this function with the function name (and optional file location) that it has to be called 
	 * from, it will either return true or false.
	 * 
	 * e.g.
	 * 
	 * function my_secure_function()
	 * {
	 * 		if (!call_gatekeeper("my_call_function"))
	 * 			return false;
	 * 
	 * 		... do secure stuff ...
	 * }
	 * 
	 * function my_call_function()
	 * {
	 * 		// will work
	 * 		my_secure_function();
	 * }
	 * 
	 * function bad_function()
	 * {
	 * 		// Will not work
	 * 		my_secure_function();
	 * }
	 * 
	 * @param mixed $function The function that this function must have in its call stack, 
	 * 		to test against a method pass an array containing a class and method name.
	 * @param string $file Optional file that the function must reside in.
	 */
	function call_gatekeeper($function, $file = "")
	{
		// Sanity check
		if (!$function)
			return false;
					
		// Check against call stack to see if this is being called from the correct location
		$callstack = debug_backtrace();		
		$stack_element = false;
		
		foreach ($callstack as $call)
		{
			if (is_array($function))
			{
				if (
					(strcmp($call['class'], $function[0]) == 0) &&
					(strcmp($call['function'], $function[1]) == 0)
				)
					$stack_element = $call;
			}
			else
			{
				if (strcmp($call['function'], $function) == 0)
					$stack_element = $call;
			}
		}

		if (!$stack_element)
			return false;

			
		// If file then check that this it is being called from this function
		if ($file)
		{
			$mirror = null;
			
			if (is_array($function))
				$mirror = new ReflectionMethod($function[0], $function[1]);
			else
				$mirror = new ReflectionFunction($function);
				
			if ((!$mirror) || (strcmp($file,$mirror->getFileName())!=0))
				return false;
		}
	
		
		return true;
	}
	
	/**
	 * This function checks to see if it is being called at somepoint by a function defined somewhere 
	 * on a given path (optionally including subdirectories).
	 * 
	 * This function is similar to call_gatekeeper() but returns true if it is being called by a method or function which has been defined on a given path or by a specified file.
	 * 
	 * @param string $path The full path and filename that this function must have in its call stack If a partial path is given and $include_subdirs is true, then the function will return true if called by any function in or below the specified path.
	 * @param bool $include_subdirs Are subdirectories of the path ok, or must you specify an absolute path and filename.
	 * @param bool $strict_mode If true then the calling method or function must be directly called by something on $path, if false the whole call stack is searched.
	 */
	function callpath_gatekeeper($path, $include_subdirs = true, $strict_mode = false)
	{
		global $CONFIG;
		
		$path = sanitise_string($path);
		
		if ($path)
		{
			$callstack = debug_backtrace();
				
			foreach ($callstack as $call)
			{
				$call['file'] = str_replace("\\","/",$call['file']);
				
				if ($include_subdirs)
				{
					if (strpos($call['file'], $path) === 0) {
						
						if ($strict_mode) {
							$callstack[1]['file'] = str_replace("\\","/",$callstack[1]['file']);							
							if ($callstack[1] === $call) { return true; }
						}
						else
						{
							return true;
						}
					}
				}
				else
				{
					if (strcmp($path, $call['file'])==0) {
						if ($strict_mode) {
							if ($callstack[1] === $call) return true;
						} else
							return true;
					}
				}
				
			}
			return false;
		}
		
		if ($CONFIG->debug)
			system_message("Gatekeeper'd function called from {$callstack[1]['file']}:{$callstack[1]['line']}\n\nStack trace:\n\n" . print_r($callstack, true));
		
		return false;
	}

	/**
	 * A utility function which returns true if the code is currently running with 
	 * extended privileges (as provided by execute_privileged_codeblock().)
	 * 
	 * This is essentially a wrapper around call_gatekeeper().
	 * 
	 * @return bool
	 */
	function is_privileged()
	{
		global $CONFIG;
		
		return call_gatekeeper('execute_privileged_codeblock', $CONFIG->path . 'engine/lib/elgglib.php');
	}
	
	/**
	 * Execute a function as a privileged user.
	 * 
	 * Privileged code blocks should be in the format of "function(array $params)" whether they
	 * are in a class or a standalone object.
	 * 
	 * Before executing it triggers an event "execute_privileged_codeblock" which gives code the option 
	 * to deny access based on a number factors (simply return false).
	 * 
	 * @param mixed $function The function (or array(object,method)) to execute.
	 * @param array $params The parameters passed to the function as an array
	 * @return the result of the executed codeblock
	 * @throws SecurityException
	 */
	function execute_privileged_codeblock($function, array $params = null)
	{
		// Test path first
		if (can_path_execute_privileged_codeblock())
		{

			// Test to see if we can actually execute code by calling any other functions
			if (trigger_elgg_event("execute_privileged_codeblock", "all"))
			{
				// Execute
				$result = null;
				
				if (is_array($function))
					$result = $function[0]->$function[1]($params);
				else
					$result = $function($params);	
	
				// Return value
				return $result;
			}
		}
		
		throw new SecurityException(elgg_echo("SecurityException:Codeblock"));
	}
	
	/**
	 * Validate that a given path has privileges to execute a piece of privileged code.
	 * 
	 */
	function can_path_execute_privileged_codeblock()
	{
		global $CONFIG;
		
		// Get a list of paths
		$callstack = debug_backtrace();
		$call_paths = array();
		foreach ($callstack as $call)
			$call_paths[] = sanitise_string($call['path']);
		
		// Get privileged paths
		$paths = get_data("SELECT * from {$CONFIG->dbprefix}privileged_paths");	
		foreach ($paths as $p)
		{
			if (in_array($CONFIG->path . "$p", $call_paths))
				return true;
		}
		
		return false;
	}
	
	
	
?>