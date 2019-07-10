<?php

namespace Elgg\I18n;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * A single localizable message template.
 *
 * We introduced this class because we want to have the flexibility of
 * easily switching our message template language from sprintf to ICU...
 *
 * Example messages:
 *  - "{subject} spent {num_nights,number,integer} nights camping in {location}." (ICU)
 *  - "%s spent %d nights camping in %s" (sprintf)
 *
 * @since 1.11
 * @internal
 */
abstract class MessageTemplate {

	/** @var string */
	protected $template;
	
	/**
	 * Constructor
	 *
	 * @param string $template The message template
	 */
	public function __construct($template) {
		$this->template = $template;
	}
	
	/**
	 * Applies the inputs to the message template and returns the result.
	 *
	 * @param array $args The inputs to this message
	 *
	 * @return string The rendered including all the interpolated inputs
	 */
	abstract public function format(array $args);
	
	/**
	 * Get the string template this message uses for translation.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->template;
	}
}
