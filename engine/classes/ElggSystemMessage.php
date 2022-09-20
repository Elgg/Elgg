<?php
/**
 * Elgg System Message
 *
 * Holds information about a single system message.
 *
 * @since 4.2.0
 * @internal
 */
class ElggSystemMessage {

	/**
	 * @var string the message text
	 */
	protected $message;

	/**
	 * @var int number of seconds before the message disappears. Error messages will never autohide. If set to -1 it will use the default TTL
	 */
	protected $ttl = -1;
	
	/**
	 * @var string type of the message (usually 'success' or 'error')
	 */
	protected $type;
	
	/**
	 * @var array additional variables set in the factory
	 */
	protected $vars = [];
	
	/**
	 * @var string optional link text
	 */
	protected $link = '';

	/**
	 * \ElggSystemMessage constructor
	 *
	 * @param string $message Message text
	 * @param string $type    Message type
	 */
	public function __construct(string $message, string $type = 'success') {
		$this->message = $message;
		$this->type = $type;
	}

	/**
	 * Create an ElggSystemMessage from an associative array. Required key is 'message'.
	 *
	 * Commonly used params:
	 *    type    => STR Message type (required)
	 *    message => STR Message text
	 *    ttl     => INT Time to live before the message is hidden
	 *    link    => STR Additional html to show in the message as an action related to the message
	 *
	 * Additional vars are stored in message vars (retrievable with getVars)
	 *
	 * @param array $options Option array of key value pairs
	 *
	 * @return \ElggSystemMessage
	 */
	public static function factory(array $options): \ElggSystemMessage {
		$message = new static(elgg_extract('message', $options));
		unset($options['message']);
		
		foreach ($options as $key => $value) {
			$func = 'set' . ucfirst($key);
			if (is_callable([$message, $func])) {
				$message->$func($value);
			} else {
				$message->vars[$key] = $value;
			}
		}
		
		return $message;
	}

	/**
	 * Set the message
	 *
	 * @param string $message message text
	 * @return void
	 */
	public function setMessage(string $message): void {
		$this->message = $message;
	}

	/**
	 * Returns the message
	 *
	 * @return string
	 */
	public function getMessage(): string {
		return $this->message;
	}

	/**
	 * Set the type
	 *
	 * @param string $type message type
	 * @return void
	 */
	public function setType(string $type): void {
		$this->type = $type;
	}

	/**
	 * Returns the message type
	 *
	 * @return string
	 */
	public function getType(): string {
		return $this->type;
	}

	/**
	 * Set the time to live
	 *
	 * @param int $ttl number of seconds before autohide
	 *
	 * @return void
	 */
	public function setTtl(int $ttl): void {
		$this->ttl = $ttl;
	}

	/**
	 * Returns the time to live
	 *
	 * @return int
	 */
	public function getTtl(): int {
		return $this->ttl;
	}
	
	/**
	 * Returns the extra vars set during the factory
	 *
	 * @param array $extras additional vars to merge into the vars
	 *
	 * @return array
	 */
	public function getVars(array $extras = []): array {
		return array_merge($this->vars, $extras);
	}
	
	/**
	 * This magic method is used for setting a string value for the object. It will be used if the object is used as a string.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return $this->message;
	}
}
