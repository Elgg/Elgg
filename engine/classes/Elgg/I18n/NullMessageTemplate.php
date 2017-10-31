<?php
namespace Elgg\I18n;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * A message that always ignores all parameters and just returns the template.
 *
 * @since 1.11
 *
 * @access private
 */
final class NullMessage extends MessageTemplate {
	
	/**
	 * {@inheritDoc}
	 */
	public function format(array $args) {
		return "$this";
	}
}
