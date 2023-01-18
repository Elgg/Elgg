<?php

use Elgg\WebServices\Di\ApiRegistrationCollection;
use Elgg\WebServices\Di\ApiRegistrationService;
use Elgg\WebServices\Di\RestApiErrorHandler;

return [
	ApiRegistrationCollection::name() => \Di\autowire(ApiRegistrationCollection::class),
	ApiRegistrationService::name() => \Di\autowire(ApiRegistrationService::class),
	RestApiErrorHandler::name() => \Di\autowire(RestApiErrorHandler::class),
	
	// map classes to alias to allow autowiring
	ApiRegistrationCollection::class => \Di\get(ApiRegistrationCollection::name()),
	ApiRegistrationService::class => \Di\get(ApiRegistrationService::name()),
	RestApiErrorHandler::class => \Di\get(RestApiErrorHandler::name()),
];
