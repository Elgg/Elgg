<?php
namespace Elgg;

/**
 * PRIVATE CLASS. API IN FLUX. DO NOT USE DIRECTLY.
 * 
 * PLUGIN DEVELOPERS SHOULD USE elgg_*_context FUNCTIONS INSTEAD.
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
 * @package Elgg.Core
 * @access  private
 * @since   1.10.0
 */
final class Context {
	
	private $stack = array();
	
	/**
	 * Get the most recently pushed context value.
	 *
	 * @return string|null
	 */
	public function peek() {
		$topPos = count($this->stack) - 1;
		
		if ($topPos >= 0) {
			return $this->stack[$topPos];
		} else {
			return NULL;
		}

	}
	
	/**
	 * Push a context onto the top of the stack
	 *
	 * @param string $context The context string to add to the context stack
	 * @return void
	 */
	public function push($context) {
		array_push($this->stack, $context);
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
}