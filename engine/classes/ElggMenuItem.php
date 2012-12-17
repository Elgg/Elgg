<?php
/**
 * Elgg Menu Item
 *
 * To create a menu item that is not a link, pass false for $href.
 *
 * @package    Elgg.Core
 * @subpackage Navigation
 * @since      1.8.0
 */
class ElggMenuItem {

	/**
	 * @var array Non-rendered data about the menu item
	 */
	protected $data = array(
		// string Identifier of the menu
		'name' => '',

		// array Page contexts this menu item should appear on
		'contexts' => array('all'),

		// string Menu section identifier
		'section' => 'default',

		// int Smaller priorities float to the top
		'priority' => 100,

		// bool Is this the currently selected menu item
		'selected' => false,

		// string Identifier of this item's parent
		'parent_name' => '',

		// ElggMenuItem The parent object or null
		'parent' => null,

		// array Array of children objects or empty array
		'children' => array(),

		// array Classes to apply to the li tag
		'itemClass' => array(),

		// array Classes to apply to the anchor tag
		'linkClass' => array(),
	);

	/**
	 * @var string The menu display string
	 */
	protected $text;

	/**
	 * @var string The menu url
	 */
	protected $href = null;

	/**
	 * @var string Tooltip
	 */
	protected $title = false;

	/**
	 * @var string The string to display if link is clicked
	 */
	protected $confirm = '';


	/**
	 * ElggMenuItem constructor
	 *
	 * @param string $name Identifier of the menu item
	 * @param string $text Display text of the menu item
	 * @param string $href URL of the menu item (false if not a link)
	 */
	public function __construct($name, $text, $href) {
		//$this->name = $name;
		$this->text = $text;
		if ($href) {
			$this->href = elgg_normalize_url($href);
		} else {
			$this->href = $href;
		}

		$this->data['name'] = $name;
	}

	/**
	 * ElggMenuItem factory method
	 *
	 * This static method creates an ElggMenuItem from an associative array.
	 * Required keys are name, text, and href.
	 *
	 * @param array $options Option array of key value pairs
	 *
	 * @return ElggMenuItem or NULL on error
	 */
	public static function factory($options) {
		if (!isset($options['name']) || !isset($options['text'])) {
			return NULL;
		}
		if (!isset($options['href'])) {
			$options['href'] = '';
		}

		$item = new ElggMenuItem($options['name'], $options['text'], $options['href']);
		unset($options['name']);
		unset($options['text']);
		unset($options['href']);

		// special catch in case someone uses context rather than contexts
		if (isset($options['context'])) {
			$options['contexts'] = $options['context'];
			unset($options['context']);
		}
		
		// make sure contexts is set correctly
		if (isset($options['contexts'])) {
			$item->setContext($options['contexts']);
			unset($options['contexts']);
		}

		if (isset($options['link_class'])) {
			$item->setLinkClass($options['link_class']);
			unset($options['link_class']);
		}

		if (isset($options['item_class'])) {
			$item->setItemClass($options['item_class']);
			unset($options['item_class']);
		}

		if (isset($options['data']) && is_array($options['data'])) {
			$item->setData($options['data']);
			unset($options['data']);
		}
		
		foreach ($options as $key => $value) {
			if (isset($item->data[$key])) {
				$item->data[$key] = $value;
			} else {
				$item->$key = $value;
			}
		}

		return $item;
	}

	/**
	 * Set a data key/value pair or a set of key/value pairs
	 *
	 * This method allows storage of arbitrary data with this menu item. The
	 * data can be used for sorting, custom rendering, or any other use.
	 *
	 * @param mixed $key   String key or an associative array of key/value pairs
	 * @param mixed $value The value if $key is a string
	 * @return void
	 */
	public function setData($key, $value = null) {
		if (is_array($key)) {
			$this->data += $key;
		} else {
			$this->data[$key] = $value;
		}
	}

	/**
	 * Get stored data
	 *
	 * @param string $key The key for the requested key/value pair
	 * @return mixed
	 */
	public function getData($key) {
		if (isset($this->data[$key])) {
			return $this->data[$key];
		} else {
			return null;
		}
	}

