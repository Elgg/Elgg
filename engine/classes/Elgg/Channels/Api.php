<?php
namespace Elgg\Channels;

use Elgg\Database;
use Elgg\Database\Datalist;
use ElggCrypto;
use Elgg\PluginHooksService;

class Api {

	/**
	 * @var string
	 */
	protected $hmac_key;

	/**
	 * @var string
	 */
	protected $session_id;

	/**
	 * @var Database
	 */
	protected $db;

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var int[]
	 */
	protected $channel_id_cache = [];

	/**
	 * @var array (channels are keys)
	 */
	protected $client_channels = [];

	/**
	 * Constructor
	 *
	 * @param string             $hmac_key   HMAC key
	 * @param string             $session_id Session ID
	 * @param PluginHooksService $hooks      Hooks service
	 * @param Database           $db         Database
	 * @access private
	 * @internal
	 */
	public function __construct($hmac_key, $session_id, PluginHooksService $hooks, Database $db) {
		$this->hmac_key = $hmac_key;
		$this->session_id = $session_id;
		$this->hooks = $hooks;
		$this->db = $db;
	}

	/**
	 * Send a message to a push channel, auto-creates the channel if necessary
	 *
	 * @param string $channel Channel name
	 * @param array  $data    JSON-serializable data (not false)
	 *
	 * @return void
	 */
	public function sendMessage($channel, array $data) {
		$channel = trim($channel);

		$params = [
			'channel' => $channel,
			'original_data' => $data,
		];
		$data = $this->hooks->trigger('elgg_channels:send', $channel, $params, $data);
		if ($data === false) {
			return;
		}

		$channel_id = $this->getChannelId($channel);
		$json = json_encode($data, JSON_UNESCAPED_SLASHES);

		$this->db->insertData("
			INSERT INTO {$this->db->prefix}channel_messages
			(channel_id, data, time_created) VALUES
			(:channel_id, :data, :time_created)
		", [
			':channel_id' => $channel_id,
			':data' => $json,
			':time_created' => time(),
		]);
	}

	/**
	 * Prepare the client for requesting new messages on this channel
	 *
	 * @param string $channel Channel name
	 *
	 * @return void
	 */
	public function setupClient($channel) {
		$channel = trim($channel);

		$params = [
			'channel' => $channel,
		];
		if (!$this->hooks->trigger('elgg_channels:setupClient', $channel, $params, true)) {
			return;
		}

		if (!$this->session_id) {
			// can't authenticate
			return;
		}

		if (!$this->client_channels) {
			// register for hook
			$this->hooks->registerHandler('elgg.data', 'page', [$this, 'populateElggData']);
			elgg_require_js('elgg/channels');
		}

		$channel = trim($channel);
		$this->getChannelId($channel);
		$this->client_channels[$channel] = true;
	}

	/**
	 * Get the key to use for HMAC in authenticating connections
	 *
	 * @return string
	 */
	public function getHmacKey() {
		return $this->hmac_key;
	}

	/**
	 * Get a channel ID
	 *
	 * @param string $name Channel name
	 *
	 * @return int
	 */
	protected function getChannelId($name) {
		$name = trim($name);

		if (empty($this->channel_id_cache[$name])) {
			$row = $this->db->getDataRow("
				SELECT id
				FROM {$this->db->prefix}channels
				WHERE name = :name
			", null, [':name' => $name]);

			if (!$row) {
				$id = $this->db->insertData("
					INSERT INTO {$this->db->prefix}channels
					(name) VALUES (:name)
				", [':name' => $name]);

				$row = (object)[
					'id' => $id,
				];
			}

			$this->channel_id_cache[$name] = (int)$row->id;
		}

		return $this->channel_id_cache[$name];
	}

	/**
	 * Send channel data to the client
	 *
	 * @param string $hook   "elgg.data"
	 * @param string $type   "page"
	 * @param array  $value  Value
	 * @param array  $params Hook params
	 *
	 * @return array
	 * @access private
	 * @internal
	 */
	public function populateElggData($hook, $type, $value, $params) {
		foreach (array_keys($this->client_channels) as $channel) {
			$id = $this->getChannelId($channel);

			// TODO can do this in 1 query
			$row = $this->db->getDataRow("
				SELECT id
				FROM {$this->db->prefix}channel_messages
				WHERE channel_id = :channel_id
				ORDER BY id DESC
				LIMIT 1
			", null, [':channel_id' => $id]);

			$last_message_id = $row ? (int)$row->id : 0;

			$value['elgg_channels']['channels'][] = [
				'name' => $channel,
				'id' => $id,
				'mac' => hash_hmac('sha256', "$id,{$this->session_id}", $this->hmac_key),
				'last_message_id' => $last_message_id,
			];
		}

		return $value;
	}

	/**
	 * Get/Populate the HMAC key
	 *
	 * @param Datalist   $datalist Datalist service
	 * @param ElggCrypto $crypto   Crypto service
	 *
	 * @return string
	 * @access private
	 * @internal
	 */
	public static function initHmacKey(Datalist $datalist, ElggCrypto $crypto) {
		$name = 'channels_hmac_key';
		$key = $datalist->get($name);
		if (!$key) {
			$key = $crypto->getRandomString(40);
			$datalist->set($name, $key);
		}
		return $key;
	}
}
