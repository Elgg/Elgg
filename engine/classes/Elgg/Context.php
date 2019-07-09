<?php

namespace Elgg;

use Elgg\Http\Request as HttpRequest;

/**
 * Manages a global stack of strings for sharing information about the current execution context
 *
 * Views can modify their output based on the local context. You may want to
 * display a list of blogs on a blog page or in a small widget. The rendered
 * output could be different for those two contexts ('blog' vs 'widget').
 *
 * Pages that pass through the page handling system set the context to the
 * first string after the root url. Example: http://example.org/elgg/bookmarks/
 * results in the initial context being set to 'bookmarks'.
 *
 * The context is a stack so that for a widget on a profile, the context stack
 * may contain first 'profile' and then 'widget'.
 *
 * @warning The context is not available until the page_handler runs (after
 * the 'init, system' event processing has completed).
 *
 * @internal
 * @since 1.10.0
 */
final class Context {
	
	private $stack = [];

	/**
	 * Initialize the context from the request
	 *
	 * @param HttpRequest $request Elgg request
	 */
	public function __construct(HttpRequest $request) {
		// don't do this for *_handler.php, etc.
		if (basename($request->server->get('SCRIPT_FILENAME')) === 'index.php') {
			$context = $request->getFirstUrlSegment();
			if (!$context) {
				$context = 'main';
			}

			$this->stack = [$context];
		}
	}

	/**
	 * Get the most recently pushed context value.
	 *
	 * @return string|null
	 */
	public function peek() {
		$last = end($this->stack);
		return ($last === false) ? null : $last;
	}
	
	/**
	 * Push a context onto the top of the stack
	 *
	 * @param string $context The context string to add to the context stack
	 * @return void
	 */
	public function push($context) {
		$this->stack[] = "$context";
	}
	
	/**
	 * Removes and returns the top context string from the stack
	 *
	 * @return string|null
	 */
	public function pop() {
		return array_pop($this->stack);
	}
	
	/**
	 * Sets the page context
	 *
	 * @param string $context The context of the page
	 * @return bool
	 */
	public function set($context) {
		$context = trim($context);

		if (empty($context)) {
			return false;
		}

		$context = strtolower($context);

		$this->pop();
		$this->push($context);

		return true;
	}

	/**
	 * Check if this context exists anywhere in the stack
	 *
	 * This is useful for situations with more than one element in the stack. For
	 * example, a widget has a context of 'widget'. If a widget view needs to render
	 * itself differently based on being on the dashboard or profile pages, it
	 * can check the stack.
	 *
	 * @param string $context The context string to check for
	 * @return bool
	 */
	public function contains($context) {
		return in_array($context, $this->stack);
	}

	/**
	 * Get the entire context stack as an array (e.g. for backing it up)
	 *
	 * @return string[]
	 */
	public function toArray() {
		return $this->stack;
	}

	/**
	 * Overwrite the entire context stack from an array of strings
	 *
	 * @param string[] $stack All contexts to be placed on the stack
	 * @return void
	 */
	public function fromArray(array $stack) {
		$this->stack = array_map('strval', $stack);
	}
}
