<?php

namespace Elgg\Email;

use Elgg\Database\DelayedEmailQueueTable;
use Elgg\Email;
use Elgg\Email\DelayedQueue\DatabaseRecord;
use Elgg\EmailService;
use Elgg\Exceptions\DatabaseException;
use Elgg\I18n\Translator;
use Elgg\Invoker;
use Elgg\Notifications\Notification;
use Elgg\Traits\Loggable;
use Elgg\ViewsService;

/**
 * Handle storing and processing delayed emails
 *
 * @since 4.0
 * @internal
 */
class DelayedEmailService {
	
	use Loggable;
	
	/**
	 * @var DelayedEmailQueueTable
	 */
	protected $queue_table;
	
	/**
	 * @var EmailService
	 */
	protected $email;
	
	/**
	 * @var ViewsService
	 */
	protected $views;
	
	/**
	 * @var Translator
	 */
	protected $translator;
	
	/**
	 * @var Invoker
	 */
	protected $invoker;
	
	/**
	 * Create a new service
	 *
	 * @param DelayedEmailQueueTable $queue_table the queue database table
	 * @param EmailService           $email       email service
	 * @param ViewsService           $views       views service
	 * @param Translator             $translator  translator service
	 * @param Invoker                $invoker     invoker serivce
	 */
	public function __construct(DelayedEmailQueueTable $queue_table, EmailService $email, ViewsService $views, Translator $translator, Invoker $invoker) {
		$this->queue_table = $queue_table;
		$this->email = $email;
		$this->views = $views;
		$this->translator = $translator;
		$this->invoker = $invoker;
	}
	
	/**
	 * Queue a notification for delayed email delivery
	 *
	 * @param Notification $notification the notification
	 *
	 * @return bool
	 */
	public function enqueueNotification(Notification $notification): bool {
		$recipient = $notification->getRecipient();
		
		$delivery_interval = $recipient->getPrivateSetting('delayed_email_interval') ?? 'daily';
		
		try {
			return $this->queue_table->queueEmail($recipient->guid, $delivery_interval, $notification);
		} catch (DatabaseException $e) {
			$this->getLogger()->error($e);
		}
		
		return false;
	}
	
	/**
	 * Send out notifications for the given delivery_interval
	 *
	 * @param string $delivery_interval the delivery interval to process
	 * @param int    $timestamp         the timestamp until which queued notifications should be picked up
	 *
	 * @return int number of notifications handled
	 */
	public function processQueuedNotifications(string $delivery_interval, int $timestamp): int {
		// processing could take a while
		set_time_limit(0);
		
		return ($this->invoker->call(ELGG_IGNORE_ACCESS, function() use ($delivery_interval, $timestamp) {
			$count = 0;
			$last_recipient_guid = null;
			$notifications = [];
			
			// process one recipient
			$processRecipient = function($row = null) use (&$last_recipient_guid, &$notifications, $delivery_interval, $timestamp) {
				$this->processRecipientNotifications($last_recipient_guid, $notifications, $delivery_interval);
				
				// cleanup the queue for this recipient
				$this->queue_table->deleteRecipientRows($last_recipient_guid, $delivery_interval, $timestamp);
				
				// start collecting data for the new recipient
				$last_recipient_guid = $row ? $row->recipient_guid : null;
				$notifications = [];
			};
			
			$rows = $this->queue_table->getIntervalRows($delivery_interval, $timestamp);
			
			/* @var $row DatabaseRecord */
			foreach ($rows as $row) {
				$count++;
				
				if (!isset($last_recipient_guid)) {
					$last_recipient_guid = $row->recipient_guid;
				} elseif ($last_recipient_guid !== $row->recipient_guid) {
					// process one recipient
					$processRecipient($row);
				}
				
				$notfication = $row->getNotification();
				if (!$notfication instanceof Notification) {
					continue;
				}
				
				$notifications[] = $notfication;
			}
			
			if (isset($last_recipient_guid)) {
				$processRecipient();
			}
			
			return $count;
		}));
	}
	
	/**
	 * Send out the combined email notification for a given recipient
	 *
	 * @param int    $recipient_guid    the GUID of the recipient
	 * @param array  $notifications     all delayed notifications for the recipient in the given delivery_interval
	 * @param string $delivery_interval the delivery interval
	 *
	 * @return bool
	 */
	protected function processRecipientNotifications(int $recipient_guid, array $notifications, string $delivery_interval): bool {
		$recipient = get_entity($recipient_guid);
		if (!$recipient instanceof \ElggEntity || !isset($recipient->email)) {
			return false;
		}
		
		$view_vars = [
			'recipient' => $recipient,
			'notifications' => $notifications,
			'delivery_interval' => $delivery_interval,
		];
		
		$body = $this->views->renderView('email/delayed_email/plaintext', $view_vars);
		if (empty($body)) {
			return true;
		}
		
		$html_body = $this->views->renderView('email/delayed_email/html', $view_vars);
		
		$email = Email::factory([
			'to' => $recipient,
			'subject' => $this->translator->translate("notifications:delayed_email:subject:{$delivery_interval}", [], $recipient->language),
			'body' => $body,
			'params' => [
				'html_body' => $html_body,
			],
		]);
		
		return $this->email->send($email);
	}
}
