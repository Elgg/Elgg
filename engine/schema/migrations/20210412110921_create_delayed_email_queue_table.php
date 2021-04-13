<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class CreateDelayedEmailQueueTable extends AbstractMigration {
    
	/**
	 * CREATE TABLE `prefix_delayed_email_queue` (
	 * `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	 * `recipient_guid` bigint(20) unsigned NOT NULL,
	 * `delivery_interval` varchar(255) NOT NULL,
	 * `data` mediumblob NOT NULL,
	 * `timestamp` int(11) NOT NULL,
	 * PRIMARY KEY (`id`),
	 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	 */
    public function change() {
    	if ($this->hasTable('delayed_email_queue')) {
    		return;
    	}
    	
    	// table
    	$table = $this->table('delayed_email_queue', [
    		'engine' => 'InnoDB',
    		'encoding' => 'utf8mb4',
    		'collation' => 'utf8mb4_general_ci',
    		'signed' => false,
    	]);
    	
    	// add columns
    	$table->addColumn('recipient_guid', MysqlAdapter::PHINX_TYPE_BIG_INTEGER, [
    		'null' => false,
    		'limit' => MysqlAdapter::INT_BIG,
    		'precision' => 20,
    		'signed' => false,
    	]);
    	
    	$table->addColumn('delivery_interval', MysqlAdapter::PHINX_TYPE_STRING, [
    		'null' => false,
    		'limit' => MysqlAdapter::INT_TINY,
    	]);
    	
    	$table->addColumn('data', MysqlAdapter::PHINX_TYPE_BLOB, [
    		'null' => false,
    		'limit' => MysqlAdapter::BLOB_MEDIUM,
    	]);
    	
    	$table->addColumn('timestamp', MysqlAdapter::PHINX_TYPE_INTEGER, [
    		'null' => false,
    		'limit' => MysqlAdapter::INT_REGULAR,
    		'precision' => 11,
    	]);
    	
    	// add indexes
    	$table->addIndex(['recipient_guid'], [
    		'name' => 'recipient_guid',
    		'unique' => false,
    	]);
    	$table->addIndex(['delivery_interval'], [
    		'name' => 'delivery_interval',
    		'unique' => false,
    	]);
    	$table->addIndex(['recipient_guid', 'delivery_interval'], [
    		'name' => 'recipient_interval',
    		'unique' => false,
    	]);
    	
    	// create
    	$table->save();
    }
}
