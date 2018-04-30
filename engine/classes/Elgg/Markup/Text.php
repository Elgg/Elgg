<?php

namespace Elgg\Markup;

/**
 * Orphaned/unwrapped text
 */
class Text implements Element {

	/**
	 * @var string
	 */
	protected $text;

	/**
	 * Constructor
	 *
	 * @param string $html HTML
	 */
	public function __construct($html = '') {
		$this->text = $html;
	}

	/**
	 * {@inheritdoc}
	 */
	public function render(array $options = []) {
		return $this->text;
	}

	/**
	 * Wrap text with a tag
	 *
	 * @param string $class Tag element class name
	 *                      Must extend \Elgg\Markup\Tag
	 * @param array  $attrs Attributes
	 *
	 * @return Tag
	 */
	public function wrap($class, array $attrs = []) {
		if (!is_subclass_of($class, Tag::class)) {
			throw new \InvalidArgumentException($class . ' must extend ' . Tag::class);
		}

		return new $class($this, $attrs);
	}
}