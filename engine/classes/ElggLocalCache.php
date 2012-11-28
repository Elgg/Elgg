<?php
/**
 * PHP in-memory cache for various uses 
 */
class ElggLocalCache extends ElggCache {

	/**
	 * @var array
	 */
	protected $data = array();
	
	/**
	 * (non-PHPdoc)
	 * @see ElggCache::save()
	 */
	public function save($key, $data) {
		$this->data[$key] = $data;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ElggCache::load()
	 */
	public function load($key, $offset = 0, $limit = null) {
		return $this->data[$key];
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ElggCache::delete()
	 */
	public function delete($key) {
		unset($this->data[$key]);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ElggCache::clear()
	 */
	public function clear() {
		$this->data = array();
	}
	
	/**
	 * @var bool
	 */
	private $isPopulated = false;
	
	/**
	 * Fills cache with provided values
	 * @param array $values
	 */
	public function populate($values = array()) {
		if (is_array($values)) {
			foreach ($values as $key => $val) {
				$this->save($key, $val);
			}
		}
		$this->isPopulated = true;
	}
	
	/**
	 * @return bool is cache already populated with data
	 */
	public function isPopulated() {
		return $this->isPopulated;
	}
}