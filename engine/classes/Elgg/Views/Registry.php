<?php
namespace Elgg\Views;

use Elgg\EventsService as Events;
use Elgg\Filesystem\File;
use Elgg\Filesystem\Directory;
use Elgg\Logger;
use Elgg\PluginHooksService as Hooks;
use Elgg\DeprecationWrapper;
use Elgg\Http\Input;
use Elgg\Views\Exception;


/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @todo 2.0 remove deprecated view injections
 *
 * @access private
 */
class Registry {
	/** @var Hooks */
	private $hooks;
	
	/** @var Events */
	private $events;
	
	/** @var Logger */
	private $logger;
	
	/** @var Input */
	private $input;
	
	/** @var \stdClass */
	private $config;
	
	/** @var Viewtype[] */
	private $viewtypes = array();
	
	/** @var View[] */
	private $views = array();
	
	/** @var string */
	private $currentViewtype = '';
	
	/** @var callable */
	private $template_handler = NULL;

	/**
	 * Constructor
	 *
	 * @param \stdClass $config  The global Elgg config
	 * @param Events    $events  The events service
	 * @param Hooks     $hooks   The hooks service
	 * @param Input     $input   The HTTP Input
	 * @param Logger    $logger  Logger
	 */
	public function __construct(
			\stdClass $config,
			Events $events,
			Hooks $hooks,
			Input $input,
			Logger $logger) {
		$this->config = $config;
		$this->events = $events;
		$this->hooks = $hooks;
		$this->input = $input;
		$this->logger = $logger;
	}

	/**
	 * Get the user object in a wrapper
	 * 
	 * @return DeprecationWrapper|null
	 */
	private function getUserWrapper() {
		$user = elgg_get_logged_in_user_entity();
		if (!$user) {
			return NULL;
		}
		
		$warning = 'Use elgg_get_logged_in_user_entity() rather than assuming elgg_view() '
			. 'populates $vars["user"]';
		return new DeprecationWrapper($user, $warning, 1.8);
	}
	
	/**
	 * @return Elgg_DeprecationWrapper
	 */
	private function getConfigWrapper() {
		$warning = 'Do not rely on $vars["config"] being available in views';
		return new DeprecationWrapper($this->config, $warning, 1.8);
	}
	
	/**
	 * @return Elgg_DeprecationWrapper
	 */
	private function getSiteUrlWrapper() {
		$warning = 'Do not rely on $vars["url"] being available in views';
		return new DeprecationWrapper(elgg_get_site_url(), $warning, 1.8);
	}
	
	/**
	 * Manually set the viewtype.
	 *
	 * View types are detected automatically.  This function allows
	 * you to force subsequent views to use a different viewtype.
	 * 
	 * @param string $viewtype The new viewtype
	 * 
	 * @return void
	 */
	public function setCurrentViewtype($viewtype = '') {
		$this->currentViewtype = $this->getOrCreateViewtype($viewtype);
	}
	
	/**
	 * Return the current view type.
	 *
	 * Viewtypes are automatically detected and can be set with
	 * $_REQUEST['view'] or {@link elgg_set_viewtype()}.
	 *
	 * @internal Viewtype is determined in this order:
	 *  - $CURRENT_SYSTEM_VIEWTYPE Any overrides by {@link elgg_set_viewtype()}
	 *  - $CONFIG->view  The default view as saved in the DB.
	 *
	 * @return Viewtype
	 */
	public function getCurrentViewtype() {
		if ($this->currentViewtype != null) {
			return $this->currentViewtype;
		}

		try {
			$viewtypeInput = $this->input->get('view', '', false);
			return $this->getOrCreateViewtype($viewtypeInput);
		} catch (\Exception $e) {}
		
		try {
			return $this->getOrCreateViewtype($this->config->view);
		} catch (\Exception $e) {}
		
		return $this->getOrCreateViewtype('default');
	}

	/**
	 * Auto-registers views from a location.
	 *
	 * @note Views in plugin/views/ are automatically registered for active plugins.
	 * Plugin authors would only need to call this if optionally including
	 * an entire views structure.
	 *
	 * @param string    $view_base The base of the view name without the view type
	 * @param Directory $folder    The folder to begin looking in
	 * @param string    $viewtype  The type of view we're looking at (default, rss, etc)
	 * 
	 * @return View[] The list of views that were registered
	 * @access private
	 */
	public function registerViews($view_base, Directory $folder, $viewtype = 'default') {
		$viewtype = $this->getOrCreateViewtype($viewtype);
		
		$views = array();
		
		foreach ($folder->getFiles() as $file) {
			if (!$file->isPrivate()) {
				$views[] = $this->registerView($view_base, $file, $viewtype);
			}
		}

		return $views;
	}
	
