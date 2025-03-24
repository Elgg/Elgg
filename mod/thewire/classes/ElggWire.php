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
		$this->attributes['access_id'] = ACCESS_PUBLIC;
		
		$this->attributes['method'] = 'site'; // @todo remove this in Elgg 7.0
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggObject::getDisplayName()
	 */
	public function getDisplayName(): string {
		return elgg_get_excerpt((string) $this->description, 25);
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
	
	/**
	 * {@inheritdoc}
	 */
	public static function getDefaultFields(): array {
		$result = parent::getDefaultFields();
		
		$char_limit = (int) elgg_get_plugin_setting('limit', 'thewire');
		
		$result[] = [
			'#type' => 'longtext',
			'name' => 'description',
			'class' => 'thewire-textarea',
			'rows' => ($char_limit === 0 || $char_limit > 140) ? 3 : 2,
			'data-max-length' => $char_limit,
			'required' => true,
			'placeholder' => elgg_echo('thewire:form:body:placeholder'),
			'editor_type' => 'thewire',
		];
		
		return $result;
	}
}
