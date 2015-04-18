<?php

namespace Elgg\Filesystem\Adapter\MsAzure;

use Gaufrette\Adapter\AzureBlobStorage\BlobProxyFactoryInterface;
use WindowsAzure\Common\ServicesBuilder;

/**
 * Implementation of a BlobProxyFactory for Gaufrette that accepts service filters
 */
class ElggBlobProxyFactory implements BlobProxyFactoryInterface {
    /**
     * @var string $connectionString
     */
    protected $connectionString;
	
	/**
	 * @var null|IServiceFilter
	 */
	protected $filter;

    /**
     * Constructor
     *
     * @param string $connectionString The standard Azure connection string
	 * @param null|IServiceFilter      A filter to use when creating new blog services
     */
    public function __construct($connectionString, $filter = null) {
        $this->connectionString = $connectionString;
		$this->filter = $filter;
    }

    /**
     * {@inheritDoc}
     */
    public function create() {
        $service = ServicesBuilder::getInstance()->createBlobService($this->connectionString);
		if ($this->filter) {
			$service = $service->withFilter($this->filter);
		}
		
		return $service;
    }
}
