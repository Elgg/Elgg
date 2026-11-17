<?php

namespace Elgg\Notifications;

/**
 * Notification container
 *
 * @since 1.10
 */
#[\AllowDynamicProperties]
class Notification {

	/**
	 * @var string Target URL
	 */
	public $url;

	/**
	 * Create a notification
	 *
	 * @param \ElggEntity $from     The entity sending the notification (usually the site)
	 * @param \ElggEntity $to       The entity receiving the notification
	 * @param string      $language The language code for the notification
	 * @param string      $subject  The subject of the notification
	 * @param string      $body     The body of the notification
	 * @param string      $summary  Optional summary of the notification
	 * @param array       $params   Optional array of parameters
	 */
	public function __construct(
		protected \ElggEntity $from,
		protected \ElggEntity $to,
		public $language,
		public $subject,
		public $body,
		public $summary = '',
		public array $params = []
	) {
		if (isset($this->params['url'])) {
			$this->url = $this->params['url'];
		}
	}

	/**
	 * Get the sender entity
	 *
	 * @return \ElggEntity
	 */
	public function getSender(): \ElggEntity {
		return $this->from;
	}

	/**
	 * Get the sender entity guid
	 *
	 * @return int
	 */
	public function getSenderGUID(): int {
		return $this->from->guid;
	}

	/**
	 * Get the recipient entity
	 *
	 * @return \ElggEntity
	 */
	public function getRecipient(): \ElggEntity {
		return $this->to;
	}

	/**
	 * Get the recipient entity guid
	 *
	 * @return int
	 */
	public function getRecipientGUID(): int {
		return $this->to->guid;
	}

	/**
	 * Export notification
	 *
	 * @return \stdClass
	 */
	public function toObject(): \stdClass {
		$obj = new \stdClass();
		$vars = get_object_vars($this);
		
		$vars = array_merge($this->params, $vars);
		unset($vars['params']);
		unset($vars['sender']);
		unset($vars['recipient']);
		unset($vars['subscriptions']);
		unset($vars['action']);
		unset($vars['object']);
		unset($vars['handler']);
		
		foreach ($vars as $key => $value) {
			if (is_object($value) && is_callable([$value, 'toObject'])) {
				$obj->$key = $value->toObject();
			} else {
				$obj->$key = $value;
			}
		}
		
		return $obj;
	}
	
	/**
	 * Called when the object is serialized
	 *
	 * @return array
	 * @see serialize()
	 */
	public function __serialize(): array {
		$vars = get_object_vars($this);
		
		// unset the NotificationEventHandler as it can't be serialized and isn't needed during processing of the notification
		unset($vars['params']['handler']);
		
		return $vars;
	}
}
