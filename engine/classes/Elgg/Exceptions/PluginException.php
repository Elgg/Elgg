<?php

namespace Elgg\Exceptions;

/**
 * A generic parent class for plugin exceptions
 *
 * @property \ElggPlugin $plugin plugin that caused this exception
 *
 * @since 4.0
 */
class PluginException extends Exception {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = '', int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('ElggPlugin:Error');
		}
		
		parent::__construct($message, $code, $previous);
	}
	
	/**
	 * Create a new instance of a PluginException
	 *
	 * @param array $options additional options for the exception, supports
	 *                       - <string> message: exception message
	 *                       - <int> code: exception code
	 *                       - <Throwable> previous: previous exception
	 *                       - <ElggPlugin> plugin: the plugin that caused this exception
	 *
	 * @return self
	 */
	public static function factory(array $options = []): self {
		$message = $options['message'] ?? '';
		$code = $options['code'] ?? 0;
		$previous = $options['previous'] ?? null;
		
		$exception = new static($message, $code, $previous);
		
		$plugin = $options['plugin'] ?? null;
		if ($plugin instanceof \ElggPlugin) {
			$exception->plugin = $plugin;
		}
		
		return $exception;
	}
}
