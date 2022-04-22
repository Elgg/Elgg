<?php
/**
 * Bundle all functions which have been deprecated in Elgg 4.2
 */

/**
 * Render a menu item (usually as a link)
 *
 * @param \ElggMenuItem $item The menu item
 * @param array         $vars Options to pass to output/url if a link
 *
 * @return string
 * @since 1.9.0
 * @deprecated 4.2 use elgg_view('navigation/menu/elements/item/url')
 */
function elgg_view_menu_item(\ElggMenuItem $item, array $vars = []) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated use elgg_view("navigation/menu/elements/item/url")', '4.2');
	
	$vars = array_merge($item->getValues(), $vars);
	$vars['class'] = elgg_extract_class($vars, ['elgg-menu-content']);
	
	if ($item->getLinkClass()) {
		$vars['class'][] = $item->getLinkClass();
	}
	
	if ($item->getHref() === false || $item->getHref() === null) {
		$vars['class'][] = 'elgg-non-link';
	}
	
	if (!isset($vars['rel']) && !isset($vars['is_trusted'])) {
		$vars['is_trusted'] = true;
	}
	
	if ($item->getConfirmText()) {
		$vars['confirm'] = $item->getConfirmText();
	}
	
	return elgg_view('output/url', $vars);
}

/**
 * Display a system message on next page load.
 *
 * @param string|array $message Message or messages to add
 *
 * @return bool
 * @deprecated 4.2 use elgg_register_success_message()
 */
function system_message($message) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated use elgg_register_success_message()', '4.2');
	
	_elgg_services()->system_messages->addSuccessMessage($message);
	return true;
}

/**
 * Display an error on next page load.
 *
 * @param string|array $error Error or errors to add
 *
 * @return bool
 * @deprecated 4.2 use elgg_register_error_message()
 */
function register_error($error) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated use elgg_register_error_message()', '4.2');
	
	_elgg_services()->system_messages->addErrorMessage($error);
	return true;
}

/**
 * Returns a database row from the entities table.
 *
 * @tip Use get_entity() to return the fully loaded entity.
 *
 * @warning This will only return results if a) it exists, b) you have access to it.
 * see {@link _elgg_get_access_where_sql()}.
 *
 * @param int $guid The GUID of the object to extract
 *
 * @return \stdClass|false
 * @deprecated 4.2 use elgg_get_entity_as_row()
 */
function get_entity_as_row($guid) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated use elgg_get_entity_as_row()', '4.2');
	
	return _elgg_services()->entityTable->getRow($guid);
}
