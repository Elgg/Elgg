<?php

namespace Elgg\Markup;

use Elgg\Loggable;
use Psr\Log\LogLevel;

/**
 * Represents an HTML tag
 *
 * @property bool   $contenteditable
 * @property bool   $hidden
 * @property string $id
 * @property string $title
 * @property int    $tabindex
 */
abstract class Tag implements Element {

	use Loggable;

	/**
	 * @var string
	 */
	protected $tag_name = 'div';

	/**
	 * @var Element[]
	 */
	protected $children = [];

	/**
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * Constructor
	 *
	 * @param Element[]|Element|string $children   Element children
	 * @param array                    $attributes Element attributes
	 */
	public function __construct($children = null, array $attributes = []) {
		$this->attributes = $attributes;

		if (isset($children)) {
			$this->append($children);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public static function __callStatic($name, $arguments) {
		$tag = new static($arguments[0] ? : null, $arguments[1] ? : []);
		$tag->setTagName($name);

		return $tag;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __set($name, $value) {
		$this->setAttribute($name, $value);
	}

	/**
	 * Set an attribute
	 *
	 * @param string $name  Attribute name
	 * @param mixed  $value Attribute value
	 *
	 * @return static
	 */
	public function setAttribute($name, $value) {
		$this->attributes[$name] = $value;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __get($name) {
		return $this->getAttribute($name);
	}

	/**
	 * Get an attribute
	 *
	 * @param string $name    Attribute name
	 * @param null   $default Default value if none set
	 *
	 * @return mixed
	 */
	public function getAttribute($name, $default = null) {
		return elgg_extract($name, $this->attributes, $default);
	}

	/**
	 * Get element attributes
	 * @return array
	 */
	public function getAttributes() {
		return $this->attributes;
	}

	/**
	 * Remove an attribute
	 *
	 * @param string $name Attribute name
	 *
	 * @return static
	 */
	public function removeAttribute($name) {
		unset($this->attributes[$name]);

		return $this;
	}

	/**
	 * Add CSS style
	 *
	 * @param string $name  CSS property name
	 * @param string $value CSS property value
	 *
	 * @return static
	 */
	public function addStyle($name, $value) {
		if (!isset($this->attributes['style'])) {
			$this->attributes['style'] = [];
		}

		$this->attributes['style'][] = "{$name}: {$value}";

		return $this;
	}

	/**
	 * Set element's tag name
	 *
	 * @param string $tag_name Tag name, e.g. 'div' or 'table'
	 *
	 * @return static
	 */
	public function setTagName($tag_name) {
		if (!is_string($tag_name) || $tag_name === '') {
			throw new \InvalidArgumentException(__METHOD__ . ' expects a valid tags name');
		}

		$this->tag_name = $tag_name;

		return $this;
	}

	/**
	 * Get element's tag name
	 * @return string
	 */
	public function getTagName() {
		return $this->tag_name;
	}

	/**
	 * Flatten nested arrays
	 *
	 * @param array $elements Elements
	 *
	 * @return array
	 */
	protected function flatten($elements) {
		$flat = [];

		foreach ($elements as $element) {
			if (is_array($element)) {
				$flat = array_merge($flat, $this->flatten($element));
			} else {
				$flat[] = $element;
			}
		}

		return $flat;
	}

	/**
	 * Remove all children
	 * @return static
	 */
	public function clear() {
		$this->children = [];

		return $this;
	}

	/**
	 * Add elements at the end of the current inner stack
	 *
	 * @param Element[]|Element|string ...$children Elements to append
	 *
	 * @return static
	 */
	public function append(...$children) {
		$children = $this->flatten($children);

		foreach ($children as $child) {
			if (is_scalar($child)) {
				$child = new Text($child);
			}

			if ($child instanceof Element) {
				$this->children[] = $child;
			} else {
				$this->log(
					LogLevel::WARNING,
					'Children passed to ' . __METHOD__ . ' should only contain instances of ' . Element::class
				);
			}
		}

		return $this;
	}

	/**
	 * Add elements at the beginning of the current inner stack
	 *
	 * @param Element[]|Element|string ...$children Elements to append
	 *
	 * @return static
	 */
	public function prepend(...$children) {
		$children = $this->flatten($children);

		foreach ($children as $child) {
			if (is_scalar($child)) {
				$child = new Text($child);
			}

			if ($child instanceof Element) {
				array_unshift($this->children, $child);
			} else {
				$this->log(
					LogLevel::WARNING,
					'Children passed to ' . __METHOD__ . ' should only contain instances of ' . Element::class
				);
			}
		}

		return $this;
	}

	/**
	 * Add an Elgg view as an element as a last child
	 *
	 * @param string $view     View name
	 * @param array  $vars     View vars
	 * @param string $viewtype Viewtype
	 *
	 * @return static
	 */
	public function appendView($view, array $vars = [], $viewtype = '') {
		$node = new View($view, $vars, $viewtype);

		$this->append($node);

		return $this;
	}

	/**
	 * Add an Elgg view as an element as a first child
	 *
	 * @param string $view     View name
	 * @param array  $vars     View vars
	 * @param string $viewtype Viewtype
	 *
	 * @return static
	 */
	public function prependView($view, array $vars = [], $viewtype = '') {
		$node = new View($view, $vars, $viewtype);

		$this->append($node);

		return $this;
	}

	/**
	 * Add one or more CSS classes
	 *
	 * @param array ...$classes CSS classes
	 *
	 * @return static
	 */
	public function addClass(...$classes) {
		foreach ($classes as $class) {
			$this->attributes['class'] = elgg_extract_class($this->attributes, $class);
		}

		return $this;
	}

	/**
	 * Get child elements
	 * @return Element[]
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * Wrap tag with another tag
	 *
	 * @param string $class Tag element class name
	 *                      Must extend \Elgg\Markup\Tag
	 * @param array  $attrs Attributes
	 *
	 * @return Tag
	 */
	public function wrap($class, array $attrs = []) {
		if (!is_subclass_of($class, Tag::class)) {
			throw new \InvalidArgumentException($class . ' must exted ' . Tag::class);
		}

		return new $class($this, $attrs);
	}

	/**
	 * Wrap children with another tag
	 *
	 * @param string $class Tag element class name
	 *                      Must extend \Elgg\Markup\Tag
	 * @param array  $attrs Attributes
	 *
	 * @return static
	 */
	public function wrapChildren($class, array $attrs = []) {
		if (!is_subclass_of($class, Tag::class)) {
			throw new \InvalidArgumentException($class . ' must exted ' . Tag::class);
		}

		$child = new $class($this->getChildren(), $attrs);

		$this->clear();
		$this->append($child);

		return $this;
	}

	/**
	 * Render an element
	 *
	 * @see elgg_format_element()
	 *
	 * @param array $options Options passed to elgg_format_element()
	 *
	 * @return string
	 */
	public function render(array $options = []) {
		$inner = '';

		$children = $this->getChildren();

		foreach ($children as $child) {
			$inner .= $child->render($options);
		}

		$attrs = $this->getAttributes();

		$attrs['#text'] = $inner;
		$attrs['#tag_name'] = $this->getTagName();
		$attrs['#options'] = $options;

		return elgg_format_element($attrs);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString() {
		return $this->render();
	}
}