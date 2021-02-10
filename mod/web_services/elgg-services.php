<?php

use Elgg\WebServices\Di\RestApiErrorHandler;
use Elgg\WebServices\Di\ApiRegistrationCollection;
use Elgg\WebServices\Di\ApiRegistrationService;

return [
	ApiRegistrationCollection::name() => \Di\create(ApiRegistrationCollection::class),
	ApiRegistrationService::name() => \Di\create(ApiRegistrationService::class)
		->constructor(Di\get(ApiRegistrationCollection::name())),
	RestApiErrorHandler::name() => \Di\create(RestApiErrorHandler::class),
];
