<?php

/**
 * Custom class for external pages
 *
 * @since 6.3
 */
class ElggExternalPage extends \ElggObject {

	const string SUBTYPE = 'external_page';
	
	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = self::SUBTYPE;
		$this->attributes['access_id'] = ACCESS_PUBLIC;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getURL(): string {
		return elgg_generate_url("view:object:external_page:{$this->title}");
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getDefaultFields(): array {
		$result = parent::getDefaultFields();

		$result[] = [
			'#type' => 'hidden',
			'name' => 'title',
			'required' => true,
		];

		$result[] = [
			'#type' => 'longtext',
			'#label' => elgg_echo('description'),
			'name' => 'description',
		];

		return $result;
	}

	/**
	 * Returns allowed names available on this site
	 *
	 * @return array
	 *
	 * @since 7.0
	 */
	public static function getAllowedPageNames(): array {
		static $result;
		
		if (!isset($result)) {
			$result = elgg_trigger_event_results('names', 'externalpages', [], ['about', 'terms', 'privacy']);
		}
		
		return $result;
	}
}