	/**
	 * @param string   $base
	 * @param File     $file
	 * @param Viewtype $viewtype
	 * 
	 * @return View
	 */
	private function registerView($base, File $file, Viewtype $viewtype) {
		$name = '';
		
		$base = trim($base, '/');
		if (!empty($base)) {
			$name .= "$base/";
		}
		
		$dirname = trim($file->getDirname(), "/");
		if (!empty($dirname)) {
			$name .= "$dirname/";
		}
		
		$name .= $file->getBasename('.php');
		
					
		return $this->getView($name)->setLocation($viewtype, $file);
	}
	
	/**
	 * @param string $name String ID for the viewtype.
	 * 
	 * @return Viewtype
	 */
	public function getOrCreateViewtype($name) {
		if (isset($this->viewtypes[$name])) {
			$viewtype = $this->viewtypes[$name];
		} else {
			$viewtype = Viewtype::create($name);
			$this->viewtypes[$name] = $viewtype;
		}

		return $viewtype;
	}
	
	
	/**
	 * @param string $name The viewtype to check for.
	 * 
	 * @return bool
	 */
	public function isRegisteredViewtype($name) {
		return isset($this->viewtypes[$name]);
	}
	
	/**
	 * @param Viewtype $viewtype
	 * 
	 * @access private
	 */
	public function registerViewtypeFallback(Viewtype $viewtype) {
		$default = $this->getOrCreateViewtype('default');

		$viewtype->setFallback($default);
	}

	/**
	 * Display a view with a deprecation notice. No missing view NOTICE is logged
	 *
	 * @see elgg_view()
	 *
	 * @param string  $view       The name and location of the view to use
	 * @param array   $vars       Variables to pass to the view
	 * @param string  $suggestion Suggestion with the deprecation message
	 * @param string  $version    Human-readable *release* version: 1.7, 1.8, ...
	 *
	 * @return string The parsed view
	 * @access private
	 */
	public function renderDeprecatedView($view, array $vars, $suggestion, $version) {
		$rendered = $this->renderView($view, $vars, false, '', false);
		if ($rendered) {
			elgg_deprecated_notice("The $view view has been deprecated. $suggestion", $version, 3);
		}
		return $rendered;
	}
	
	private function prepareVars(array $vars) {
		// @warning - plugin authors: do not expect user, config, and url to be
		// set by elgg_view() in the future. Instead, use elgg_get_logged_in_user_entity(),
		// elgg_get_config(), and elgg_get_site_url() in your views.
		if (!isset($vars['user'])) {
			$vars['user'] = $this->getUserWrapper();
		}
		
		if (!isset($vars['config'])) {
			$vars['config'] = $this->getConfigWrapper();
		}
		
		if (!isset($vars['url'])) {
			$vars['url'] = $this->getSiteUrlWrapper();
		}

		// full_view is the new preferred key for full view on entities @see elgg_view_entity()
		// check if full_view is set because that means we've already rewritten it and this is
		// coming from another view passing $vars directly.
		if (isset($vars['full']) && !isset($vars['full_view'])) {
			elgg_deprecated_notice("Use \$vars['full_view'] instead of \$vars['full']", 1.8, 2);
			$vars['full_view'] = $vars['full'];
		}
		if (isset($vars['full_view'])) {
			$vars['full'] = $vars['full_view'];
		}

		// internalname => name (1.8)
		if (isset($vars['internalname']) && !isset($vars['__ignoreInternalname']) && !isset($vars['name'])) {
			elgg_deprecated_notice('You should pass $vars[\'name\'] now instead of $vars[\'internalname\']', 1.8, 2);
			$vars['name'] = $vars['internalname'];
		} elseif (isset($vars['name'])) {
			if (!isset($vars['internalname'])) {
				$vars['__ignoreInternalname'] = '';
			}
			$vars['internalname'] = $vars['name'];
		}

		// internalid => id (1.8)
		if (isset($vars['internalid']) && !isset($vars['__ignoreInternalid']) && !isset($vars['name'])) {
			elgg_deprecated_notice('You should pass $vars[\'id\'] now instead of $vars[\'internalid\']', 1.8, 2);
			$vars['id'] = $vars['internalid'];
		} elseif (isset($vars['id'])) {
			if (!isset($vars['internalid'])) {
				$vars['__ignoreInternalid'] = '';
			}
			$vars['internalid'] = $vars['id'];
		}
		
		return $vars;
	}
	
