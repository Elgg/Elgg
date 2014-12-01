<?php
namespace Elgg\Sql\MySql;

class InsertStatement implements Sql\InsertStatement {
	
	/** @var Database */
	private $db;
	
	/** @var string */
	private $tableName;
	
	/**
	 * @param Database $db        The mysql database this insert statement works on.
	 * @param string   $tableName The name of the table (including any prefix).
	 */
	public function __construct(Database $db, $tableName) {
		$this->db = $db;
		$this->tableName = $tableName;
	}
	
	public function onDuplicateKeyUpdate(array $values) {
		$this->onDuplicateKeyUpdate = $values;
	}
	
	/**
	 * Autoescapes values in column => value pairs.
	 * 
	 * @param array $values Column => value pairs to insert into this table.
	 * 
	 * @return mixed The ID of the new row.
	 */
	public function values(array $values) {
		$map = new ArrayMap($values);
		$query = "INSERT INTO `$tableName` ";
		$query .= "(" . $map->keys()->join(', ') . ")";
		$query .= " VALUES ";
		$query .= "(" . $map->map([$this->db, 'quote']) . ")";
		
		if (!empty($))
		
		$this->db->write($query);
		
		// TODO(ewinslow): Check for successful query first.
		return $this->db->getLastInsertId();
	}
}
