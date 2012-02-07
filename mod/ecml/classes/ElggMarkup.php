<?php
/**
 * Elgg Customizable Markup Language parser and renderer
 *
 */

class ElggMarkup {

	const TAG_REGEX = '/\[([a-z0-9\.]+)([^\]]+)?\]/';
	const ATTR_SEPARATOR = ' ';
	const ATTR_OPERATOR = '=';

	/**
	 * Render ECML in this string
	 *
	 * @param string $text The text string to be modified
	 * @return string
	 */
	public function process($text) {
		return preg_replace_callback(ElggMarkup::TAG_REGEX, array($this, 'render'), $text);
	}

	/**
	 * Render an ECML tag
	 *
	 * @param array $matches Array of string matches for a particular tag
	 * @return string
	 */
	public function render($matches) {
		$text = trim($matches[0]);
		$keyword = trim($matches[1]);
		$attributes_string = trim($matches[2]);

		$vars = array(
			'keyword' => $keyword,
			'attributes' => $this->tokenize($attributes_string),
		);
		return elgg_trigger_plugin_hook("render:$keyword", "ecml", $vars, $text);
	}

	/**
	 * Tokenize the ECML tag attributes
	 *
	 * @param string $string Attribute string
	 * @return array
	 */
	protected function tokenize($string) {

		if (empty($string)) {
			return array();
		}

		$attributes = array();
		$pos = 0;
		$char = elgg_substr($string, $pos, 1);

		// working var for assembling name and values
		$operand = $name = '';

		while ($char !== false && $char !== '') {
			switch ($char) {
				// handle quoted names/values
				case '"':
				case "'":
					$quote = $char;

					$next_char = elgg_substr($string, ++$pos, 1);
					while ($next_char != $quote) {
						if ($next_char === false) {
							// no matching quote. bail.
							return array();
						}
						$operand .= $next_char;
						$next_char = elgg_substr($string, ++$pos, 1);
					}
					break;

				case ElggMarkup::ATTR_SEPARATOR:
					// normalize true and false
					if ($operand == 'true') {
						$operand = true;
					} elseif ($operand == 'false') {
						$operand = false;
					}
					$attributes[$name] = $operand;
					$operand = $name = '';
					break;

				case ElggMarkup::ATTR_OPERATOR:
					// save name, switch to value
					$name = $operand;
					$operand = '';
					break;

				default:
					$operand .= $char;
					break;
			}

			$char = elgg_substr($string, ++$pos, 1);
		}

		// need to get the last attr
		if ($name && $operand) {
			if ($operand == 'true') {
				$operand = true;
			} else if ($operand == 'false') {
				$operand = false;
			}
			$attributes[$name] = $operand;
		}

		return $attributes;
	}
}