	/**
	 * Set the identifier of the menu item
	 *
	 * @param string $name Unique identifier
	 * @return void
	 */
	public function setName($name) {
		$this->data['name'] = $name;
	}

	/**
	 * Get the identifier of the menu item
	 *
	 * @return string
	 */
	public function getName() {
		return $this->data['name'];
	}

	/**
	 * Set the display text of the menu item
	 * 
	 * @param string $text The display text
	 * @return void
	 */
	public function setText($text) {
		$this->text = $text;
	}

	/**
	 * Get the display text of the menu item
	 *
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * Set the URL of the menu item
	 *
	 * @param string $href URL or false if not a link
	 * @return void
	 */
	public function setHref($href) {
		$this->href = $href;
	}

	/**
	 * Get the URL of the menu item
	 *
	 * @return string
	 */
	public function getHref() {
		return $this->href;
	}

	/**
	 * Set the contexts that this menu item is available for
	 *
	 * @param array $contexts An array of context strings
	 * @return void
	 */
	public function setContext($contexts) {
		if (is_string($contexts)) {
			$contexts = array($contexts);
		}
		$this->data['contexts'] = $contexts;
	}

	/**
	 * Get an array of context strings
	 *
	 * @return array
	 */
	public function getContext() {
		return $this->data['contexts'];
	}

