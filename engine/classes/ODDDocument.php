<?php
/**
 * This class is used during import and export to construct.
 *
 * @package    Elgg.Core
 * @subpackage ODD
 */
class ODDDocument implements Iterator {
	/**
	 * ODD Version
	 *
	 * @var string
	 */
	private $ODDSupportedVersion = "1.0";

	/**
	 * Elements of the document.
	 */
	private $elements;

	/**
	 * Optional wrapper factory.
	 */
	private $wrapperfactory;

	/**
	 * Create a new ODD Document.
	 *
	 * @param array $elements Elements to add
	 *
	 * @return void
	 */
	public function __construct(array $elements = NULL) {
		if ($elements) {
			if (is_array($elements)) {
				$this->elements = $elements;
			} else {
				$this->addElement($elements);
			}
		} else {
			$this->elements = array();
		}
	}

	/**
	 * Return the version of ODD being used.
	 *
	 * @return string
	 */
	public function getVersion() {
		return $this->ODDSupportedVersion;
	}

	/**
	 * Returns the number of elements
	 *
	 * @return int
	 */
	public function getNumElements() {
		return count($this->elements);
	}

	/**
	 * Add an element
	 *
	 * @param ODD $element An ODD element
	 *
	 * @return void
	 */
	public function addElement(ODD $element) {
		if (!is_array($this->elements)) {
			$this->elements = array();
			$this->elements[] = $element;
		}
	}

	/**
	 * Add multiple elements at once
	 *
	 * @param array $elements Array of ODD elements
	 *
	 * @return void
	 */
	public function addElements(array $elements) {
		foreach ($elements as $element) {
			$this->addElement($element);
		}
	}

	/**
	 * Return all elements
	 *
	 * @return array
	 */
	public function getElements() {
		return $this->elements;
	}

	/**
	 * Set an optional wrapper factory to optionally embed the ODD document in another format.
	 *
	 * @param ODDWrapperFactory $factory The factory
	 *
	 * @return void
	 */
	public function setWrapperFactory(ODDWrapperFactory $factory) {
		$this->wrapperfactory = $factory;
	}

	/**
	 * Magic function to generate valid ODD XML for this item.
	 *
	 * @return string
	 */
	public function __toString() {
		$xml = "";

		if ($this->wrapperfactory) {
			// A wrapper has been provided
			$wrapper = $this->wrapperfactory->getElementWrapper($this); // Get the wrapper for this element

			$xml = $wrapper->wrap($this); // Wrap this element (and subelements)
		} else {
			// Output begin tag
			$generated = date("r");
			$xml .= "<odd version=\"{$this->ODDSupportedVersion}\" generated=\"$generated\">\n";

			// Get XML for elements
			foreach ($this->elements as $element) {
				$xml .= "$element";
			}

			// Output end tag
			$xml .= "</odd>\n";
		}

		return $xml;
	}

	// ITERATOR INTERFACE //////////////////////////////////////////////////////////////
	/*
	 * This lets an entity's attributes be displayed using foreach as a normal array.
	 * Example: http://www.sitepoint.com/print/php5-standard-library
	 */

	private $valid = FALSE;

	/**
	 * Iterator interface
	 *
	 * @see Iterator::rewind()
	 *
	 * @return void
	 */
	function rewind() {
		$this->valid = (FALSE !== reset($this->elements));
	}

	/**
	 * Iterator interface
	 *
	 * @see Iterator::current()
	 *
	 * @return void
	 */
	function current() {
		return current($this->elements);
	}

	/**
	 * Iterator interface
	 *
	 * @see Iterator::key()
	 *
	 * @return void
	 */
	function key() {
		return key($this->elements);
	}

	/**
	 * Iterator interface
	 *
	 * @see Iterator::next()
	 *
	 * @return void
	 */
	function next() {
		$this->valid = (FALSE !== next($this->elements));
	}

	/**
	 * Iterator interface
	 *
	 * @see Iterator::valid()
	 *
	 * @return void
	 */
	function valid() {
		return $this->valid;
	}
}
