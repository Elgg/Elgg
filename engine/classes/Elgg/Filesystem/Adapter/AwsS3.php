<?php
namespace Elgg\Filesystem\Adapter;
use Gaufrette\Adapter\AwsS3 as GaufretteAwsS3;
use Gaufrette\Filesystem;
use Aws\S3\S3Client;

class AwsS3 implements Cloud {
	protected $requiredParams = [
		'bucket',
		'key',
		'secret'
	];
	
	protected $params;
	protected $s3Adapter;
	protected $service;
	protected $fs;
	
	public function __construct(array $params) {
		foreach ($this->requiredParams as $param) {
			if (!isset($params[$param])) {
				throw new \InvalidParameterException("Missing required parameter `$param`");
			}
		}
		
		$this->params = $params;
		
		// set in settings.php for now.
		// 123.123.123.123:8080
		$proxy = elgg_get_config('proxy');
		$ssl_no_verify = elgg_get_config('ssl_no_verify');
		
		// s3 client
		$this->service = S3Client::factory([
			'key' => $params['key'],
			'secret' => $params['secret'],
			'request.options' => [
				'proxy' => $proxy,
				'verify' => !$ssl_no_verify
			]
		]);
		
		$this->s3Adapter = new GaufretteAwsS3($this->service, $params['bucket']);
		$this->fs = new Filesystem($this->s3Adapter);
	}
	
	public function getFilesystem() {
		return $this->fs;
	}
	
	/**
	 * Returns parameters that can be used as an identifier for this filestore adapter
	 * 
	 * @return array
	 */
	public function getParameters() {
		return [
			'class_name' => __CLASS__,
			'cloud_service' => 'aws_s3',
			'bucket' => $this->params['bucket'],
			'key' => $this->service->getCredentials()->getAccessKeyId(),
			'secret' => $this->service->getCredentials()->getSecretKey()
		];
	}
}