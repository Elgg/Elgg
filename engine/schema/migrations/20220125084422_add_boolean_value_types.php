<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddBooleanValueTypes extends AbstractMigration {
	/**
	 * Change Method.
	 *
	 * Write your reversible migrations using this method.
	 *
	 * More information on writing migrations is available here:
	 * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
	 *
	 * Remember to call "create()" or "update()" and NOT "save()" when working
	 * with the Table class.
	 */
	public function change(): void {
		
		$metadata = $this->table('metadata');
		$metadata->changeColumn('value_type', 'enum', ['values' => ['integer', 'text', 'bool']])->update();

		$annotations = $this->table('annotations');
		$annotations->changeColumn('value_type', 'enum', ['values' => ['integer', 'text', 'bool']])->update();
	}
}
