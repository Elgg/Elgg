<?php

namespace Elgg\Search;

/**
 * Highlights relavant substrings in search results
 */
class Highlighter {

	/**
	 * @var array
	 */
	protected $params = [];

	/**
	 * Constructor
	 *
	 * @param array $params Search params
	 */
	public function __construct(array $params = []) {
		$this->params = $params;
	}

	/**
	 * Safely highlights search query words found in $string avoiding recursion
	 *
	 * @param string $text Text to highlight
	 *
	 * @return string
	 */
	public function highlightWords($text) {

		$text = _elgg_get_display_query($text);

		$parts = elgg_extract('query_parts', $this->params);
		if (empty($parts)) {
			return $text;
		}

		foreach ($parts as $index => $part) {
			// remove any boolean mode operators
			$part = preg_replace("/([\-\+~])([\w]+)/i", '$2', $part);

			// escape the delimiter and any other regexp special chars
			$part = preg_quote($part, '/');
			$parts[$index] = $part;
		}
		
		$search = "/(" . implode('|', $parts) . ")/i";

		return preg_replace_callback($search, function($matches) use ($parts) {
			$text = $matches[0];
			$i = array_search($text, $parts) + 1;
			
			return "<span class=\"search-highlight search-highlight-color{$i}\">{$text}</span>";
		}, $text);
	}

	/**
	 * Return a string with highlighted matched queries and relevant context
	 * Determines context based upon occurrence and distance of words with each other.
	 *
	 * @todo   This also highlights partials even if partial search is not allowed.
	 *
	 * @param string $text              Text to highlight
	 * @param int    $min_match_context Minimum length of the text to initiate highlighting (default: 30)
	 * @param int    $max_length        Maximum length of the truncated and highlighted text (default: 300)
	 *
	 * @return string
	 */
	public function highlight($text, $min_match_context = 30, $max_length = 300) {

		$text = strip_tags($text);

		$haystack_length = elgg_strlen($text);
		$haystack_lc = elgg_strtolower($text);

		// if haystack < $max_length return the entire haystack w/formatting immediately
		if ($haystack_length <= $max_length) {
			return $this->highlightWords($text);
		}
		
		$parts = elgg_extract('query_parts', $this->params);
		if (empty($parts) || !is_array($parts)) {
			// no query
			return $text;
		}

		// get the starting positions and lengths for all matching words
		$starts = [];
		$lengths = [];
		foreach ($parts as $part) {
			$part = elgg_strtolower($part);
			$count = elgg_substr_count($haystack_lc, $part);
			$word_len = elgg_strlen($part);
			$haystack_len = elgg_strlen($haystack_lc);

			// find the start positions for the words
			if ($count > 1) {
				$offset = 0;
				while (false !== $pos = elgg_strpos($haystack_lc, $part, $offset)) {
					$start = ($pos - $min_match_context > 0) ? $pos - $min_match_context : 0;
					$starts[] = $start;
					$stop = $pos + $word_len + $min_match_context;
					$lengths[] = $stop - $start;
					$offset += $pos + $word_len;

					if ($offset >= $haystack_len) {
						break;
					}
				}
			} else {
				$pos = elgg_strpos($haystack_lc, $part);
				$start = ($pos - $min_match_context > 0) ? $pos - $min_match_context : 0;
				$starts[] = $start;
				$stop = $pos + $word_len + $min_match_context;
				$lengths[] = $stop - $start;
			}
		}

		$offsets = $this->consolidateSubstrings($starts, $lengths);

		// figure out if we can adjust the offsets and lengths
		// in order to return more context
		$total_length = array_sum($offsets);
		if ($total_length < $max_length && $offsets) {
			$add_length = floor((($max_length - $total_length) / count($offsets)) / 2);

			$starts = [];
			$lengths = [];
			foreach ($offsets as $offset => $length) {
				$start = ($offset - $add_length > 0) ? $offset - $add_length : 0;
				$length = $length + $add_length;
				$starts[] = $start;
				$lengths[] = $length;
			}

			$offsets = $this->consolidateSubstrings($starts, $lengths);
		}

		// sort by order of string size descending (which is roughly
		// the proximity of matched terms) so we can keep the
		// substrings with terms closest together and discard
		// the others as needed to fit within $max_length.
		arsort($offsets);

		$return_strs = [];
		$total_length = 0;
		foreach ($offsets as $start => $length) {
			$string = trim(elgg_substr($text, $start, $length));

			// continue past if adding this substring exceeds max length
			if ($total_length + $length > $max_length) {
				continue;
			}

			$total_length += $length;
			$return_strs[$start] = $string;
		}

		// put the strings in order of occurence
		ksort($return_strs);

		// add ...s where needed
		$return = implode('...', $return_strs);
		if (!array_key_exists(0, $return_strs)) {
			$return = "...$return";
		}

		// add to end of string if last substring doesn't hit the end.
		$starts = array_keys($return_strs);
		$last_pos = $starts[count($starts) - 1];
		if ($last_pos + elgg_strlen($return_strs[$last_pos]) < $haystack_length) {
			$return .= '...';
		}

		return $this->highlightWords($return);
	}

	/**
	 * Takes an array of offsets and lengths and consolidates any
	 * overlapping entries, returning an array of new offsets and lengths
	 *
	 * Offsets and lengths are specified in separate arrays because of possible
	 * index collisions with the offsets.
	 *
	 * @param array $offsets offsets
	 * @param array $lengths lengths
	 *
	 * @return array
	 */
	protected function consolidateSubstrings($offsets, $lengths) {
		// sort offsets by occurence
		asort($offsets, SORT_NUMERIC);

		// reset the indexes maintaining association with the original offsets.
		$offsets = array_merge($offsets);

		$new_lengths = [];
		foreach ($offsets as $i => $offset) {
			$new_lengths[] = $lengths[$i];
		}

		$lengths = $new_lengths;

		$return = [];
		$count = count($offsets);
		for ($i = 0; $i < $count; $i++) {
			$offset = $offsets[$i];
			$length = $lengths[$i];
			$end_pos = $offset + $length;

			// find the next entry that doesn't overlap
			while (array_key_exists($i + 1, $offsets) && $end_pos > $offsets[$i + 1]) {
				$i++;
				if (!array_key_exists($i, $offsets)) {
					break;
				}
				$end_pos = $lengths[$i] + $offsets[$i];
			}

			$length = $end_pos - $offset;

			// will never have a colliding offset, so can return as a single array
			$return[$offset] = $length;
		}

		return $return;
	}

}
