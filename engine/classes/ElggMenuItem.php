<?php
/**
 * Elgg Menu Item
 *
 * @package    Elgg.Core
 * @subpackage Navigation
 *
 * To create a menu item that is not a link, pass false for $href.
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
	protected $text;

	/**
	 * @var string The menu url
	 */
	protected $href = null;

	/**
	 * @var string The string to display if link is clicked
	 */
	protected $confirm = '';

	/**
	 * @var array Classes to apply to the anchor tag.
	 */
	protected $linkClass = array();

	/**
	 * @var array Classes to apply to the li tag.
	 */
	protected $itemClass = array();

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
	protected $title = '';

	/**
	 * @var int Menu priority - smaller prioritys float to the top
	 */
	protected $priority = 100;

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
	 * @param string $text  Display text of the menu item
	 * @param string $href  URL of the menu item (false if not a link)
	 */
	public function __construct($name, $text, $href) {
		$this->name = $name;
		$this->text = $text;
		if ($href) {
			$this->href = elgg_normalize_url($href);
		} else {
			$this->href = $href;
		}
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

		$item = new ElggMenuItem($options['name'], $options['text'], $options['href']);
		unset($options['name']);
		unset($options['text']);
		unset($options['href']);

		// special catch in case someone uses context rather than contexts
		if (isset($options['context'])) {
			$options['contexts'] = $options['context'];
			unset($options['context']);
		}

		if (isset($options['link_class'])) {
			$item->setLinkClass($options['link_class']);
			unset($options['link_class']);
		}

		if (isset($options['item_class'])) {
			$item->setItemClass($options['item_class']);
			unset($options['item_class']);
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
	 * Set the display text of the menu item
	 * 
	 * @param string $text The display text
	 * 
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
	 *
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
	 *
	 * @return void
	 */
	public function setLinkClass($class) {
		if (!is_array($class)) {
			$this->linkClass[] = $class;
		} else {
			$this->linkClass = $class;
		}
	}

	/**
	 * Get the anchor classes as text
	 *
	 * @return string
	 */
	public function getLinkClass() {
		return implode(' ', $this->linkClass);
	}

	/**
	 * Set the li classes
	 *
	 * @param mixed $class An array of class names, or a single string class name.
	 *
	 * @return void
	 */
	public function setItemClass($class) {
		if (!is_array($class)) {
			$this->itemClass[] = $class;
		} else {
			$this->itemClass = $class;
		}
	}

	/**
	 * Get the li classes as text
	 *
	 * @return string
	 */
	public function getItemClass() {
		//allow people to specify name with underscores and colons
		$name = str_replace('_', '-', $this->getName());
		$name = str_replace(':', '-', $name);

		$class = implode(' ', $this->itemClass);
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
	 *
	 * @return void
	 */
	public function setWeight($priority) {
		$this->priority = $priority;
	}

	/**
	 * Get the priority of the menu item
	 *
	 * @return int
	 */
	public function getWeight() {
		return $this->priority;
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
	 * Set the menu item's children
	 *
	 * @param array $children Array of ElggMenuItems
	 *
	 * @return void
	 */
	public function setChildren($children) {
		$this->children = $children;
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
	 * Get the menu item content (usually a link)
	 *
	 * @params array $vars Options to pass to output/url if a link
	 *
	 * @return string
	 *
	 * @todo View code in a model.  How do we feel about that?
	 */
	public function getContent(array $vars = array()) {

		if ($this->href === false) {
			return $this->text;
		}

		$vars['text'] = $this->text;

		if ($this->href) {
			$vars['href'] = $this->href;
		}

		if ($this->linkClass) {
			$vars['class'] = $this->getLinkClass();
		}

		if ($this->rel) {
			$vars['rel'] = $this->rel;
		}

		if ($this->title) {
			$vars['title'] = $this->title;
		}

		if ($this->is_action) {
			$vars['is_action'] = $this->is_action;
		}

		if ($this->confirm) {
			$vars['confirm'] = $this->confirm;
			return elgg_view('output/confirmlink', $vars);
		}

		return elgg_view('output/url', $vars);
	}
}
