<?php

namespace Elgg\Mocks\Database;

class MetastringsTable extends \Elgg\Database\MetastringsTable {

	/**
	 * @var int
	 */
	public $iterator = 0;

	/**
	 * @var array
	 */
	public $mocks = [];

	/**
	 * {@inheritdoc}
	 */
	public function add($string) {

		$string = trim($string);

		$this->iterator++;

		$this->db->addQuerySpec([
			'sql' => "INSERT INTO {$this->getTableName()} (string) VALUES (:string)",
			'params' => [
				':string' => $string,
			],
			'insert_id' => $this->iterator,
		]);

		$id = parent::add($string);
		if (!$id) {
			return false;
		}

		$this->mocks[$id] = [
			'id' => $id,
			'string' => $string,
		];

		// Case sensitive SELECT
		$this->db->addQuerySpec([
			'sql' => "SELECT id FROM {$this->getTableName()} WHERE string = BINARY :string LIMIT 1",
			'params' => [
				':string' => $string,
			],
			'results' => function() use ($id, $string) {
				return [
					(object) [
						'id' => $id,
						'string' => $string,
					],
				];
			},
		]);

		// Case insensitive SELECT
		$this->db->addQuerySpec([
			'sql' => "SELECT id FROM {$this->getTableName()} WHERE string = :string",
			'params' => [
				':string' => $string,
			],
			'results' => function() use ($string) {
				$return = [];
				foreach ($this->mocks as $id => $row) {
					if (strcasecmp($string, $row['string']) === 0) {
						$return[] = (object) $row;
					}
				}
				return !empty($return) ? $return : null;
			},
		]);

		return parent::add($string);
	}

}
