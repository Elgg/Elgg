<?php
/**
 * Elgg Menu Item
 *
 * To create a menu item that is not a link, pass false for $href.
 *
 * Any undocumented properties set will be passed to the output/url view during rendering. E.g.
 * to give a menu item a "target" attribute, set $item->target, or include a "target" key in
 * the options array for factory().
 *
 * @since 1.8.0
 */
class ElggMenuItem implements \Elgg\Collections\CollectionItemInterface {

	/**
	 * @var array Non-rendered data about the menu item
	 */
	protected $data = [
		// string Identifier of the menu
		'name' => '',

		// array Page contexts this menu item should appear on
		'contexts' => ['all'],

		// string Menu section identifier
		'section' => 'default',

		// int Smaller priorities float to the top
		'priority' => 100,

		// bool Is this the currently selected menu item (null for autodetection)
		'selected' => null,

		// string Identifier of this item's parent
		'parent_name' => '',

		// \ElggMenuItem The parent object or null
		'parent' => null,

		// array Array of children objects or empty array
		'children' => [],

		// array An array of options for child menu of the parent item
		'child_menu' => [],

		// array Classes to apply to the li tag
		'itemClass' => [],

		// array Classes to apply to the anchor tag
		'linkClass' => [],

		// array AMD modules required by this menu item
		'deps' => []
	];

	/**
	 * @var string The menu display string (HTML)
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
	 * \ElggMenuItem constructor
	 *
	 * @param string $name Identifier of the menu item
	 * @param string $text Display text of the menu item (HTML)
	 * @param string $href URL of the menu item (false if not a link)
	 */
	public function __construct($name, $text, $href) {
		$this->text = $text;
		if ($href) {
			$this->href = elgg_normalize_url($href);
		} else {
			$this->href = $href;
		}

		$this->data['name'] = $name;
	}

