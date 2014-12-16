<?php
namespace Elgg\Forms;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @package    Elgg.Core
 * @subpackage Forms
 * @since      1.10.0
 *
 * @access private
 */
class StickyForms {
	
	/**
	 * Load all the GET and POST variables into the sticky form cache
	 *
	 * Call this from an action when you want all your submitted variables
	 * available if the submission fails validation and is sent back to the form
	 *
	 * @param string $form_name Name of the sticky form
	 *
	 * @return void
	 */
	public function makeStickyForm($form_name) {
	
		elgg_clear_sticky_form($form_name);
	
		$session = _elgg_services()->session;
		$data = $session->get('sticky_forms', array());
		$req = _elgg_services()->request;
	
		// will go through XSS filtering in elgg_get_sticky_value()
		$vars = array_merge($req->query->all(), $req->request->all());
		$data[$form_name] = $vars;
	
		$session->set('sticky_forms', $data);
	}
	
	/**
	 * Clear the sticky form cache
	 *
	 * Call this if validation is successful in the action handler or
	 * when they sticky values have been used to repopulate the form
	 * after a validation error.
	 *
	 * @param string $form_name Form namespace
	 *
	 * @return void
	 */
	function clearStickyForm($form_name) {
		$session = _elgg_services()->session;
		$data = $session->get('sticky_forms', array());
		unset($data[$form_name]);
		$session->set('sticky_forms', $data);
	}
	
	/**
	 * Has this form been made sticky?
	 *
	 * @param string $form_name Form namespace
	 *
	 * @return boolean
	 */
	function isStickyForm($form_name) {
		$session = _elgg_services()->session;
		$data = $session->get('sticky_forms', array());
		return isset($data[$form_name]);
	}
	
	/**
	 * Get a specific sticky variable
	 *
	 * @param string  $form_name     The name of the form
	 * @param string  $variable      The name of the variable
	 * @param mixed   $default       Default value if the variable does not exist in sticky cache
	 * @param boolean $filter_result Filter for bad input if true
	 *
	 * @return mixed
	 *
	 * @todo should this filter the default value?
	 */
	function getStickyValue($form_name, $variable = '', $default = null, $filter_result = true) {
		$session = _elgg_services()->session;
		$data = $session->get('sticky_forms', array());
		if (isset($data[$form_name][$variable])) {
			$value = $data[$form_name][$variable];
			if ($filter_result) {
				// XSS filter result
				$value = filter_tags($value);
			}
			return $value;
		}
		return $default;
	}
	
	/**
	 * Get all the values in a sticky form in an array
	 *
	 * @param string $form_name     The name of the form
	 * @param bool   $filter_result Filter for bad input if true
	 *
	 * @return array
	 */
	function getStickyValues($form_name, $filter_result = true) {
		$session = _elgg_services()->session;
		$data = $session->get('sticky_forms', array());
		if (!isset($data[$form_name])) {
			return array();
		}
	
		$values = $data[$form_name];
		if ($filter_result) {
			foreach ($values as $key => $value) {
				// XSS filter result
				$values[$key] = filter_tags($value);
			}
		}
		return $values;
	}
	
	/**
	 * Clear a specific sticky variable
	 *
	 * @param string $form_name The name of the form
	 * @param string $variable  The name of the variable to clear
	 *
	 * @return void
	 */
	function clearStickyValue($form_name, $variable) {
		$session = _elgg_services()->session;
		$data = $session->get('sticky_forms', array());
		unset($data[$form_name][$variable]);
		$session->set('sticky_forms', $data);
	}
	
}