	/**
	 * Should this menu item be used given the current context
	 *
	 * @param string $context A context string (default is empty string for
	 *                        current context stack).
	 * @return bool
	 */
	public function inContext($context = '') {
		if ($context) {
			return in_array($context, $this->data['contexts']);
		}

		if (in_array('all', $this->data['contexts'])) {
			return true;
		}

		foreach ($this->data['contexts'] as $context) {
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
	 * @return void
	 */
	public function setSelected($state = true) {
		$this->data['selected'] = $state;
	}

	/**
	 * Get selected state
	 *
	 * @return bool
	 */
	public function getSelected() {
		return $this->data['selected'];
	}

	/**
	 * Set the tool tip text
	 *
	 * @param string $text The text of the tool tip
	 * @return void
	 */
	public function setTooltip($text) {
		$this->title = $text;
	}

	/**
	 * Get the tool tip text
	 *
	 * @return string
	 */
	public function getTooltip() {
		return $this->title;
	}

	/**
	 * Set the confirm text shown when link is clicked
	 *
	 * @param string $text The text to show
	 * @return void
	 */
	public function setConfirmText($text) {
		$this->confirm = $text;
	}

	/**
	 * Get the confirm text
	 *
	 * @return string
	 */
	public function getConfirmText() {
		return $this->confirm;
	}

	/**
	 * Set the anchor class
	 *
	 * @param mixed $class An array of class names, or a single string class name.
	 * @return void
	 */
	public function setLinkClass($class) {
		if (!is_array($class)) {
			$this->data['linkClass'] = array($class);
		} else {
			$this->data['linkClass'] = $class;
		}
	}

	/**
	 * Get the anchor classes as text
	 *
	 * @return string
	 */
	public function getLinkClass() {
		return implode(' ', $this->data['linkClass']);
	}

	/**
	 * Add a link class
	 *
	 * @param mixed $class An array of class names, or a single string class name.
	 * @return void
	 */
	public function addLinkClass($class) {
		if (!is_array($class)) {
			$this->data['linkClass'][] = $class;
		} else {
			$this->data['linkClass'] += $class;
		}
	}

	/**
	 * Set the li classes
	 *
	 * @param mixed $class An array of class names, or a single string class name.
	 * @return void
	 */
	public function setItemClass($class) {
		if (!is_array($class)) {
			$this->data['itemClass'] = array($class);
		} else {
			$this->data['itemClass'] = $class;
		}
	}

	/**
	 * Get the li classes as text
	 *
	 * @return string
	 */
	public function getItemClass() {
		// allow people to specify name with underscores and colons
		$name = strtolower($this->getName());
		$name = str_replace('_', '-', $name);
		$name = str_replace(':', '-', $name);
		$name = str_replace(' ', '-', $name);

		$class = implode(' ', $this->data['itemClass']);
		if ($class) {
			return "elgg-menu-item-$name $class";
		} else {
			return "elgg-menu-item-$name";
		}
	}

	/**
	 * Set the priority of the menu item
	 *
	 * @param int $priority The smaller numbers mean higher priority (1 before 100)
	 * @return void
	 * @deprecated
	 */
	public function setWeight($priority) {
		$this->data['priority'] = $priority;
	}

	/**
	 * Get the priority of the menu item
	 *
	 * @return int
	 * @deprecated
	 */
	public function getWeight() {
		return $this->data['priority'];
	}

	/**
	 * Set the priority of the menu item
	 *
	 * @param int $priority The smaller numbers mean higher priority (1 before 100)
	 * @return void
	 */
	public function setPriority($priority) {
		$this->data['priority'] = $priority;
	}

	/**
	 * Get the priority of the menu item
	 *
	 * @return int
	 */
	public function getPriority() {
		return $this->data['priority'];
	}

	/**
	 * Set the section identifier
	 *
	 * @param string $section The identifier of the section
	 * @return void
	 */
	public function setSection($section) {
		$this->data['section'] = $section;
	}

	/**
	 * Get the section identifier
	 *
	 * @return string
	 */
	public function getSection() {
		return $this->data['section'];
	}

	/**
	 * Set the parent identifier
	 *
	 * @param string $name The identifier of the parent ElggMenuItem
	 * @return void
	 */
	public function setParentName($name) {
		$this->data['parent_name'] = $name;
	}

	/**
	 * Get the parent identifier
	 *
	 * @return string
	 */
	public function getParentName() {
		return $this->data['parent_name'];
	}

	/**
	 * Set the parent menu item
	 *
	 * @param ElggMenuItem $parent The parent of this menu item
	 * @return void
	 */
	public function setParent($parent) {
		$this->data['parent'] = $parent;
	}

	/**
	 * Get the parent menu item
	 *
	 * @return ElggMenuItem or null
	 */
	public function getParent() {
		return $this->data['parent'];
	}

	/**
	 * Add a child menu item
	 *
	 * @param ElggMenuItem $item A child menu item
	 * @return void
	 */
	public function addChild($item) {
		$this->data['children'][] = $item;
	}

	/**
	 * Set the menu item's children
	 *
	 * @param array $children Array of ElggMenuItems
	 * @return void
	 */
	public function setChildren($children) {
		$this->data['children'] = $children;
	}

	/**
	 * Get the children menu items
	 *
	 * @return array
	 */
	public function getChildren() {
		return $this->data['children'];
	}

	/**
	 * Sort the children
	 *
	 * @param string $sortFunction A function that is passed to usort()
	 * @return void
	 */
	public function sortChildren($sortFunction) {
		foreach ($this->data['children'] as $key => $node) {
			$this->data['children'][$key]->data['original_order'] = $key;
		}
		usort($this->data['children'], $sortFunction);
	}

	/**
	 * Get the menu item content (usually a link)
	 *
	 * @param array $vars Options to pass to output/url if a link
	 * @return string
	 * @todo View code in a model.  How do we feel about that?
	 */
	public function getContent(array $vars = array()) {

		if ($this->href === false) {
			return $this->text;
		}

		$defaults = get_object_vars($this);
		unset($defaults['data']);

		$vars += $defaults;

		if ($this->data['linkClass']) {
			if (isset($vars['class'])) {
				$vars['class'] = $vars['class'] . ' ' . $this->getLinkClass();
			} else {
				$vars['class'] = $this->getLinkClass();
			}
		}

		if (!isset($vars['rel']) && !isset($vars['is_trusted'])) {
			$vars['is_trusted'] = true;
		}

		if ($this->confirm) {
			$vars['confirm'] = $this->confirm;
			return elgg_view('output/confirmlink', $vars);
		} else {
			unset($vars['confirm']);
		}

		return elgg_view('output/url', $vars);
	}
}
