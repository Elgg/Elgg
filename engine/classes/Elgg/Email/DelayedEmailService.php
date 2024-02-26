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
	
	protected const NOTIFICATIONS_BATCH_SIZE = 500;
		
	/**
	 * Create a new service
	 *
	 * @param DelayedEmailQueueTable $queue_table the queue database table
	 * @param EmailService           $email       email service
	 * @param ViewsService           $views       views service
	 * @param Translator             $translator  translator service
	 * @param Invoker                $invoker     invoker serivce
	 */
	public function __construct(
		protected DelayedEmailQueueTable $queue_table,
		protected EmailService $email,
		protected ViewsService $views,
		protected Translator $translator,
		protected Invoker $invoker
	) {
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
		
		$delivery_interval = $recipient->delayed_email_interval ?: 'daily';
		
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
			
			// process one recipient
			$processRecipient = function(int $recipient_guid, array $notifications, int $max_id) use ($delivery_interval, $timestamp) {
				try {
					$this->processRecipientNotifications($recipient_guid, $notifications, $delivery_interval);
				} catch (\Throwable $t) {
					$this->getLogger()->error($t);
				}
				
				// cleanup the queue for this recipient
				return $this->queue_table->deleteRecipientRows($recipient_guid, $delivery_interval, $timestamp, $max_id);
			};
			
			// get the next recipient to process
			$recipient_guid = $this->queue_table->getNextRecipientGUID($delivery_interval, $timestamp);
			while ($recipient_guid > 0) {
				// get a notification batch to process for this recipient
				$rows = $this->queue_table->getRecipientRows($recipient_guid, $delivery_interval, $timestamp, self::NOTIFICATIONS_BATCH_SIZE);
				while (!empty($rows)) {
					$notifications = [];
					$max_id = 0;
					foreach ($rows as $row) {
						$max_id = max($max_id, $row->id);
						
						$notification = $row->getNotification();
						if (!$notification instanceof Notification) {
							continue;
						}
						
						$notifications[] = $notification;
					}
					
					// send all notifications in this batch
					$count += $processRecipient($recipient_guid, $notifications, $max_id);
					
					// get next batch
					$rows = $this->queue_table->getRecipientRows($recipient_guid, $delivery_interval, $timestamp, static::NOTIFICATIONS_BATCH_SIZE);
				}
				
				// get next recipient to process
				$recipient_guid = $this->queue_table->getNextRecipientGUID($delivery_interval, $timestamp);
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
			'subject' => $this->translator->translate("notifications:delayed_email:subject:{$delivery_interval}", [], (string) $recipient->language),
			'body' => $body,
			'params' => [
				'html_body' => $html_body,
			],
		]);
		
		return $this->email->send($email);
	}
}
