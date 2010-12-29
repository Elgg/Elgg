<?php
/**
 * Elgg Menu Item
 *
 * @package    Elgg.Core
 * @subpackage Navigation
 *
 * @since 1.8.0
 */
class ElggMenuItem {
	/**
	 * @var string Identifier of the menu
	 */
	protected $name;

	/**
	 * @var string The menu display string
	 */
	protected $title;

	/**
	 * @var string The menu url
	 */
	protected $url = null;

	/**
	 * @var array Page context array
	 */
	protected $contexts = array('all');

	/**
	 * @var string Menu section identifier
	 */
	protected $section = 'default';

	/**
	 * @var string Tooltip
	 */
	protected $tooltip = '';

	/**
	 * @var bool Is this the currently selected menu item
	 */
	protected $selected = false;

	/**
	 * @var string Identifier of this item's parent
	 */
	 protected $parent_name = '';

	 /**
	  * @var ElggMenuItem The parent object or null
	  */
	 protected $parent = null;

	 /**
	  * @var array Array of children objects or empty array
	  */
	 protected $children = array();

	/**
	 * ElggMenuItem constructor
	 *
	 * @param string $name  Identifier of the menu item
	 * @param string $title Title of the menu item
	 * @param string $url   URL of the menu item
	 */
	public function __construct($name, $title, $url) {
		$this->name = $name;
		$this->title = $title;
		if ($url) {
			$this->url = elgg_normalize_url($url);
		}
	}

	/**
	 * ElggMenuItem factory method
	 *
	 * This static method creates an ElggMenuItem from an associative array.
	 * Required keys are name, title, and url.
	 *
	 * @param array $options Option array of key value pairs
	 *
	 * @return ElggMenuItem or NULL on error
	 */
	public static function factory($options) {
		if (!isset($options['name']) || !isset($options['title'])) {
			return NULL;
		}

		$item = new ElggMenuItem($options['name'], $options['title'], $options['url']);
		unset($options['name']);
		unset($options['title']);
		unset($options['url']);

		// special catch in case someone uses context rather than contexts
		if (isset($options['context'])) {
			$options['contexts'] = $options['context'];
			unset($options['context']);
		}

		foreach ($options as $key => $value) {
			$item->$key = $value;
		}

		// make sure contexts is set correctly
		if (isset($options['contexts'])) {
			$item->setContext($options['contexts']);
		}

		return $item;
	}

	/**
	 * Get the identifier of the menu item
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get the display title of the menu
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Get the URL of the menu item
	 *
	 * @return string
	 */
	public function getURL() {
		return $this->url;
	}

	/**
	 * Set the contexts that this menu item is available for
	 *
	 * @param array $contexts An array of context strings
	 *
	 * @return void
	 */
	public function setContext($contexts) {
		if (is_string($contexts)) {
			$contexts = array($contexts);
		}
		$this->contexts = $contexts;
	}

	/**
	 * Get an array of context strings
	 *
	 * @return array
	 */
	public function getContext() {
		return $this->contexts;
	}

	/**
	 * Should this menu item be used given the current context
	 *
	 * @param string $context A context string (default is empty string for
	 *                        current context stack.
	 *
	 * @return bool
	 */
	public function inContext($context = '') {
		if ($context) {
			return in_array($context, $this->contexts);
		}

		if (in_array('all', $this->contexts)) {
			return true;
		}

		foreach ($this->contexts as $context) {
			if (elgg_in_context($context)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Set the selected flag
	 *
	 * @param bool $state Selected state (default is true)
	 *
	 * @return void
	 */
	public function setSelected($state = true) {
		$this->selected = $state;
	}

	/**
	 * Get selected state
	 *
	 * @return bool
	 */
	public function getSelected() {
		return $this->selected;
	}

	/**
	 * Set the tool tip text
	 *
	 * @param string $text The text of the tool tip
	 *
	 * @return void
	 */
	public function setTooltip($text) {
		$this->tooltip = $text;
	}

	/**
	 * Get the tool tip text
	 *
	 * @return string
	 */
	public function getTooltip() {
		return $this->tooltip;
	}

	/**
	 * Set the section identifier
	 *
	 * @param string $section The identifier of the section
	 *
	 * @return void
	 */
	public function setSection($section) {
		$this->section = $section;
	}

	/**
	 * Get the section identifier
	 *
	 * @return string
	 */
	public function getSection() {
		return $this->section;
	}

	/**
	 * Set the parent identifier
	 *
	 * @param string $parent_name The identifier of the parent ElggMenuItem
	 *
	 * @return void
	 */
	public function setParentName($parent_name) {
		$this->parent_name = $parent_name;
	}

	/**
	 * Get the parent identifier
	 *
	 * @return string
	 */
	public function getParentName() {
		return $this->parent_name;
	}

	/**
	 * Set the parent menu item
	 *
	 * @param ElggMenuItem $parent
	 *
	 * @return void
	 */
	public function setParent($parent) {
		$this->parent = $parent;
	}

	/**
	 * Get the parent menu item
	 *
	 * @return ElggMenuItem or null
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * Add a child menu item
	 *
	 * @param ElggMenuItem $item
	 *
	 * @return void
	 */
	public function addChild($item) {
		$this->children[] = $item;
	}

	/**
	 * Get the children menu items
	 *
	 * @return array
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * Sort the children
	 *
	 * @param string $sort_function
	 *
	 * @return void
	 */
	public function sortChildren($sort_function) {
		usort($this->children, $sort_function);
	}

	/**
	 * Get the menu link
	 *
	 * @params array $vars Options to pass to output/url
	 *
	 * @return string
	 */
	public function getLink(array $vars = array()) {
		$vars['text'] = $this->title;
		if ($this->url) {
			$vars['href'] = $this->url;
		}

		return elgg_view('output/url', $vars);
	}
}
