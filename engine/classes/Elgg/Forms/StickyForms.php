<?php

namespace Elgg\Forms;

/**
 * Stick forms service
 *
 * @since 1.10.0
 * @internal
 */
class StickyForms {
	
	/**
	 * @var \ElggSession
	 */
	protected $session;
	
	/**
	 * Constructor
	 *
	 * @param \ElggSession $session Session for storage
	 */
	public function __construct(\ElggSession $session) {
		$this->session = $session;
	}
	
	/**
	 * Save form submission data (all GET and POST vars) into a session cache
	 *
	 * Call this from an action when you want all your submitted variables
	 * available if the submission fails validation and is sent back to the form
	 *
	 * @param string   $form_name           Name of the sticky form
	 * @param string[] $ignored_field_names Field names which shouldn't be made sticky in this form
	 *
	 * @return void
	 */
	public function makeStickyForm(string $form_name, array $ignored_field_names = []): void {
		$this->clearStickyForm($form_name);

		$default_ignored_field_names = [
			'__elgg_ts', // never store CSRF tokens
			'__elgg_token', // never store CSRF tokens
		];
		$ignored_field_names = array_merge($default_ignored_field_names, $ignored_field_names);
		
		$data = $this->session->get('sticky_forms', []);
		$req = _elgg_services()->request;
	
		// will go through XSS filtering in elgg_get_sticky_value()
		$vars = array_merge($req->query->all(), $req->request->all());
		foreach ($ignored_field_names as $key) {
			unset($vars[$key]);
		}
		$data[$form_name] = $vars;
	
		$this->session->set('sticky_forms', $data);
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
	public function clearStickyForm(string $form_name): void {
		$data = $this->session->get('sticky_forms', []);
		unset($data[$form_name]);
		
		$this->session->set('sticky_forms', $data);
	}
	
	/**
	 * Does form submission data exist for this form?
	 *
	 * @param string $form_name Form namespace
	 *
	 * @return bool
	 */
	public function isStickyForm(string $form_name): bool {
		$data = $this->session->get('sticky_forms', []);
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
	 */
	public function getStickyValue(string $form_name, string $variable = '', $default = null, bool $filter_result = true) {
		$data = $this->session->get('sticky_forms', []);
		if (isset($data[$form_name][$variable])) {
			$value = $data[$form_name][$variable];
			if ($filter_result) {
				// XSS filter result
				$value = elgg_sanitize_input($value);
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
	public function getStickyValues(string $form_name, bool $filter_result = true): array {
		$data = $this->session->get('sticky_forms', []);
		if (!isset($data[$form_name])) {
			return [];
		}
	
		$values = $data[$form_name];
		if ($filter_result) {
			foreach ($values as $key => $value) {
				// XSS filter result
				$values[$key] = elgg_sanitize_input($value);
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
	 * @deprecated 4.3
	 */
	public function clearStickyValue(string $form_name, string $variable): void {
		$data = $this->session->get('sticky_forms', []);
		unset($data[$form_name][$variable]);
		
		$this->session->set('sticky_forms', $data);
	}
}
