<?php
namespace Elgg\Views\TableColumn;

use Elgg\Values;
use Elgg\Views\TableColumn;

/**
 * Factory for table column objects
 *
 * `elgg_list_entities()` can output tables by specifying `$options['list_type'] = 'table'` and
 * by providing an array of TableColumn objects to `$options['columns']`. This service, available
 * as `elgg()->table_columns` provides methods to create column objects based around existing views
 * like `page/components/column/*`, properties, or methods.
 *
 * Numerous pre-existing methods are provided via `__call()` magic. See this method to find out how
 * to add your own methods, override the existing ones, or completely replace a method via hook.
 *
 * @internal Use elgg()->table_columns to access the instance of this.
 *
 * @method TableColumn admin($heading = null, $vars = [])
 * @method TableColumn banned($heading = null, $vars = [])
 * @method TableColumn container($heading = null, $vars = [])
 * @method TableColumn excerpt($heading = null, $vars = [])
 * @method TableColumn icon($heading = null, $vars = [])
 * @method TableColumn item($heading = null, $vars = [])
 * @method TableColumn language($heading = null, $vars = [])
 * @method TableColumn link($heading = null, $vars = [])
 * @method TableColumn owner($heading = null, $vars = [])
 * @method TableColumn time_created($heading = null, $vars = [])
 * @method TableColumn time_updated($heading = null, $vars = [])
 * @method TableColumn description($heading = null)
 * @method TableColumn email($heading = null)
 * @method TableColumn name($heading = null)
 * @method TableColumn type($heading = null)
 * @method TableColumn user($heading = null, $vars = [])
 * @method TableColumn username($heading = null)
 * @method TableColumn getSubtype($heading = null)
 * @method TableColumn getDisplayName($heading = null)
 * @method TableColumn getMimeType($heading = null)
 * @method TableColumn getSimpleType($heading = null)
 */
class ColumnFactory {

	/**
	 * Make a column from one of the page/components/column/* views.
	 *
	 * @param string $name    Column name (view will be "page/components/column/$name")
	 * @param string $heading Optional heading
	 * @param array  $vars    View vars (item, item_vars, and type will be merged in)
	 *
	 * @return ViewColumn
	 */
	public function fromView($name, $heading = null, $vars = []) {
		$view = "page/components/column/$name";

		if (!is_string($heading)) {
			if (elgg_language_key_exists("table_columns:fromView:$name")) {
				$heading = elgg_echo("table_columns:fromView:$name");
			} else {
				$title = str_replace('_', ' ', $name);
				$heading = elgg_ucwords($title);
			}
		}

		return new ViewColumn($view, $heading, $vars);
	}

	/**
	 * Make a column by reading a property of the item
	 *
	 * @param string $name    Property name. e.g. "description", "email", "type"
	 * @param string $heading Heading
	 *
	 * @return CallableColumn
	 */
	public function fromProperty($name, $heading = null) {
		if (!is_string($heading)) {
			if (elgg_language_key_exists("table_columns:fromProperty:$name")) {
				$heading = elgg_echo("table_columns:fromProperty:$name");
			} else {
				$title = str_replace('_', ' ', $name);
				$heading = elgg_ucwords($title);
			}
		}

		$renderer = function ($item) use ($name) {
			return $item->{$name};
		};

		return new CallableColumn($renderer, $heading);
	}

	/**
	 * Make a column by calling a method on the item
	 *
	 * @param string $name    Method name. e.g. "getSubtype", "getDisplayName"
	 * @param string $heading Heading
	 * @param array  $args    Method arguments
	 *
	 * @return CallableColumn
	 */
	public function fromMethod($name, $heading = null, $args = []) {
		if (!is_string($heading)) {
			if (elgg_language_key_exists("table_columns:fromMethod:$name")) {
				$heading = elgg_echo("table_columns:fromMethod:$name");
			} else {
				$title = str_replace('_', ' ', $name);
				$heading = elgg_ucwords($title);
			}
		}

		$renderer = function ($item) use ($name, $args) {
			return call_user_func_array([$item, $name], $args);
		};

		return new CallableColumn($renderer, $heading);
	}

	/**
	 * Provide additional methods via hook and specified language keys.
	 *
	 * First, the hook `table_columns:call` is called. Details in `docs/guides/hooks-list.rst`.
	 *
	 * Then it checks existence of 3 language keys in order to defer processing to a local method:
	 *
	 * - "table_columns:fromView:$name" -> uses $this->fromView($name, ...).
	 * - "table_columns:fromProperty:$name" -> uses $this->fromProperty($name, ...).
	 * - "table_columns:fromMethod:$name" -> uses $this->fromMethod($name, ...).
	 *
	 * See the `from*()` methods for details.
	 *
	 * @param string $name      Method name
	 * @param array  $arguments Arguments
	 *
	 * @return TableColumn
	 */
	public function __call($name, $arguments) {
		// allow hook to hijack magic methods
		$column = _elgg_services()->hooks->trigger('table_columns:call', $name, [
			'arguments' => $arguments,
		]);
		if ($column instanceof TableColumn) {
			return $column;
		}

		if (elgg_language_key_exists("table_columns:fromView:$name")) {
			array_unshift($arguments, $name);
			return call_user_func_array([$this, 'fromView'], $arguments);
		}

		if (elgg_language_key_exists("table_columns:fromProperty:$name")) {
			array_unshift($arguments, $name);
			return call_user_func_array([$this, 'fromProperty'], $arguments);
		}

		if (elgg_language_key_exists("table_columns:fromMethod:$name")) {
			array_unshift($arguments, $name);
			return call_user_func_array([$this, 'fromMethod'], $arguments);
		}

		// empty column and error
		_elgg_services()->logger->error(__CLASS__ . ": No method defined '$name'");
		return new CallableColumn([Values::class, 'getNull'], '');
	}
}
