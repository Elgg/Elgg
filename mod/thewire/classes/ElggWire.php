<?php
/**
 * ElggWire Class
 *
 * @property string $method      The method used to create the wire post (site, sms, api)
 * @property bool   $reply       Whether this wire post was a reply to another post
 * @property int    $wire_thread The identifier of the thread for this wire post
 */
class ElggWire extends ElggObject {

	/**
	 * Set subtype to thewire
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'thewire';
	}

	/**
	 * {@inheritDoc}
	 * @see ElggObject::canComment()
	 */
	public function canComment($user_guid = 0, $default = null) {
		return false;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggObject::getDisplayName()
	 */
	public function getDisplayName() {
		return elgg_get_excerpt($this->description, 25);
	}
	
	/**
	 * Returns the parent entity if available
	 *
	 * @return \ElggWire|null
	 */
	public function getParent(): ?\ElggWire {
		$parents = elgg_get_entities([
			'type' => 'object',
			'subtype' => $this->subtype,
			'relationship' => 'parent',
			'relationship_guid' => $this->guid,
			'limit' => 1,
		]);
		
		return $parents ? $parents[0] : null;
	}
}
