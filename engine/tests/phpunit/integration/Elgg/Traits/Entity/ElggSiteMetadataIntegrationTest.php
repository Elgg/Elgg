<?php

namespace Elgg\Traits\Entity;

use Elgg\Traits\Entity\MetadataIntegrationTestCase;

class ElggSiteMetadataIntegrationTest extends MetadataIntegrationTestCase {
	
	protected array $initial_metadata_names = [];
	
	public function up() {
		parent::up();
		
		// Sites don't get seeded, therefor we need to clean up in the end
		$metadata = elgg_get_metadata([
			'guid' => $this->entity->guid,
			'limit' => false,
		]);
		$names = [];
		foreach ($metadata as $md) {
			$names[] = $md->name;
		}
		$this->initial_metadata_names = array_unique($names);
	}
	
	public function down() {
		$metadata = elgg_get_metadata([
			'guid' => $this->entity->guid,
			'limit' => false,
		]);
		foreach ($metadata as $md) {
			if (in_array($md->name, $this->initial_metadata_names)) {
				continue;
			}
			
			unset($this->entity->{$md->name});
		}
		
		parent::down();
	}
	
	protected function getEntity(): \ElggEntity {
		return $this->createSite();
	}
	
	protected function getUnsavedEntity(): \ElggEntity {
		return new \ElggSite();
	}
}
