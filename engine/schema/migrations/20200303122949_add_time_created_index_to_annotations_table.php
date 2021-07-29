<?php

use Phinx\Migration\AbstractMigration;

class AddTimeCreatedIndexToAnnotationsTable extends AbstractMigration {
    
	/**
     * Adds an index to the annotations table for the time_created column
     */
	public function change() {
		if (!$this->hasTable('annotations')) {
			return;
		}
		
		$table = $this->table('annotations');
		if ($table->hasIndexByName('time_created')) {
			return;
		}
		
		$table->addIndex(['time_created'], [
			'name' => 'time_created',
			'unique' => false,
		]);
		
		$table->save();
    }
}