	/**
	 * Create an ElggMenuItem from an associative array. Required keys are name, text, and href.
	 *
	 * Commonly used params:
	 *    name        => STR  Menu item identifier (required)
	 *    text        => STR  Menu item display text as HTML (required)
	 *    href        => STR  Menu item URL (required)
	 *                        false = do not create a link.
	 *                        null = current URL.
	 *                        "" = current URL.
	 *                        "/" = site home page.
	 *                        @warning If href is false, the <a> tag will
	 *                        not appear, so the link_class will not apply. If you
	 *                        put <a> tags in manually through the 'text' option
	 *                        the default CSS selector .elgg-menu-$menu > li > a
	 *                        may affect formatting. Wrap in a <span> if it does.)
	 *
	 *    section     => STR  Menu section identifier
	 *    link_class  => STR  A class or classes for the <a> tag
	 *    item_class  => STR  A class or classes for the <li> tag
	 *    parent_name => STR  Identifier of the parent menu item
	 *    contexts    => ARR  Page context strings
	 *    title       => STR  Menu item tooltip
	 *    selected    => BOOL Is this menu item currently selected?
	 *    confirm     => STR  If set, the link will be drawn with the output/confirmlink view instead of output/url.
	 *    deps        => ARR  AMD modules required by this menu item
	 *    child_menu  => ARR  Options for the child menu
	 *    data        => ARR  Custom attributes stored in the menu item.
	 *
	 * @param array $options Option array of key value pairs
	 *
	 * @return ElggMenuItem|null null on error
	 */
	public static function factory($options) {
		if (!isset($options['name']) || !isset($options['text'])) {
			elgg_log(__METHOD__ . ': $options "name" and "text" are required.', 'ERROR');
			return null;
		}
		if (!isset($options['href'])) {
			$options['href'] = '';
		}

		$item = new \ElggMenuItem($options['name'], $options['text'], $options['href']);
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
			if (array_key_exists($key, $item->data)) {
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
			$this->data = array_merge($this->data, $key);
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
	 * @param string $text The display text as HTML
	 * @return void
	 */
	public function setText($text) {
		$this->text = $text;
	}

	/**
	 * Get the display text of the menu item
	 *
	 * @return string The display text as HTML
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
	 * @return string|false|null
	 */
	public function getHref() {
		return $this->href;
	}

	/**
	 * Set the contexts that this menu item is available for
	 *
	 * @param array $contexts An array of context strings. Use 'all' to match all contexts.
	 * @return void
	 */
	public function setContext($contexts) {
		if (is_string($contexts)) {
			$contexts = [$contexts];
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
		if (in_array('all', $this->data['contexts'])) {
			return true;
		}

		if ($context) {
			return in_array($context, $this->data['contexts']);
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
		if (isset($this->data['selected'])) {
			return $this->data['selected'];
		}

		return elgg_http_url_is_identical(current_page_url(), $this->getHref());
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
			$this->data['linkClass'] = [$class];
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
		$this->addClass($this->data['linkClass'], $class);
	}

	/**
	 * Set required AMD modules
	 *
	 * @param string[]|string $modules One or more required AMD modules
	 * @return void
	 */
	public function setDeps($modules) {
		$this->data['deps'] = (array) $modules;
	}

	/**
	 * Get required AMD modules
	 *
	 * @return string[]
	 */
	public function getDeps() {
		$modules = (array) $this->data['deps'];
		return array_filter($modules, function($m) {
			return is_string($m) && !empty($m);
		});
	}

	/**
	 * Add required AMD modules
	 *
	 * @param string[]|string $modules One or more required AMD modules
	 * @return void
	 */
	public function addDeps($modules) {
		$current = $this->getDeps();
		$this->setDeps($current + (array) $modules);
	}

	/**
	 * Set child menu options for a parent item
	 *
	 * @param array $options Options
	 * @return void
	 */
	public function setChildMenuOptions(array $options = []) {
		$this->data['child_menu'] = $options;
	}

	/**
	 * Returns child menu options for parent items
	 *
	 * @return array
	 */
	public function getChildMenuOptions() {
		return $this->data['child_menu'];
	}

	/**
	 * Set the li classes
	 *
	 * @param mixed $class An array of class names, or a single string class name.
	 * @return void
	 */
	public function setItemClass($class) {
		if (!is_array($class)) {
			$this->data['itemClass'] = [$class];
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
		$name = preg_replace('/[^a-z0-9\-]/i', '-', strtolower($this->getName()));
		
		$class = implode(' ', $this->data['itemClass']);
		if ($class) {
			return "elgg-menu-item-$name $class";
		} else {
			return "elgg-menu-item-$name";
		}
	}

	/**
	 * Add a li class
	 *
	 * @param mixed $class An array of class names, or a single string class name.
	 * @return void
	 * @since 1.9.0
	 */
	public function addItemClass($class) {
		$this->addClass($this->data['itemClass'], $class);
	}

	// @codingStandardsIgnoreStart
	/**
	 * Add additional classes
	 *
	 * @param array $current    The current array of classes
	 * @param mixed $additional Additional classes (either array of string)
	 * @return void
	 */
	protected function addClass(array &$current, $additional) {
		if (!is_array($additional)) {
			$current[] = $additional;
		} else {
			$current = array_merge($current, $additional);
		}
	}
	// @codingStandardsIgnoreEnd

	/**
	 * Set the priority of the menu item
	 *
	 * @param int $priority The smaller numbers mean higher priority (1 before 100)
	 * @return void
	 */
	public function setPriority(int $priority) {
		$this->data['priority'] = $priority;
	}

	/**
	 * Get the priority of the menu item
	 *
	 * @return int
	 */
	public function getPriority() {
		return (int) $this->data['priority'];
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
	 * @param string $name The identifier of the parent \ElggMenuItem
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
	 * @param \ElggMenuItem $parent The parent of this menu item
	 * @return void
	 *
	 * @internal This is reserved for the \ElggMenuBuilder
	 */
	public function setParent($parent) {
		$this->data['parent'] = $parent;
	}

	/**
	 * Get the parent menu item
	 *
	 * @return \ElggMenuItem or null
	 *
	 * @internal This is reserved for the \ElggMenuBuilder
	 */
	public function getParent() {
		return $this->data['parent'];
	}

	/**
	 * Add a child menu item
	 *
	 * @param \ElggMenuItem $item A child menu item
	 * @return void
	 *
	 * @internal This is reserved for the \ElggMenuBuilder
	 */
	public function addChild($item) {
		$this->data['children'][] = $item;
	}

	/**
	 * Set the menu item's children
	 *
	 * @param ElggMenuItem[] $children Array of items
	 * @return void
	 *
	 * @internal This is reserved for the \ElggMenuBuilder
	 */
	public function setChildren($children) {
		$this->data['children'] = $children;
	}

	/**
	 * Get the children menu items
	 *
	 * @return ElggMenuItem[]
	 *
	 * @internal This is reserved for the \ElggMenuBuilder
	 */
	public function getChildren() {
		return $this->data['children'];
	}

	/**
	 * Sort the children
	 *
	 * @param callable $sortFunction A function that is passed to usort()
	 *
	 * @return void
	 *
	 * @internal This is reserved for the \ElggMenuBuilder
	 */
	public function sortChildren($sortFunction) {
		foreach ($this->data['children'] as $key => $node) {
			$node->data['original_order'] = $key;
			$node->sortChildren($sortFunction);
		}
		usort($this->data['children'], $sortFunction);
	}

	/**
	 * Get all the values for this menu item. Useful for rendering.
	 *
	 * @return array
	 * @since 1.9.0
	 */
	public function getValues() {
		$values = get_object_vars($this);
		unset($values['data']);

		return $values;
	}

	/**
	 * Get unique item identifier within a collection
	 * @return string|int
	 */
	public function getID() {
		return $this->getName();
	}
}
