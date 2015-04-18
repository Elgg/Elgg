<?php
namespace Elgg\Filesystem\Adapter\MsAzure;
use Gaufrette\Adapter\AzureBlobStorage;

/**
 * Use our own adapter until this is fixed: 
 * https://github.com/KnpLabs/Gaufrette/issues/336
 */
class GaufretteAdapter extends AzureBlobStorage {
	
	/**
	 * @inheritdoc
	 */
	public function write($key, $content) {
		if (!strlen($content) === 0) {
			return;
		}
		
		return parent::write($key, $content);
	}
}