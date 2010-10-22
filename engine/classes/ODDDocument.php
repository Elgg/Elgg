<?php
/**
 * @class ODDDocument ODD Document container.
 * This class is used during import and export to construct.
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

	public function getNumElements() {
		return count($this->elements);
	}

	public function addElement(ODD $element) {
		if (!is_array($this->elements)) {
			$this->elements = array();
			$this->elements[] = $element;
		}
	}

	public function addElements(array $elements) {
		foreach ($elements as $element) {
			$this->addElement($element);
		}
	}

	public function getElements() {
		return $this->elements;
	}

	/**
	 * Set an optional wrapper factory to optionally embed the ODD document in another format.
	 */
	public function setWrapperFactory(ODDWrapperFactory $factory) {
		$this->wrapperfactory = $factory;
	}

	/**
	 * Magic function to generate valid ODD XML for this item.
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

	function rewind() {
		$this->valid = (FALSE !== reset($this->elements));
	}

	function current() {
		return current($this->elements);
	}

	function key() {
		return key($this->elements);
	}

	function next() {
		$this->valid = (FALSE !== next($this->elements));
	}

	function valid() {
		return $this->valid;
	}
}
