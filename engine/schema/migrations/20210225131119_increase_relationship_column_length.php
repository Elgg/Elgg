<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class IncreaseRelationshipColumnLength extends AbstractMigration {
	
    /**
     * Update the relationship column to a larger size
     */
    public function change() {
    	if (!$this->hasTable('entity_relationships')) {
    		return;
    	}
    	
    	$table = $this->table('entity_relationships');
    	if (!$table->hasColumn('relationship')) {
    		return;
    	}
    	
    	$table->changeColumn('relationship', 'string', [
    		'limit' => MysqlAdapter::TEXT_SMALL,
    	]);
    	$table->update();
    }
}
