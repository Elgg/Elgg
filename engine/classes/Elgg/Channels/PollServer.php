<?php
namespace Elgg\Channels;

use Elgg\Database\PdoFactory;

class PollServer {
	const MAX_CHANNELS_PER_REQUEST = 50;

	/**
	 * Handle a push API request
	 *
	 * @param array  $post      $_POST array
	 * @param array  $cookie    $_COOKIE array
	 * @param string $root_path Path to Elgg installation
	 *
	 * @return void
	 */
	public function serve(array $post, array $cookie, $root_path) {

		list ($pdo, $prefix) = (new PdoFactory())->fromRootPath($root_path, 'read');
		/* @var \PDO $pdo */

		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		if (empty($cookie['Elgg']) || !is_string($cookie['Elgg'])) {
			die('Session cookie missing/invalid');
		}

		if (empty($post['channels'])
				|| !is_array($post['channels'])
				|| count($post['channels']) > self::MAX_CHANNELS_PER_REQUEST) {
			die('Invalid request');
		}

		$channels = [];
		foreach ($post['channels'] as $channel) {
			if (!is_string($channel)) {
				die('Invalid request');
			}
			$parts = explode(',', $channel);
			if (count($parts) !== 3) {
				die('Invalid request');
			}
			$channels[] = [
				'id' => (int)$parts[0],
				'given_mac' => $parts[1],
				'last_message_id' => (int)$parts[2],
			];
		}

		$sql = "
			SELECT d.value
			FROM {$prefix}datalists d
			WHERE d.name = 'channels_hmac_key'
		";
		$row = $pdo->query($sql, \PDO::FETCH_ASSOC)->fetch();
		if ($row) {
			$hmac_key = $row['value'];
		} else {
			die('No HMAC key set');
		}

		$wheres = [];
		foreach ($channels as $channel) {
			$wheres[] = "(m.channel_id = {$channel['id']} && m.id > {$channel['last_message_id']})";
		}
		$where = implode(' OR ', $wheres);
		$sql = "
			SELECT m.id, m.data, m.channel_id
			FROM {$prefix}channel_messages m
			WHERE $where
			ORDER BY m.id
		";
		$stmt = $pdo->query($sql, \PDO::FETCH_ASSOC);
		$data = [];
		foreach ($stmt as $row) {
			$data[$row['channel_id']][] = $row;
		}

		// assemble JSON by hand to avoid overhead of JSON-decoding MySQL rows and re-encoding
		$channel_objs = array_map(function ($channel) use ($data, $hmac_key, $cookie) {
			// validate MAC
			$mac = hash_hmac('sha256', "{$channel['id']},{$cookie['Elgg']}", $hmac_key);
			if ($mac !== $channel['given_mac']) {
				die('Invalid MAC');
			}

			$messages = [];
			if (!empty($data[$channel['id']])) {
				$messages = array_map(function ($row) {
					return "[{$row['id']}, {$row['data']}]";
				}, $data[$channel['id']]);
			}

			return "{\"id\":{$channel['id']},\"messages\":[" . implode(',', $messages) . "]}";
		}, $channels);

		header('Content-Type: application/json;charset=utf-8');
		echo "[" .implode(',', $channel_objs) . "]";
	}
}