	/**
	 * @param string $name
	 * 
	 * @return View
	 */
	public function getView($name) {
		if (!isset($this->views[$name])) {
			$this->views[$name] = new View();
		}
		
		return $this->views[$name];
	}
	
	/**
	 * @param string  $view
	 * @param array   $vars
	 * @param string  $viewtype
	 * @param boolean $bypass
	 * @param boolean $issue_missing_notice
	 * 
	 * @access private
	 */
	public function renderView($view, array $vars = array(), $viewtype = '', $bypass = false, $issue_missing_notice = true) {
		if (!is_string($view) || !is_string($viewtype)) {
			$this->logger->log("View and Viewtype in views must be a strings: $view", 'NOTICE');
			return '';
		}
		// basic checking for bad paths
		if (strpos($view, '..') !== false) {
			return '';
		}

		if (!is_array($vars)) {
			$this->logger->log("Vars in views must be an array: $view", 'ERROR');
			$vars = array();
		}
		
		if (empty($viewtype)) {
			$viewtype = $this->getCurrentViewtype();
		} else {
			$viewtype = $this->getOrCreateViewtype($viewtype);
		}

		// Trigger the pagesetup event
		if (!isset($this->config->pagesetupdone) && empty($this->config->boot_complete)) {
			$this->config->pagesetupdone = true;
			$this->events->trigger('pagesetup', 'system');
		}
		
		$vars = $this->prepareVars($vars);

		// If it's been requested, pass off to a template handler instead
		if (!$bypass && isset($this->template_handler)) {
			return call_user_func($this->template_handler, $view, $vars);
		}

		$content = $this->getView($view)->render($vars, $viewtype);
		
		// Plugin hook
		$params = array('view' => "$view", 'vars' => $vars, 'viewtype' => "$viewtype");
		$content = $this->hooks->trigger('view', "$view", $params, $content);

		// TODO: Remove backward compatibility with less granular hook in 2.0
		$content_tmp = $this->hooks->trigger('display', 'view', $params, $content);

		if ($content_tmp !== $content) {
			$content = $content_tmp;
			elgg_deprecated_notice('The display:view plugin hook is deprecated by view:view_name', 1.8);
		}

		return $content;
	}

	/**
	 * Configure a custom template handler besides renderView
	 * 
	 * @param string $function_name The custom callback for handling rendering.
	 * 
	 * @return boolean whether the template handler was accepted
	 */
	public function setTemplateHandler($function_name) {
		if (!is_callable($function_name)) {
			return false;
		}
		
		$this->template_handler = $function_name;
		return true;
	}
	
	/**
	 * Register a plugin's views
	 *
	 * @param Directory $dir Base path of the plugin
	 *
	 * @access private
	 */
	public function registerViewsDirectory(Directory $dir) {
		// plugins don't have to have views.
		if (!$dir->isDirectory('/')) {
			return;
		}
		
		// but if they do, they have to be readable
		$handle = opendir("$dir");
		if (!$handle) {
			throw new Exception\UnreadableDirectory("$dir");
		}
		
		// TODO(ewinslow): Add a directory method that returns shallow list of folders as a collection
		$view_type_names = [];
		while (($view_type_name = readdir($handle)) !== false) {
			// ignore private-directories and non-directories
			if (substr($view_type_name, 0, 1) !== '.' && !$dir->isDirectory($view_type_name)) {
				$view_types[] = $view_type_name;
			}
		}
		
		foreach ($view_type_names as $view_type_name) {
			try {
				$view_type_dir = $dir->chroot("/$view_type_name/");
				$views->registerViews('', $view_type_dir, $view_type_name);
			} catch (\Exception $e) {
				throw new Exception\UnreadableDirectory("$view_type_dir");
			}
		}
	}
	
	/**
	 * Get views overridden by setViewLocation() calls.
	 *
	 * @return array
	 *
	 * @access private
	 */
	public function getOverriddenLocations() {
		return $this->overriden_locations;
	}
	
	/**
	 * Set views overridden by setViewLocation() calls.
	 *
	 * @param array $locations
	 * @return void
	 *
	 * @access private
	 */
	public function setOverriddenLocations(array $locations) {
		$this->overriden_locations = $locations;
	}
}
