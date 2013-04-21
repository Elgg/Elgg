<?php
/**
 * Notification container
 * 
 * @package    Elgg.Core
 * @subpackage Notifications
 * @since      1.9.0
 */
class Elgg_Notifications_Notification {
	/** @var ElggEntity The entity causing or creating the notification */
	protected $from;

	/** @var ElggUser The user receiving the notification */
	protected $to;

	/** @var string The subject string */
	public $subject;

	/** @var string The body string */
	public $body;

	/** @var string The language of the notification */
	public $language;

	/** @var array Additional parameters */
	public $params;

	/**
	 * Create a notification
	 *
	 * @param ElggEntity $from     The entity sending the notification (usually the site)
	 * @param ElggEntity $to       The entity receiving the notification
	 * @param string     $language The language code for the notification
	 * @param string     $subject  The subject of the notification
	 * @param string     $body     The body of the notification
	 * @param array      $params   Optional array of parameters
	 */
	public function __construct($from, $to, $language, $subject, $body, array $params = array()) {
		$this->from = $from;
		$this->to = $to;
		$this->language = $language;
		$this->subject = $subject;
		$this->body = $body;
		$this->params = $params;
	}

	/**
	 * Get the sender entity
	 *
	 * @return ElggEntity
	 */
	public function getSender() {
		return $this->from;
	}

	/**
	 * Get the recipient entity
	 *
	 * @return ElggEntity
	 */
	public function getRecipient() {
		return $this->to;
	}

	/**
	 * Get the formatted address for sender: "Name <email address>"
	 *
	 * @return string
	 */
	public function getSenderFormattedEmailAddress() {
		return $this->getEmailAddress($this->from);
	}

	/**
	 * Get the formatted address for recipient: "Name <email address>"
	 *
	 * @return string
	 */
	public function getRecipientFormattedEmailAddress() {
		return $this->getEmailAddress($this->to);
	}

	/**
	 * Get an email address string for to/from field
	 * 
	 * @todo this should not be here
	 * 
	 * @param ElggUser|ElggGroup|ElggSite $entity Entity to get the email address for 
	 * @return string
	 */
	protected function getFormattedEmailAddress($entity) {
		// need to remove special characters
		$name = $entity->name;
		$email = $entity->email;
		return "$name <$email>";
	}
}
