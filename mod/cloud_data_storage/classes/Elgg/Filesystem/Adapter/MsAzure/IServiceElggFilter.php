<?php
namespace Elgg\Filesystem\Adapter\MsAzure;

use WindowsAzure\Common\Internal\IServiceFilter;

/**
 * A filter for Azure SDK REST requests that optionally uses a proxy
 * or disables SSL validation
 */
class IserviceElggFilter implements IServiceFilter {
    protected $proxyHost;
    protected $proxyPort;
	protected $sslNoVerify;
    
    public function __construct($host = null, $port = null, $sslNoVerify = null) {
		$this->proxyHost = $host;
		$this->proxyPort = $port;
		$this->sslNoVerify = $sslNoVerify;
    }
    
	/**
	 * 
	 * @param \HTTP_Request2 $request
	 * @return \HTTP_Request2
	 */
    public function handleRequest($request) {
		if ($this->proxyHost) {
			$request->setConfig('proxy_host', $this->proxyHost);
		}
		
		if ($this->proxyPort) {
			$request->setConfig('proxy_port', $this->proxyPort);
		}
		
		if ($this->sslNoVerify !== null) {
			$request->setConfig('ssl_verify_peer', !$this->sslNoVerify);
			$request->setConfig('ssl_verify_host', !$this->sslNoVerify);
		}
		
        return $request;
    }

    public function handleResponse($request, $response) {
        return $response;
    }

    public static function errorHandler($errno, $errorMessage, $errorFile, $errorLine)
    {
        return ($errno == E_WARNING ? true : false);
    }
}


