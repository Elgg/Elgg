<?php

namespace Elgg\Forms;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @since 1.10.0
 *
 * @internal
 */
class StickyForms {
	
	/**
	 * Save form submission data (all GET and POST vars) into a session cache
	 *
	 * Call this from an action when you want all your submitted variables
	 * available if the submission fails validation and is sent back to the form
	 *
	 * @param string $form_name Name of the sticky form
	 *
	 * @return void
	 */
	public function makeStickyForm($form_name) {
		$this->clearStickyForm($form_name);

		$banned_keys = [];
		// TODO make $banned_keys an argument
		if (in_array($form_name, ['register', 'useradd', 'usersettings'])) {
			$banned_keys = ['password', 'password2'];
		}

		$session = _elgg_services()->session;
		$data = $session->get('sticky_forms', []);
		$req = _elgg_services()->request;
	
		// will go through XSS filtering in elgg_get_sticky_value()
		$vars = array_merge($req->query->all(), $req->request->all());
		foreach ($banned_keys as $key) {
			unset($vars[$key]);
		}
		$data[$form_name] = $vars;
	
		$session->set('sticky_forms', $data);
	}
	
	/**
	 * Remove form submission data from the session
	 *
	 * Call this if validation is successful in the action handler or
	 * when they sticky values have been used to repopulate the form
	 * after a validation error.
	 *
	 * @param string $form_name Form namespace
	 *
	 * @return void
	 */
	public function clearStickyForm($form_name) {
		$session = _elgg_services()->session;
		$data = $session->get('sticky_forms', []);
		unset($data[$form_name]);
		$session->set('sticky_forms', $data);
	}
	
	/**
	 * Does form submission data exist for this form?
	 *
	 * @param string $form_name Form namespace
	 *
	 * @return boolean
	 */
	public function isStickyForm($form_name) {
		$session = _elgg_services()->session;
		$data = $session->get('sticky_forms', []);
		return isset($data[$form_name]);
	}
	
	/**
	 * Get a specific value from cached form submission data
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
	public function getStickyValue($form_name, $variable = '', $default = null, $filter_result = true) {
		$session = _elgg_services()->session;
		$data = $session->get('sticky_forms', []);
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
	 * Get all submission data cached for a form
	 *
	 * @param string $form_name     The name of the form
	 * @param bool   $filter_result Filter for bad input if true
	 *
	 * @return array
	 */
	public function getStickyValues($form_name, $filter_result = true) {
		$session = _elgg_services()->session;
		$data = $session->get('sticky_forms', []);
		if (!isset($data[$form_name])) {
			return [];
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
	 * Remove one value of form submission data from the session
	 *
	 * @param string $form_name The name of the form
	 * @param string $variable  The name of the variable to clear
	 *
	 * @return void
	 */
	public function clearStickyValue($form_name, $variable) {
		$session = _elgg_services()->session;
		$data = $session->get('sticky_forms', []);
		unset($data[$form_name][$variable]);
		$session->set('sticky_forms', $data);
	}
}
