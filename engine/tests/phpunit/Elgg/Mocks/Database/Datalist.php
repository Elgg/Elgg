<?php

namespace Elgg\Mocks\Database;

class Datalist extends \Elgg\Database\Datalist {

	/**
	 * {@inheritdoc}
	 */
	public function set($name, $value) {

		$sql = "
			INSERT INTO {$this->table}
			SET name = :name, value = :value
			ON DUPLICATE KEY UPDATE value = :value
		";
		$params = [
			':name' => $name,
			':value' => $value,
		];

		$this->db->addQuerySpec([
			'sql' => $sql,
			'params' => $params,
			'insert_id' => 1,
		]);

		if ($result = parent::set($name, $value)) {

			$sql = "SELECT * FROM {$this->table} WHERE name = :name";
			$params = [
				':name' => $name,
			];

			$this->db->addQuerySpec([
				'sql' => $sql,
				'params' => $params,
				'results' => function() use ($name, $value) {
					return [
						(object) [
							'name' => $name,
							'value' => $value,
						],
					];
				}
			]);

		}

		return $result;
	}
}
