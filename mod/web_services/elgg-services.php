<?php

use Elgg\WebServices\Di\RestApiErrorHandler;
use Elgg\WebServices\Di\ApiRegistrationCollection;
use Elgg\WebServices\Di\ApiRegistrationService;

return [
	ApiRegistrationCollection::name() => Di\object(ApiRegistrationCollection::class),
	ApiRegistrationService::name() => Di\object(ApiRegistrationService::class)
		->constructor(Di\get(ApiRegistrationCollection::name())),
	RestApiErrorHandler::name() => Di\object(RestApiErrorHandler::class),
];
