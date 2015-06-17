<?php
namespace Elgg\Views;

use Elgg\EventsService as Events;
use Elgg\Logger;
use Elgg\PluginHooksService as Hooks;


/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @since 2.0.0
 * @access private
 */
class ViewRegistry {
	/** @var Hooks */
	private $hooks;
	
	/** @var Events */
	private $events;
	
	/** @var Logger */
	private $logger;
	
	/** @var \stdClass */
	private $config;
	
	/** @var ViewtypeRegistry */
	private $viewtypes;
	
	/** @var View[] */
	private $views = [];

	/** @var PathRegistry */
	private $viewFiles;

	/** @var callable */
	private $template_handler = null;

	/**
	 * Constructor
	 *
	 * @param \stdClass        $config    The global Elgg config
	 * @param Events           $events    The events service
	 * @param Hooks            $hooks     The hooks service
	 * @param Logger           $logger    Logger
	 * @param ViewtypeRegistry $viewtypes List of available viewtypes
	 * @param PathRegistry     $viewFiles Global registry of view locations
	 */
	public function __construct(
			\stdClass $config,
			Events $events,
			Hooks $hooks,
			Logger $logger,
			ViewtypeRegistry $viewtypes,
			PathRegistry $viewFiles) {
		$this->config = $config;
		$this->events = $events;
		$this->hooks = $hooks;
		$this->logger = $logger;
		$this->viewtypes = $viewtypes;
		$this->viewFiles = $viewFiles;
	}

	/**
	 * Display a view with a deprecation notice. No missing view NOTICE is logged
	 *
	 * @see elgg_view()
	 *
	 * @param string $view       The name and location of the view to use
	 * @param array  $vars       Variables to pass to the view
	 * @param string $suggestion Suggestion with the deprecation message
	 * @param string $version    Human-readable *release* version: 1.7, 1.8, ...
	 *
	 * @return string The parsed view
	 */
	public function renderDeprecated($view, array $vars, $suggestion, $version) {
		$rendered = $this->render($view, $vars, false, '');
		if ($rendered) {
			elgg_deprecated_notice("The $view view has been deprecated. $suggestion", $version, 3);
		}
		return $rendered;
	}
	
	/** @inheritDoc */
	public function get(/*string*/ $name) {
		if (!isset($this->views[$name])) {
			$this->views[$name] = new View($name, $this->viewFiles->forView($name));
		}
		
		return $this->views[$name];
	}
	
	/**
	 * Returns the output of the given view with the $vars applied.
	 * 
	 * @param string  $view     The name of the view to render
	 * @param array   $vars     Arbitrary arguments to send to the view
	 * @param string  $viewtype The view format
	 * @param boolean $bypass   Whether to force use of the default template handler
	 *
	 * @return string
	 */
	public function render(/*string*/ $view, array $vars = array(), $viewtype = '', $bypass = false) {
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
			$viewtype = $this->viewtypes->getCurrent();
		} else {
			$viewtype = $this->viewtypes->get($viewtype);
		}

		// Trigger the pagesetup event, but not before engine boot is done
		if (!isset($this->config->pagesetupdone) && !empty($this->config->boot_complete)) {
			$this->config->pagesetupdone = true;
			$this->events->trigger('pagesetup', 'system');
		}
		
		// If it's been requested, pass off to a template handler instead
		if (!$bypass && isset($this->template_handler)) {
			return call_user_func($this->template_handler, $view, $vars);
		}

		$content = $this->get($view)->render($vars, $viewtype);
		
		$params = array('view' => "$view", 'vars' => $vars, 'viewtype' => $viewtype->getName());
		return $this->hooks->trigger('view', "$view", $params, $content);
	}

	/**
	 * Configures a custom template handler besides render
	 * 
	 * @param string $function_name The custom callback for handling rendering.
	 * 
	 * @return boolean whether the template handler was accepted
	 */
	public function setTemplateHandler(/*string*/ $function_name) {
		if (!is_callable($function_name)) {
			return false;
		}
		
		$this->template_handler = $function_name;
		return true;
	}
}
