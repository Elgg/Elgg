<?php
namespace Elgg\Notifications;

/**
 * Notification container
 *
 * @package    Elgg.Core
 * @subpackage Notifications
 * @since      1.10
 */
class Notification {

	const ORIGIN_SUBSCRIPTIONS = 'subscriptions_service';
	const ORIGIN_INSTANT = 'instant_notifications';
	
	/** @var \ElggEntity The entity causing or creating the notification */
	protected $from;

	/** @var \ElggUser The user receiving the notification */
	protected $to;

	/** @var string A single sentence summary string */
	public $summary;

	/** @var string The subject of the notification. Email subject is one use. */
	public $subject;

	/** @var string The body of the notification. Email body is one use. */
	public $body;

	/** @var string The language of the notification */
	public $language;

	/** @var array Additional parameters */
	public $params;

	/** @var string Target URL */
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
	 * @throws \InvalidArgumentException
	 */
	public function __construct(\ElggEntity $from, \ElggEntity $to, $language, $subject, $body, $summary = '', array $params = []) {
		if (!$from) {
			throw new \InvalidArgumentException('$from is not a valid \ElggEntity');
		}
		if (!$to) {
			throw new \InvalidArgumentException('$to is not a valid \ElggEntity');
		}
		$this->from = $from;
		$this->to = $to;
		$this->language = $language;
		$this->subject = $subject;
		$this->body = $body;
		$this->summary = $summary;
		$this->params = $params;

		if (isset($this->params['url'])) {
			$this->url = $this->params['url'];
		}
	}

	/**
	 * Get the sender entity
	 *
	 * @return \ElggEntity
	 */
	public function getSender() {
		return $this->from;
	}

	/**
	 * Get the sender entity guid
	 *
	 * @return int
	 */
	public function getSenderGUID() {
		return $this->from->guid;
	}

	/**
	 * Get the recipient entity
	 *
	 * @return \ElggEntity
	 */
	public function getRecipient() {
		return $this->to;
	}

	/**
	 * Get the recipient entity guid
	 *
	 * @return int
	 */
	public function getRecipientGUID() {
		return $this->to->guid;
	}

	/**
	 * Export notification
	 * @return \stdClass
	 */
	public function toObject() {
		$obj = new \stdClass();
		$vars = get_object_vars($this);
		$vars = array_merge($this->params, $vars);
		unset($vars['params']);
		unset($vars['sender']);
		unset($vars['recipient']);
		unset($vars['subscriptions']);
		unset($vars['action']);
		unset($vars['object']);
		foreach ($vars as $key => $value) {
			if (is_object($value) && is_callable([$value, 'toObject'])) {
				$obj->$key = $value->toObject();
			} else {
				$obj->$key = $value;
			}
		}
		return $obj;
	}
}
