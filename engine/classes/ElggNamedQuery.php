<?php

/**
 * Allow an elgg_get_*() query to be easily modified via plugin hook
 */
class ElggNamedQuery extends ElggAbstractQueryModifier {

	protected $name;
	protected $locked_keys;

	/**
	 * @param string $name Name for this query. You should prefix the name with the plugin so to
	 *                     avoid unexpected name collisions
	 *
	 * @param array $options options for the elgg_get_*() function
	 *
	 * @param array $locked_keys keys to options array which will not be altered. If the key exists,
	 *                           the value will be preserved. If not, there will be no key in the
	 *							 return value of getOptions().
	 */
	public function __construct($name, array $options = array(), array $locked_keys = array()) {
		parent::__construct($options);
		$this->name = $name;
		$this->locked_keys = $locked_keys;
	}

	/**
	 * Modify the $options
	 *
	 * @return void
	 */
	protected function execute() {
		$orig_options = $this->options;

		$params = array(
			'orig_options' => $orig_options,
		);
		$options = elgg_trigger_plugin_hook('query:alter_options', $this->name, $params, $this->options);

		if (is_array($options)) {
			foreach ($this->locked_keys as $key) {
				if (array_key_exists($key, $orig_options)) {
					$options = $orig_options[$key];
				} else {
					unset($options[$key]);
				}
			}
			$this->options = $options;
		}
	}
}
