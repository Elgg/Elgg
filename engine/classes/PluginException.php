<?php

/**
 * PluginException
 *
 * A plugin Exception, thrown when an Exception occurs relating to the plugin mechanism.
 * Subclass for specific plugin Exceptions.
 */
class PluginException extends \Elgg\HttpException {

	/**
	 * @var ElggPlugin
	 */
	protected $plugin;

	/**
	 * Create a new plugin exception
	 *
	 * @param string     $reason   Reason
	 * @param ElggPlugin $plugin   Plugin
	 * @param string     $message  Custom message
	 * @param Throwable  $previous Previous exception
	 *
	 * @return self
	 */
	public static function factory(
		$reason,
		ElggPlugin $plugin = null,
		$message = null,
		Throwable $previous = null
	) {
		if ($plugin) {
			try {
				if ($plugin->getID()) {
					$info = elgg_echo("ElggPlugin:Error:ID", [$plugin->getID()]);
				} else {
					$info = elgg_echo("ElggPlugin:Error:Path", [$plugin->getPath()]);
				}
			} catch (Exception $ex) {
				$info = elgg_echo("ElggPlugin:Error:Unknown");
			}
		} else {
			$info = elgg_echo("ElggPlugin:Error");
		}

		if (!isset($message)) {
			$message = elgg_echo("ElggPlugin:Exception:$reason");
		}

		$code = ELGG_HTTP_INTERNAL_SERVER_ERROR;

		$exception = new static("$info: $message", $code, $previous);

		$exception->setParams([
			'reason' => $reason,
			'plugin' => $plugin,
		]);

		return $exception;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo("ElggPlugin:Error");
		}
		if (!$code) {
			$code = ELGG_HTTP_INTERNAL_SERVER_ERROR;
		}
		parent::__construct($message, $code, $previous);
	}
}
