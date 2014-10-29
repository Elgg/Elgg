<?php
namespace Elgg\I18n;

use Elgg\Filesystem\Filesystem;


/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @package    Elgg.Core
 * @subpackage I18n
 * @since      1.10.0
 *
 * @access private
 */
final class Loader {
	/** @var Filesystem[] */
	private $directories = array();
	
	/** @var \ElggCache */
	private $cache;
	
	/** @var array */
	private $uncachedTranslations = array();
	
	/** @var array */
	private $localTranslationCache = array();
	
	/**
	 * Create the translation loader
	 *
	 * @param \ElggCache $cache Cache for translations
	 */
	public function __construct(\ElggCache $cache = null) {
		$this->cache = $cache;
	}
	
	/**
	 * Caches translations when appriopiate
	 */
	public function __destruct() {
		if ($this->cache && $this->uncachedTranslations) {
			foreach ($this->uncachedTranslations as $language => $translation) {
				$this->cache->setVariable("$language.lang", serialize($translation));
			}
		}
	}
	
	/**
	 * Add a translation directory
	 *
	 * @param Filesystem $directory directory with language files
	 * 
	 * @return void
	 */
	public function addDirectory(Filesystem $directory) {
		$this->directories[] = $directory;
	}
	
	/**
	 * Load a translation
	 *
	 * @param Locale $language Two letter language code
	 * 
	 * @return Map
	 */
	public function loadTranslation(Locale $language) {
		if ($this->cache) {
			$data = $this->cache->getVariable("$language.lang");
			if ($data) {
				$translations = new Map();
				$translation->addTranslations(unserialize($data));
				return $translations;
			}
		}
		
		$translations = new Map();
		
		foreach ($this->directories as $dir) {
			$language_file = $dir->getFile("$language.php");
			if ($this->isCached($language_file)) {
				$translations->addTranslations($this->getCache($language_file));
			} else if ($language_file->exists()) {
				$result = $language_file->includeFile();
				if (is_array($result)) {
					// Elgg 1.9-style language file
					$this->setCache($language_file, $result);
					$translations->addTranslations($result);
				} else {
					// Elgg 1.0-1.8-style (add_translation) 
				}
			} else {
				// Not every language directory needs to have every language
			}
		}
		
		if ($this->cache) {
			$this->uncachedTranslations[$language] = $translations;
		}
		
		return $translations;
	}
	
	/**
	 * Get all languages available
	 *
	 * @return Locale[]
	 */
	public function getAllLocales() {
		$languages = array();
		
		foreach ($this->directories as $dir) {
			$files = $dir->getFiles();
			
			$languages_in_this_dir = array_map(function($file) {
				if ($file->getExtension() !== 'php') {
					return NULL;
				}
				
				try {
					return Locale::parse($file->getBasename(".php"));
				} catch (Exception $e) {
					return NULL;
				}
			}, $files);
			
			foreach ($languages_in_this_dir as $lang) {
				if (isset($lang)) {
					$languages[$lang] = true;
				}
			}
		}
		
		return array_keys($languages);
	}
	
	/**
	 * Is this translation file cached
	 *
	 * @param string $file Translation file
	 * 
	 * @return bool
	 */
	private function isCached($file) {
		return isset($this->localTranslationCache["$file"]);
	}
	
	/**
	 * Set the cached translation for this file
	 *
	 * @param string $file  Translation file
	 * @param array  $value Value to cache
	 * @return void
	 */
	private function setCache($file, $value) {
		$this->localTranslationCache["$file"] = $value;
	}
	
	/**
	 * Get the cached translation for this file
	 *
	 * @param string $file Translation file
	 * 
	 * @return array
	 */
	private function getCache($file) {
		return $this->localTranslationCache["$file"];
	}
}
