<?php

/**
 * Report
 *
 * @property string $address URL of content
 * @property string $state   State of report. "active" or "archived"
 */
class ElggReportedContent extends ElggObject {

	/**
	 * {@inheritDoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'reported_content';
		$this->attributes['access_id'] = ACCESS_PRIVATE;
	}
	
	/**
	 * Returns the entity related to this report
	 *
	 * @return \ElggEntity|null
	 */
	public function getRelatedEntity(): ?\ElggEntity {
		$related_entity = $this->getEntitiesFromRelationship([
			'relationship' => 'reportedcontent',
			'limit' => 1,
		]);
		
		return elgg_extract(0, $related_entity);
	}
	
	/**
	 * Returns the related entity url or the address saved with the report
	 *
	 * @return string
	 */
	public function getAddress(): string {
		$entity = $this->getRelatedEntity();
		
		return $entity ? $entity->getURL() : (string) $this->address;
	}
}
