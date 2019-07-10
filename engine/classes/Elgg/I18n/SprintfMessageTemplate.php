<?php

namespace Elgg\I18n;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * A message that uses vsprintf to insert arguments into the template.
 *
 * @since 1.11
 * @internal
 */
final class SprintfMessageTemplate extends MessageTemplate {
	
	/**
	 * {@inheritDoc}
	 */
	public function format(array $args) {
		return \vsprintf($this->template, $args);
	}
}
