<?php
/**
 * Cloud Data Storage 
 * 
 * Provides cloud storage capabilities for the following services:
 *  - AWS S3
 *  - MS Azure Blob Storage
 *  - Google Cloud Data
 */

elgg_register_event_handler('init', 'system', 'cloud_data_storage_init');

/**
 * Init
 */
function cloud_data_storage_init() {
	require __DIR__ . "/vendor/autoload.php";
	
	elgg_register_plugin_hook_handler('config', 'user_data_storage_options', 'cloud_data_storage_register_options');
	
	$info = elgg_get_config('user_data_store_info');
	$adapters = _elgg_services()->dataStorageAdapters;
	
	$proxy = elgg_get_config('proxy');
	$proxy_parsed = parse_url($proxy);
	
	$connection_info = [
		'ssl_no_verify' => elgg_get_config('ssl_no_verify'),
		'proxy' => $proxy,
		'proxy_scheme' => isset($proxy_parsed['scheme']) ? $proxy_parsed['scheme'] : null,
		'proxy_user' => isset($proxy_parsed['user']) ? $proxy_parsed['user'] : null,
		'proxy_pass' => isset($proxy_parsed['pass']) ? $proxy_parsed['pass'] : null,
		'proxy_host' => isset($proxy_parsed['host']) ? $proxy_parsed['host'] : null,
		'proxy_port' => isset($proxy_parsed['port']) ? $proxy_parsed['port'] : null,
	];
	
	if (isset($info['aws_s3'])) {	
		$info['aws_s3']['request.options'] = [
			'proxy' => $connection_info['proxy'],
			'verify' => !$connection_info['ssl_no_verify']
		];
		$service = Aws\S3\S3Client::factory($info['aws_s3']);
		$adapter = new Gaufrette\Adapter\AwsS3($service, $info['aws_s3']['bucket']);
		$adapters->set('aws_s3', $adapter);
	}
	
	if (isset($info['ms_azure'])) {
		$azure = $info['ms_azure'];
		$connection = "DefaultEndpointsProtocol=https;AccountName={$azure['account']};AccountKey={$azure['key']}";
		
		$filter = new Elgg\Filesystem\Adapter\MsAzure\IserviceElggFilter(
			$connection_info['proxy_host'], $connection_info['proxy_port'], $connection_info['ssl_no_verify']
		);
		$rest_proxy = new Elgg\Filesystem\Adapter\MsAzure\ElggBlobProxyFactory($connection, $filter);
		
		$adapter = new Elgg\Filesystem\Adapter\MsAzure\GaufretteAdapter($rest_proxy, $azure['container']);
		// @todo azure apparently doesn't have streaming support in gaufrette.
		$adapter->_canStream = false;
		$adapters->set('ms_azure', $adapter, false);
	}
	
	if (isset($info['google_cloud'])) {
		try {
			$fs = cloud_storage_google_cloud_init($info['google_cloud'], $connection_info);
		} catch (Google_Exception $e) {
			$fs = false;
		}
		if ($fs) {
			$adapters->set('google_cloud', $fs);
		}
	}
}

/**
 * Init Google Cloud Storage
 * 
 * @param array $info            Google storage account info
 * @param array $connection_info Proxy connection info
 * @return \Gaufrette\Adapter\GoogleCloudStorage
 */
function cloud_storage_google_cloud_init($info, $connection_info) {	
	$config = new \Google_Config();
	$client = new \Google_Client($config);

	$io = new Google_IO_Curl($client);
	$io->setOptions([
		CURLOPT_SSL_VERIFYHOST => !$connection_info['ssl_no_verify'],
		CURLOPT_SSL_VERIFYPEER => !$connection_info['ssl_no_verify'],
		CURLOPT_PROXY => $connection_info['proxy_host'],
		CURLOPT_PROXYPORT => $connection_info['proxy_port']
	]);
	$client->setIo($io);
	$client->setApplicationName(elgg_get_config('site')->name);

	$cred = new \Google_Auth_AssertionCredentials(
		$info['account_name'],
		array(\Google_Service_Storage::DEVSTORAGE_FULL_CONTROL),
		file_get_contents($info['p12_key_location'])
	);
	$client->setAssertionCredentials($cred);

	// @todo this needs to be cached somewhere.
	if ($client->getAuth()->isAccessTokenExpired()) {
		$client->getAuth()->refreshTokenWithAssertion();
	}

	$service = new \Google_Service_Storage($client);
	return new Gaufrette\Adapter\GoogleCloudStorage($service, $info['bucket'], array(), true);
}

/**
 * Adds the supported storage options and views.
 * 
 * @param type $hook
 * @param type $type
 * @param array $return
 * @param type $params
 * @return string
 */
function cloud_data_storage_register_options($hook, $type, $return, $params) {
	$return['aws_s3'] = 'admin/site/advanced/storage_aws_s3';
	$return['ms_azure'] = 'admin/site/advanced/storage_ms_azure';
	$return['google_cloud'] = 'admin/site/advanced/storage_google_cloud';
	return $return;
}