<?php

	/**
	 * Elgg library
	 * Contains important functionality core to Elgg
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
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
			global $CONFIG;
			if (!headers_sent()) {
				 
				 $current_page = current_page_url();
				 if (strpos($current_page, $CONFIG->wwwroot . "action") ===false)
				 
				 $_SESSION['messages'] = system_messages();
				 if ((substr_count($location, 'http://') == 0) && (substr_count($location, 'https://') == 0)) {
				 	
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
			if ((isset($url['user'])) && ($url['user'])) $page .= $url['user'];
			if ((isset($url['pass'])) && ($url['pass'])) $page .= ":".$url['pass'];
			if (($url['user']) || $url['pass']) $page .="@";
			
			$page .= $url['host'];
			
			if ((isset($url['port'])) && ($url['port'])) $page .= ":" . $url['port'];
			
			$page = trim($page, "/"); //$page.="/";
			
			$page .= $_SERVER['REQUEST_URI'];
			
			return $page;
		}
		
	/**
	 * Templating and visual functionality
	 */
		
		$CURRENT_SYSTEM_VIEWTYPE = "";
		
		/**
		 * Override the view mode detection for the elgg view system.
		 * 
		 * This function will force any further views to be rendered using $viewtype. Remember to call elgg_set_viewtype() with
		 * no parameters to reset.
		 *
		 * @param string $viewtype The view type, e.g. 'rss', or 'default'.
		 * @return bool
		 */
		function elgg_set_viewtype($viewtype = "")
		{
			global $CURRENT_SYSTEM_VIEWTYPE;
			
			$CURRENT_SYSTEM_VIEWTYPE = $viewtype;
			
			return true;
		}
		
		/**
		 * Return the current view type used by the elgg view system.
		 * 
		 * By default, this function will return a value based on the default for your system or from the command line
		 * view parameter. However, you may force a given view type by calling elgg_set_viewtype()
		 *
		 * @return string The view.
		 */
		function elgg_get_viewtype()
		{
			global $CURRENT_SYSTEM_VIEWTYPE, $CONFIG;
			
			$viewtype = NULL;
			
			if ($CURRENT_SYSTEM_VIEWTYPE != "")
				return $CURRENT_SYSTEM_VIEWTYPE;
				
			if ((empty($_SESSION['view'])) || ( (trim($CONFIG->view!="")) && ($_SESSION['view']!=$CONFIG->view) )) {
		        $_SESSION['view'] = "default";
		        
		        // If we have a config default view for this site then use that instead of 'default'
		        if (/*(is_installed()) && */(!empty($CONFIG->view)) && (trim($CONFIG->view)!=""))
		        	$_SESSION['view'] = $CONFIG->view;
		    }
				
		    if (empty($viewtype) && is_callable('get_input'))
		        $viewtype = get_input('view');
		        
		    if (empty($viewtype)) 
		        $viewtype = $_SESSION['view'];
		    
		    return $viewtype;
		}
		
		/**
		 * Return the location of a given view.
		 *
		 * @param string $view The view.
		 * @param string $viewtype The viewtype
		 */
		function elgg_get_view_location($view, $viewtype = '')
		{
			global $CONFIG;
		
			if (empty($viewtype))
				$viewtype = elgg_get_viewtype();
			
			if (!isset($CONFIG->views->locations[$viewtype][$view])) {
	    		if (!isset($CONFIG->viewpath)) {
					return dirname(dirname(dirname(__FILE__))) . "/views/";		    			
	    		} else {
	    			return $CONFIG->viewpath;
	    		}
	    	} else {
	    		return $CONFIG->views->locations[$viewtype][$view];
	    	}
	    	
	    	return false;
		}
		
	/**
	 * Generate a view hash [EXPERIMENTAL].
	 *
	 * @param string $view The view name
	 * @param array $vars The view parameters
	 * @return string The hash.
	 */	
		function elgg_get_viewhash($view, $vars)
		{			
			$unchanged_vars = unserialize(serialize($vars));
			
			// This is a bit of a hack, but basically we have to remove things that change on each pageload 
			if (isset($unchanged_vars['entity']->last_action)) $unchanged_vars['entity']->last_action = 0;
			if (isset($unchanged_vars['entity']->prev_last_action)) $unchanged_vars['entity']->prev_last_action = 0;
			
			if (isset($unchanged_vars['user']->last_action)) $unchanged_vars['user']->last_action = 0;
			if (isset($unchanged_vars['user']->prev_last_action)) $unchanged_vars['user']->prev_last_action = 0;
			
			
			return md5(current_page_url() . $view . serialize($unchanged_vars)); // This should be enough to stop artefacts
			
		}
	
		
	/**
	 * Get a cached view based on its hash.
	 *
	 * @param string $viewhash
	 * @return string or false on if no cache returned.
	 */
		function elgg_get_cached_view($viewhash)
		{
			global $view_cache, $VIEW_CACHE_DISABLED, $CONFIG;
			
			if (($VIEW_CACHE_DISABLED) || (!$CONFIG->viewcache)) return false;
			
			if ((!$view_cache) && (is_memcache_available())) 
				$view_cache = new ElggMemcache('view_cache');
			if ($view_cache) {
				
				$cached_view = $view_cache->load($viewhash);

				if ($cached_view)
				{
					error_log("MARCUS : LOADED $view:$viewhash from cache");
					return $cached_view;
				}
				else
					error_log("MARCUS : View $view:$viewhash not cached");
				
			}
			
			return false;
		}
		
	/**
	 * Temporarily disable view caching.
	 *
	 */
		function elgg_disable_view_cache()
		{
			global $VIEW_CACHE_DISABLED;
			
			$VIEW_CACHE_DISABLED = true;
		}
		
	/**
	 * Handles templating views
	 *
	 * @see set_template_handler
	 * 
	 * @param string $view The name and location of the view to use
	 * @param array $vars Any variables that the view requires, passed as an array
	 * @param boolean $bypass If set to true, elgg_view will bypass any specified alternative template handler; by default, it will hand off to this if requested (see set_template_handler)
	 * @param boolean $debug If set to true, the viewer will complain if it can't find a view
	 * @param string $viewtype If set, forces the viewtype for the elgg_view call to be this value (default: standard detection) 
	 * @return string The HTML content
	 */
		function elgg_view($view, $vars = "", $bypass = false, $debug = false, $viewtype = '') {

		    global $CONFIG;
		    static $usercache;
		    
		// Trigger the pagesetup event
			if (!isset($CONFIG->pagesetupdone)) {
				trigger_elgg_event('pagesetup','system');
				$CONFIG->pagesetupdone = true;
			}
		    
		    if (!is_array($usercache)) {
		        $usercache = array();
		    }
		
		    if (empty($vars)) {
		        $vars = array();
		    }
		
		// Load session and configuration variables into $vars
		    if (isset($_SESSION) /*&& is_array($_SESSION)*/ ) { // $_SESSION will always be an array if it is set
		        $vars += $_SESSION; //= array_merge($vars, $_SESSION);
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
		    if (($vars['page_owner'] != -1) && (is_installed())) {
		        if (!isset($usercache[$vars['page_owner']])) {
		    	       $vars['page_owner_user'] = get_entity($vars['page_owner']);
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
		    	if (is_callable($template_handler))
		    		return $template_handler($view, $vars);
		    }
		
		
		// Get the current viewtype
			if (empty($viewtype))
				$viewtype = elgg_get_viewtype(); 
		
		// Set up any extensions to the requested view
		    if (isset($CONFIG->views->extensions[$view])) {
		    	$viewlist = $CONFIG->views->extensions[$view];
		    } else {
		    	$viewlist = array(500 => $view);
		    }	    
		// Start the output buffer, find the requested view file, and execute it
		    ob_start();
		    
		// Attempt to cache views [EXPERIMENTAL] 
			/*if (ob_get_level()==1) {
			    $view_hash = elgg_get_viewhash($view, $vars); 
			    $view_cache = elgg_get_filepath_cache();
			    $cached_view = $view_cache->load($view_hash);
			    
			    if ($cached_view) {
			    	ob_get_clean();
			    	return $cached_view;
			    }
			}*/
		    
		    foreach($viewlist as $priority => $view) {
		    	
		    	$view_location = elgg_get_view_location($view, $viewtype);
		    			    	
			    if (file_exists($view_location . "{$viewtype}/{$view}.php") && !include($view_location . "{$viewtype}/{$view}.php")) {
			        $success = false;
			        
			        if ($viewtype != "default") {
			            if (include($view_location . "default/{$view}.php")) {
			                $success = true;
			            }
			        }
			        if (!$success && isset($CONFIG->debug) && $CONFIG->debug == true) {
			            error_log(" [This view ({$view}) does not exist] ");
			        }
			    } else if (isset($CONFIG->debug) && $CONFIG->debug == true && !file_exists($view_location . "{$viewtype}/{$view}.php")) {
		    	
			    	error_log($view_location . "{$viewtype}/{$view}.php");
			    	error_log(" [This view ({$view}) does not exist] ");
			    }
		    
		    }

	    // Cache view [EXPERIMENTAL]
		//if (ob_get_level()==1) // Only cache top level view
		//	$view_cache->save($view_hash, $content);
				
		// Save the output buffer into the $content variable
			$content = ob_get_clean();
			
		// Plugin hook
			$content = trigger_plugin_hook('display','view',array('view' => $view),$content);
		
		// Return $content
		    return $content;
		
		}
		
	/**
	 * Returns whether the specified view exists
	 *
	 * @param string $view The view name
	 * @param string $viewtype If set, forces the viewtype
	 * @return true|false Depending on success
	 */
		function elgg_view_exists($view, $viewtype = '') {
			
				global $CONFIG;
			
			// Detect view type
				if (empty($viewtype))
			    	$viewtype = elgg_get_viewtype();
			    
				if (!isset($CONFIG->views->locations[$viewtype][$view])) {
		    		if (!isset($CONFIG->viewpath)) {
						$location = dirname(dirname(dirname(__FILE__))) . "/views/";		    			
		    		} else {
		    			$location = $CONFIG->viewpath;
		    		}
		    	} else {
		    		$location = $CONFIG->views->locations[$viewtype][$view];
		    	}
		    	
		    	if (file_exists($location . "{$viewtype}/{$view}.php")) {
		    		return true;
		    	}
		    	
		    	return false;
			
		}
		
	/**
	 * Registers a view to be simply cached
	 * 
	 * Views cached in this manner must take no parameters and be login agnostic -
	 * that is to say, they look the same no matter who is logged in (or logged out).
	 * 
	 * CSS and the basic jS views are automatically cached like this.
	 *
	 * @param string $viewname View name
	 */
		function elgg_view_register_simplecache($viewname) {
			
			global $CONFIG;
			
			if (!isset($CONFIG->views->simplecache))
				$CONFIG->views->simplecache = array();
			
			//if (elgg_view_exists($viewname))
				$CONFIG->views->simplecache[] = $viewname;				
			
		}
		
	/**
	 * Regenerates the simple cache.
	 * 
	 * @see elgg_view_register_simplecache
	 *
	 */
		function elgg_view_regenerate_simplecache() {
			
			global $CONFIG;
			
			if (isset($CONFIG->views->simplecache)) {
				
				if (!file_exists($CONFIG->dataroot . 'views_simplecache')) {
					@mkdir($CONFIG->dataroot . 'views_simplecache');
				}
				
				if (!empty($CONFIG->views->simplecache) && is_array($CONFIG->views->simplecache)) {
					foreach($CONFIG->views->simplecache as $view) {
						$viewcontents = elgg_view($view);
						$viewname = md5(elgg_get_viewtype() . $view);
						if ($handle = fopen($CONFIG->dataroot . 'views_simplecache/' . $viewname, 'w')) {
							fwrite($handle, $viewcontents);
							fclose($handle);
						}
					}
				}
				
				datalist_set('simplecache_lastupdate',0);
				
			}
			
		}
		
	/**
	 * Enables the simple cache.
	 * 
	 * @see elgg_view_register_simplecache
	 *
	 */
		
		function elgg_view_enable_simplecache() {
			global $CONFIG;
			if(!$CONFIG->simplecache_enabled) {
				datalist_set('simplecache_enabled',1);
				$CONFIG->simplecache_enabled = 1;
				elgg_view_regenerate_simplecache();
			}
		}
		
	/**
	 * Disables the simple cache.
	 * 
	 * @see elgg_view_register_simplecache
	 *
	 */
		
		function elgg_view_disable_simplecache() {
			global $CONFIG;
			if ($CONFIG->simplecache_enabled) {
				datalist_set('simplecache_enabled',0);
				$CONFIG->simplecache_enabled = 0;
					
				// purge simple cache
				if ($handle = opendir($CONFIG->dataroot.'views_simplecache')) {	
				    while (false !== ($file = readdir($handle))) {
				    	if ($file != "." && $file != "..") {
				        	unlink($CONFIG->dataroot.'views_simplecache/'.$file);
				    	}
				    }	
	    			closedir($handle);
				}
			}
		}
		
		/**
		 * This is a factory function which produces an ElggCache object suitable for caching file load paths.
		 *
		 * TODO: Can this be done in a cleaner way?
		 * TODO: Swap to memcache etc?
		 */
		function elgg_get_filepath_cache()
		{
			global $CONFIG;
			static $FILE_PATH_CACHE;
			if (!$FILE_PATH_CACHE) $FILE_PATH_CACHE = new ElggFileCache($CONFIG->dataroot);
			
			return $FILE_PATH_CACHE;
		}
		
		/**
		 * Internal function for retrieving views used by elgg_view_tree
		 *
		 * @param unknown_type $dir
		 * @param unknown_type $base
		 * @return unknown
		 */
		function get_views($dir, $base) {
				
			$return = array();
			if (file_exists($dir) && is_dir($dir)) {
				if ($handle = opendir($dir)) {
					while ($view = readdir($handle)) {
						if (!in_array($view, array('.','..','.svn','CVS'))) {
							if (is_dir($dir . '/' . $view)) {
								if ($val = get_views($dir . '/' . $view, $base . '/' . $view)) {
									$return = array_merge($return, $val);
								}
							} else {
								$view = str_replace('.php','',$view);
								$return[] = $base . '/' . $view;
							}
						}
					}
				}
			}
			return $return;
		}

	/**
	 * When given a partial view root (eg 'js' or 'page_elements'), returns an array of views underneath it
	 *
	 * @param string $view_root The root view
	 * @param string $viewtype Optionally specify a view type other than the current one.
	 * @return array A list of view names underneath that root view
	 */
		function elgg_view_tree($view_root, $viewtype = "") {
			
				global $CONFIG;
				static $treecache;
			
			// Get viewtype
				if (!$viewtype)
					$viewtype = elgg_get_viewtype();
				
			// Has the treecache been initialised?
				if (!isset($treecache)) $treecache = array();			
			// A little light internal caching
				if (!empty($treecache[$view_root])) return $treecache[$view_root];
			
			// Examine $CONFIG->views->locations
				if (isset($CONFIG->views->locations[$viewtype])) {
					foreach($CONFIG->views->locations[$viewtype] as $view => $path) {
						$pos = strpos($view,$view_root);
						if ($pos === 0) {
							$treecache[$view_root][] = $view; 
						}
					}
				}
	
			// Now examine core
				$location = $CONFIG->viewpath;
				$viewtype = elgg_get_viewtype();
				$root = $location . $viewtype . '/' . $view_root;
			
				if (file_exists($root) && is_dir($root)) {				
					$val = get_views($root, $view_root);				
					if (!is_array($treecache[$view_root])) $treecache[$view_root] = array();				
					$treecache[$view_root] = array_merge($treecache[$view_root], $val);
				}

				return $treecache[$view_root];
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
	 * @param boolean $full Determines whether or not to display the full version of an object, or a smaller version for use in aggregators etc
	 * @param boolean $bypass If set to true, elgg_view will bypass any specified alternative template handler; by default, it will hand off to this if requested (see set_template_handler)
	 * @param boolean $debug If set to true, the viewer will complain if it can't find a view
	 * @return string HTML (etc) to display
	 */
		function elgg_view_entity(ElggEntity $entity, $full = false, $bypass = true, $debug = false) {
			
			global $autofeed;
			$autofeed = true;
			
			// No point continuing if entity is null.
			if (!$entity) return ''; 
			
			$view = $entity->view;
			if (is_string($view)) {
				return elgg_view($view,array('entity' => $entity), $bypass, $debug);
			}
			
			$classes = array(
								'ElggUser' => 'user',
								'ElggObject' => 'object',
								'ElggSite' => 'site',
								'ElggGroup' => 'group'
							);
			
			$entity_class = get_class($entity);
			
			if (isset($classes[$entity_class])) {
				$entity_type = $classes[$entity_class];
			} else {
				foreach($classes as $class => $type) {
					if ($entity instanceof $class) {
						$entity_type = $type;
						break;
					}
				}
			}
			if (!isset($entity_class)) return false;
			
			$subtype = $entity->getSubtype();
			if (empty($subtype)) { $subtype = $entity_type; }

			$contents = '';
			if (elgg_view_exists("{$entity_type}/{$subtype}")) {
				$contents = elgg_view("{$entity_type}/{$subtype}",array(
																	'entity' => $entity,
																	'full' => $full
																	), $bypass, $debug);
			} 
			if (empty($contents)) {
				$contents = elgg_view("{$entity_type}/default",array(
																'entity' => $entity,
																'full' => $full
																), $bypass, $debug);
			}
			return $contents;
		}

	/**
	 * When given an annotation, views it intelligently.
	 * 
	 * This function expects annotation views to be of the form annotation/name, where name
	 * is the type of annotation. 
	 *
	 * @param ElggAnnotation $annotation The annotation to display
	 * @param boolean $full Determines whether or not to display the full version of an object, or a smaller version for use in aggregators etc
	 * @param boolean $bypass If set to true, elgg_view will bypass any specified alternative template handler; by default, it will hand off to this if requested (see set_template_handler)
	 * @param boolean $debug If set to true, the viewer will complain if it can't find a view
	 * @return string HTML (etc) to display
	 */
		function elgg_view_annotation(ElggAnnotation $annotation, $bypass = true, $debug = false) {
			
			global $autofeed;
			$autofeed = true;
			
			$view = $annotation->view;
			if (is_string($view)) {
				return elgg_view($view,array('annotation' => $annotation), $bypass, $debug);
			}
			
			$name = $annotation->name;
			$intname = (int) $name;
			if ("{$intname}" == "{$name}") {
				$name = get_metastring($intname);
			}
			if (empty($name)) { return ""; }
			
			if (elgg_view_exists("annotation/{$name}")) {
				return elgg_view("annotation/{$name}",array(
																	'annotation' => $annotation,
																	), $bypass, $debug);
			} else {
				return elgg_view("annotation/default",array(
																'annotation' => $annotation,
																), $bypass, $debug);
			}
		}
				
	
	/**
	 * Returns a view of a list of entities, plus navigation. It is intended that this function
	 * be called from other wrapper functions.
	 * 
	 * @see list_entities
	 * @see list_user_objects
	 * @see list_user_friends_objects
	 * @see list_entities_from_metadata
	 * @see list_entities_from_metadata_multi
	 * @see list_entities_from_relationships
	 * @see list_site_members
	 *
	 * @param array $entities List of entities
	 * @param int $count The total number of entities across all pages
	 * @param int $offset The current indexing offset
	 * @param int $limit The number of entities to display per page
	 * @param true|false $fullview Whether or not to display the full view (default: true)
	 * @param true|false $viewtypetoggle Whether or not to allow users to toggle to gallery view
	 * @param bool $pagination Whether pagination is offered.
	 * @return string The list of entities
	 */
		function elgg_view_entity_list($entities, $count, $offset, $limit, $fullview = true, $viewtypetoggle = true, $pagination = true) {
			
			$count = (int) $count;
			$offset = (int) $offset;
			$limit = (int) $limit;
			
			$context = get_context();
			
			$html = elgg_view('search/entity_list',array(
													'entities' => $entities,
													'count' => $count,
													'offset' => $offset,
													'limit' => $limit,
													'baseurl' => $_SERVER['REQUEST_URI'],
													'fullview' => $fullview,
													'context' => $context, 
													'viewtypetoggle' => $viewtypetoggle,
													'viewtype' => get_input('search_viewtype','list'), 
													'pagination' => $pagination
												  ));
				
			return $html;
			
		}

	/**
	 * Returns a view of a list of annotations, plus navigation. It is intended that this function
	 * be called from other wrapper functions.
	 * 
	 * @param array $annotations List of annotations
	 * @param int $count The total number of annotations across all pages
	 * @param int $offset The current indexing offset
	 * @param int $limit The number of annotations to display per page
	 * @return string The list of annotations
	 */
		
		function elgg_view_annotation_list($annotations, $count, $offset, $limit) {
			
			$count = (int) $count;
			$offset = (int) $offset;
			$limit = (int) $limit;
			
			$html = "";
			
			$nav = elgg_view('navigation/pagination',array(
			
												'baseurl' => $_SERVER['REQUEST_URI'],
												'offset' => $offset,
												'count' => $count,
												'limit' => $limit,
												'word' => 'annoff',
												'nonefound' => false,
			
														));
			
			$html .= $nav;

			if (is_array($annotations) && sizeof($annotations) > 0) {
				foreach($annotations as $annotation) {
					$html .= elgg_view_annotation($annotation, "", false);
				}
			}
			
			if ($count)
				$html .= $nav;
				
			return $html;
			
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
			if (elgg_view_exists("canvas/layouts/{$layout}")) {
				return elgg_view("canvas/layouts/{$layout}",$param_array);
			} else {
				return elgg_view("canvas/default",$param_array);
			}
				
		}
	
	/**
	 * Returns a view for the page title
	 *
	 * @param string $title The page title
	 * @param string $submenu Should a submenu be displayed? (default false, use not recommended)
	 * @return string The HTML (etc)
	 */
		function elgg_view_title($title, $submenu = false) {
			
			$title = elgg_view('page_elements/title', array('title' => $title, 'submenu' => $submenu));
			
			return $title;
			
		}
		
	/**
	 * Adds an item to the submenu
	 *
	 * @param string $label The human-readable label
	 * @param string $link The URL of the submenu item
	 * @param boolean $onclick Used to provide a JS popup to confirm delete
	 */
		function add_submenu_item($label, $link, $group = 'a', $onclick = false) {
			
			global $CONFIG;
			if (!isset($CONFIG->submenu)) $CONFIG->submenu = array();
			if (!isset($CONFIG->submenu[$group])) $CONFIG->submenu[$group] = array();
			$item = new stdClass;
			$item->value = $link;
			$item->name = $label;
			$item->onclick = $onclick;
			$CONFIG->submenu[$group][] = $item; 
			
		}
		
	/**
	 * Gets a formatted list of submenu items
	 *
	 * @return string List of items
	 */
		function get_submenu() {
			
			$submenu_total = "";
			global $CONFIG;
			
			if (isset($CONFIG->submenu) && $submenu_register = $CONFIG->submenu) {
				
				$preselected = false;
				$comparevals = array();
				$maxcompareval = 999999;
				
				//asort($submenu_register);
				ksort($submenu_register);
				
				foreach($submenu_register as $groupname => $submenu_register_group) {
					foreach($submenu_register_group as $key => $item) {
						
						if (substr_count($item->value, $_SERVER['REQUEST_URI'])) {
							$comparevals[$key] = levenshtein($item->value, $_SERVER['REQUEST_URI']);
							if ($comparevals[$key] < $maxcompareval) {
								$maxcompareval = $comparevals[$key];
								$preselected = $key;
								$preselectedgroup = $groupname;
							}
						}
						
					}
				}
				
				foreach($submenu_register as $groupname => $submenu_register_group) {
				
					$submenu = "";
					
					foreach($submenu_register_group as $key => $item) {
	
						if ($preselected === false) {
							if (substr_count($item->value, $_SERVER['REQUEST_URI'])) {
								$preselected = $key;
								$preselectedgroup = $groupname;
								$selected = true;
							} else {
								$selected = false;
							}
						} else {
							if ($key == $preselected && $groupname == $preselectedgroup) {
								$selected = true;
							} else {
								$selected = false;
							}
						}
						
						$submenu .= elgg_view('canvas_header/submenu_template',
										array(
												'href' => $item->value, 
												'label' => $item->name,
												'onclick' => $item->onclick,
												'selected' => $selected,
											));
						
					}
					
					$submenu_total .= elgg_view('canvas_header/submenu_group', array(
												'submenu' => $submenu,
												'group_name' => $groupname
											));
					
				}
				
			}
			
			return $submenu_total;
			
		}
		
		
	/**
	 * Automatically views comments and a comment form relating to the given entity
	 *
	 * @param ElggEntity $entity The entity to comment on
	 * @return string|false The HTML (etc) for the comments, or false on failure
	 */
		function elgg_view_comments($entity){

			if (!($entity instanceof ElggEntity)) return false;
            
			if ($comments = trigger_plugin_hook('comments',$entity->getType(),array('entity' => $entity),false)) {

				return $comments;

			} else {
	            $comments = list_annotations($entity->getGUID(),'generic_comment');
	            
	            //display the comment form
	            $comments .= elgg_view('comments/forms/edit',array('entity' => $entity));
	            
	            return $comments;
			}
           
        }

	/**
	 * Count the number of comments attached to an entity
	 *
	 * @param ElggEntity $entity
	 * @return int Number of comments
	 */
        function elgg_count_comments($entity) {
        	
        	if ($commentno = trigger_plugin_hook('comments:count',$entity->getType(),array('entity' => $entity),false)) {
        		return $commentno;
        	} else 
        	return count_annotations($entity->getGUID(), "", "", "generic_comment");
        }
        
	/**
	 * Wrapper function to display search listings.
	 *
	 * @param string $icon The icon for the listing
	 * @param string $info Any information that needs to be displayed.
	 * @return string The HTML (etc) representing the listing
	 */		
		function elgg_view_listing($icon, $info) {
			return elgg_view('search/listing',array('icon' => $icon, 'info' => $info));			
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
		function extend_view($view, $view_name, $priority = 501, $viewtype = '') {
			
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
		function set_view_location($view, $location, $viewtype = '') {
			
			global $CONFIG;
			
			if (empty($viewtype))
				$viewtype = 'default';
			
			if (!isset($CONFIG->views)) {
				$CONFIG->views = new stdClass;
			}
			if (!isset($CONFIG->views->locations)) {
				$CONFIG->views->locations = array($viewtype => array(
																	$view => $location
																));
			} else if (!isset($CONFIG->views->locations[$viewtype])) {
				$CONFIG->views->locations[$viewtype] = array(
																	$view => $location
																);
			} else {
				$CONFIG->views->locations[$viewtype][$view] = $location;
			}
			
		}
		
	/**
	 * Auto-registers views from a particular starting location
	 *
	 * @param string $view_base The base of the view name
	 * @param string $folder The folder to begin looking in
	 * @param string $base_location_path The base views directory to use with set_view_location
	 * @param string $viewtype The type of view we're looking at (default, rss, etc)
	 */		
		function autoregister_views($view_base, $folder, $base_location_path, $viewtype) {
			
			if (!isset($i)) $i = 0;
			if ($handle = opendir($folder)) {
				while ($view = readdir($handle)) {
					if (!in_array($view,array('.','..','.svn','CVS')) && !is_dir($folder . "/" . $view)) {
						if ((substr_count($view,".php") > 0) || (substr_count($view,".png") > 0)) {
							if (!empty($view_base)) { $view_base_new = $view_base . "/"; } else { $view_base_new = ""; }
							set_view_location($view_base_new . str_replace(".php","",$view), $base_location_path, $viewtype);
						}
					} else if (!in_array($view,array('.','..','.svn','CVS')) && is_dir($folder . "/" . $view)) {
						if (!empty($view_base)) { $view_base_new = $view_base . "/"; } else { $view_base_new = ""; }
						autoregister_views($view_base_new . $view, $folder . "/" . $view, $base_location_path, $viewtype);
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

			// Draw the page
			$output = elgg_view('pageshells/pageshell', array(
												'title' => $title,
												'body' => $body,
												'sidebar' => $sidebar,
												'sysmessages' => system_messages(null,"")
											  )
										);
			$split_output = str_split($output, 1024);

    		foreach($split_output as $chunk)
        		echo $chunk; 
		}
		
	/**
	 * Displays a UNIX timestamp in a friendly way (eg "less than a minute ago")
	 *
	 * @param int $time A UNIX epoch timestamp
	 * @return string The friendly time
	 */
		function friendly_time($time) {
			
			$diff = time() - ((int) $time);
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
	 * When given a title, returns a version suitable for inclusion in a URL
	 *
	 * @param string $title The title
	 * @return string The optimised title
	 */
		function friendly_title($title) {
			$title = trim($title);
			$title = strtolower($title);
			$title = preg_replace("/[^\w ]/","",$title); 
			$title = str_replace(" ","-",$title);
			$title = str_replace("--","-",$title);
			return $title;
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
			/*if (is_file($directory) && !in_array($directory,$file_exceptions)) {
				$file_list[] = $directory;
			} else */
			if ($handle = opendir($directory)) {
				while ($file = readdir($handle)) {
					if (in_array(strrchr($file, '.'), $extensions_allowed) && !in_array($file,$file_exceptions)) {
						$file_list[] = $directory . "/" . $file;
						//$file_list = get_library_files($directory . "/" . $file, $file_exceptions, $file_list);
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
			
			if (empty($register_name) || empty($subregister_name))
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
							return false;
							//$return = false;
							//break;
						}
					}
				}
				
				if (!empty($CONFIG->events['all'][$object_type]) && is_array($CONFIG->events['all'][$object_type])) {					
					foreach($CONFIG->events['all'][$object_type] as $eventfunction) {
						if ($eventfunction($event, $object_type, $object) === false) {
							return false;
							//$return = false;
							//break;
						}
					}
				}
			
				if (!empty($CONFIG->events[$event]['all']) && is_array($CONFIG->events[$event]['all'])) {						
					foreach($CONFIG->events[$event]['all'] as $eventfunction) {
						if ($eventfunction($event, $object_type, $object) === false) {
							return false;
							//$return = false;
							//break;
						}
					}
				}
			
				if (!empty($CONFIG->events['all']['all']) && is_array($CONFIG->events['all']['all'])) {					
					foreach($CONFIG->events['all']['all'] as $eventfunction) {
						if ($eventfunction($event, $object_type, $object) === false) {
							return false;
							//$return = false;
							//break;
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
	 * Triggers a plugin hook, with various parameters as an array. For example, to provide
	 * a 'foo' hook that concerns an entity of type 'bar', with a parameter called 'param1'
	 * with value 'value1', that by default returns true, you'd call:
	 * 
	 * trigger_plugin_hook('foo', 'bar', array('param1' => 'value1'), true);
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
			//else
			//if (!isset($CONFIG->hooks['all'][$entity_type]))
			//	return $returnvalue;
			
			if (!empty($CONFIG->hooks['all'][$entity_type]) && is_array($CONFIG->hooks['all'][$entity_type])) {
				foreach($CONFIG->hooks['all'][$entity_type] as $hookfunction) {
					
					$temp_return_value = $hookfunction($hook, $entity_type, $returnvalue, $params);
					if (!is_null($temp_return_value)) $returnvalue = $temp_return_value;
				}
			}
			//else
			//if (!isset($CONFIG->hooks[$hook]['all']))
			//	return $returnvalue;
			
			if (!empty($CONFIG->hooks[$hook]['all']) && is_array($CONFIG->hooks[$hook]['all'])) {
				foreach($CONFIG->hooks[$hook]['all'] as $hookfunction) {
					
					$temp_return_value = $hookfunction($hook, $entity_type, $returnvalue, $params);
					if (!is_null($temp_return_value)) $returnvalue = $temp_return_value;
				}
			}
			//else
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
					global $CONFIG;
					if (isset($CONFIG->debug)) {
						error_log("DEBUG: " . $error); 
					}
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
			
			ob_end_clean(); // Wipe any existing output buffer
			
			$body = elgg_view("messages/exceptions/exception",array('object' => $exception));
			page_draw(elgg_echo('exception:title'), $body);
			
		}
		
	/**
	 * Data lists
	 */
		
	$DATALIST_CACHE = array();
		
	/**
	 * Get the value of a particular piece of data in the datalist
	 *
	 * @param string $name The name of the datalist
	 * @return string|false Depending on success
	 */	
		function datalist_get($name) {
			
			global $CONFIG, $DATALIST_CACHE;
			
			// We need this, because sometimes datalists are received before the database is created
			if (!is_db_installed()) return false;
			
			$name = sanitise_string($name);
			if (isset($DATALIST_CACHE[$name]))
				return $DATALIST_CACHE[$name];
				
			// If memcache enabled then cache value in memcache
			$value = null;
			static $datalist_memcache;
			if ((!$datalist_memcache) && (is_memcache_available()))
				$datalist_memcache = new ElggMemcache('datalist_memcache');
			if ($datalist_memcache) $value = $datalist_memcache->load($name);
			if ($value) return $value;
			
			// [Marcus Povey 20090217 : Now retrieving all datalist values on first load as this saves about 9 queries per page]
			$result = get_data("SELECT * from {$CONFIG->dbprefix}datalists");
			if ($result)
			{
				foreach ($result as $row)
				{
					$DATALIST_CACHE[$row->name] = $row->value;
				
					// Cache it if memcache is available
					if ($datalist_memcache) $datalist_memcache->save($name, $row->value);
				}
				
				if (isset($DATALIST_CACHE[$name]))
					return $DATALIST_CACHE[$name];
			}
			
			
			/*if ($row = get_data_row("SELECT value from {$CONFIG->dbprefix}datalists where name = '{$name}' limit 1")) {
				$DATALIST_CACHE[$name] = $row->value;
				
				// Cache it if memcache is available
				if ($datalist_memcache) $datalist_memcache->save($name, $row->value);
				
				return $row->value;
			}*/
			
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
			
			global $CONFIG, $DATALIST_CACHE;
			
			$name = sanitise_string($name);
			$value = sanitise_string($value);
			
			// If memcache is available then invalidate the cached copy
			static $datalist_memcache;
			if ((!$datalist_memcache) && (is_memcache_available()))
				$datalist_memcache = new ElggMemcache('datalist_memcache');
			if ($datalist_memcache) $datalist_memcache->delete($name);
			
			//delete_data("delete from {$CONFIG->dbprefix}datalists where name = '{$name}'");
			insert_data("INSERT into {$CONFIG->dbprefix}datalists set name = '{$name}', value = '{$value}' ON DUPLICATE KEY UPDATE value='{$value}'");
			
			$DATALIST_CACHE[$name] = $value;
			
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
	 * Returns true or false depending on whether a PHP .ini setting is on or off
	 *
	 * @param string $ini_get_arg The INI setting
	 * @return true|false Depending on whether it's on or off
	 */
	function ini_get_bool($ini_get_arg) {
	    $temp = ini_get($ini_get_arg);
	    
	    if ($temp == '1' or strtolower($temp) == 'on') {
	        return true;
	    }
	    return false;
	}
	
	/**
	 * Function to be used in array_filter which returns true if $string is not null.
	 *
	 * @param string $string
	 * @return bool
	 */
	function is_not_null($string) 
	{
		if (($string==='') || ($string===false) || ($string===null)) 
			return false;

		return true;
	}
	
	/**
	 * Get the full URL of the current page.
	 *
	 * @return string The URL
	 */
	function full_url()
	{
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
	}
	
	/**
	 * Useful function found in the comments on the PHP man page for ip2long.
	 * Returns 1 if an IP matches a given range.
	 * 
	 * TODO: Check licence... assuming this is PD since it was found several places on the interwebs.. 
	 * please check or rewrite.
	 * 
	 * Matches:
	 *  xxx.xxx.xxx.xxx        (exact)
	 *  xxx.xxx.xxx.[yyy-zzz]  (range)
	 *  xxx.xxx.xxx.xxx/nn    (nn = # bits, cisco style -- i.e. /24 = class C)
	 * Does not match:
	 * xxx.xxx.xxx.xx[yyy-zzz]  (range, partial octets not supported)
	 */
	function test_ip($range, $ip) 
	{
		$result = 1;
		
		# IP Pattern Matcher
		# J.Adams <jna@retina.net>
		#
		# Matches:
		#
		# xxx.xxx.xxx.xxx        (exact)
		# xxx.xxx.xxx.[yyy-zzz]  (range)
		# xxx.xxx.xxx.xxx/nn    (nn = # bits, cisco style -- i.e. /24 = class C)
		#
		# Does not match:
		# xxx.xxx.xxx.xx[yyy-zzz]  (range, partial octets not supported)
		
		
		if (ereg("([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)/([0-9]+)",$range,$regs)) {
	
			# perform a mask match
			$ipl = ip2long($ip);
			$rangel = ip2long($regs[1] . "." . $regs[2] . "." . $regs[3] . "." . $regs[4]);
	
			$maskl = 0;
	
			for ($i = 0; $i< 31; $i++) {
				 if ($i < $regs[5]-1) {
					 $maskl = $maskl + pow(2,(30-$i));
				 }
			}
	
			if (($maskl & $rangel) == ($maskl & $ipl)) {
			 	return 1;
			} else {
			 	return 0;
			}
	   	} else {
	
			 # range based
			 $maskocts = split("\.",$range);
			 $ipocts = split("\.",$ip);
	
			 # perform a range match
			 for ($i=0; $i<4; $i++) {
				 if (ereg("\[([0-9]+)\-([0-9]+)\]",$maskocts[$i],$regs)) {
				   if ( ($ipocts[$i] > $regs[2]) || ($ipocts[$i] < $regs[1])) {
						 $result = 0;
					 }
				 }
				 else
				 {
					 if ($maskocts[$i] <> $ipocts[$i]) {
						 $result = 0;
					 }
				 }
			 }
		}
	  	return $result;
	}
	
	/**
	 * Match an IP address against a number of ip addresses or ranges, returning true if found.
	 *
	 * @param array $networks
	 * @param string $ip
	 * @return bool
	 */
	function is_ip_in_array(array $networks, $ip)
	{
		global $SYSTEM_LOG;
	
		foreach ($networks as $network)
		{
			if (test_ip(trim($network), $ip))
				return true;
		}
		
		return false;
	}
	
	/**
	 * An interface for objects that behave as elements within a social network that have a profile.
	 *
	 */
	interface Friendable {
		
		/**
		 * Adds a user as a friend
		 *
		 * @param int $friend_guid The GUID of the user to add
		 */
		public function addFriend($friend_guid);
		
		/**
		 * Removes a user as a friend
		 *
		 * @param int $friend_guid The GUID of the user to remove
		 */
		public function removeFriend($friend_guid);
		
		/**
		 * Determines whether or not the current user is a friend of this entity
		 *
		 */
		public function isFriend();
		
		/**
		 * Determines whether or not this entity is friends with a particular entity
		 *
		 * @param int $user_guid The GUID of the entity this entity may or may not be friends with 
		 */
		public function isFriendsWith($user_guid);
		
		/**
		 * Determines whether or not a foreign entity has made this one a friend
		 *
		 * @param int $user_guid The GUID of the foreign entity
		 */
		public function isFriendOf($user_guid);
		
		/**
		 * Returns this entity's friends
		 *
		 * @param string $subtype The subtype of entity to return
		 * @param int $limit The number of entities to return
		 * @param int $offset Indexing offset
		 */
		public function getFriends($subtype = "", $limit = 10, $offset = 0);
		
		/**
		 * Returns entities that have made this entity a friend
		 *
		 * @param string $subtype The subtype of entity to return
		 * @param int $limit The number of entities to return
		 * @param int $offset Indexing offset
		 */
		public function getFriendsOf($subtype = "", $limit = 10, $offset = 0);
		
		/**
		 * Returns objects in this entity's container
		 *
		 * @param string $subtype The subtype of entity to return
		 * @param int $limit The number of entities to return
		 * @param int $offset Indexing offset
		 */
		public function getObjects($subtype="", $limit = 10, $offset = 0);
		
		/**
		 * Returns objects in the containers of this entity's friends
		 *
		 * @param string $subtype The subtype of entity to return
		 * @param int $limit The number of entities to return
		 * @param int $offset Indexing offset
		 */
		public function getFriendsObjects($subtype = "", $limit = 10, $offset = 0);
		
		/**
		 * Returns the number of object entities in this entity's container 
		 *
		 * @param string $subtype The subtype of entity to count
		 */
		public function countObjects($subtype = "");
		
	}
	
	function js_page_handler($page) {
		
		if (is_array($page) && sizeof($page)) {
			$js = str_replace('.js','',$page[0]);
			$return = elgg_view('js/' . $js);
			
			header('Content-type: text/javascript');
			header('Expires: ' . date('r',time() + 864000));
			header("Pragma: public");
			header("Cache-Control: public"); 
			header("Content-Length: " . strlen($return));
			
			echo $return;
			exit;
		}
		
	}
	
	/**
	 * This function is a shutdown hook registered on startup which does nothing more than trigger a 
	 * shutdown event when the script is shutting down, but before database connections have been dropped etc.
	 *
	 */
	function __elgg_shutdown_hook()
	{
		global $CONFIG, $START_MICROTIME;
		
		trigger_elgg_event('shutdown', 'system');
		
		if ($CONFIG->debug)
			error_log("Page {$_SERVER['REQUEST_URI']} generated in ".(float)(microtime(true)-$START_MICROTIME)." seconds"); 
	}
	
	function elgg_init() {

		// Menu
			global $CONFIG;
			//add_menu(elgg_echo('content:latest'), $CONFIG->wwwroot . 'dashboard/latest.php');
		// Page handler for JS
			register_page_handler('js','js_page_handler');
			extend_view('js/initialise_elgg','embed/js');
		// Register an event triggered at system shutdown	
			register_shutdown_function('__elgg_shutdown_hook');

	}
	
	function elgg_boot() {

		// Actions
		register_action('comments/add');
		register_action('comments/delete');
		
		elgg_view_register_simplecache('css');
		elgg_view_register_simplecache('js/friendsPickerv1');
		elgg_view_register_simplecache('js/initialise_elgg');
	}
		
	/**
	 * Some useful constant definitions
	 */
		define('ACCESS_DEFAULT',-1);
		define('ACCESS_PRIVATE',0);
		define('ACCESS_LOGGED_IN',1);
		define('ACCESS_PUBLIC',2);
		define('ACCESS_FRIENDS',-2);
	
	register_elgg_event_handler('init','system','elgg_init');
	register_elgg_event_handler('boot','system','elgg_boot',1000);
	
?>