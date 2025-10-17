<?php

namespace Elgg;

use Elgg\Exceptions\AuthenticationException;
use Elgg\Traits\Loggable;

/**
 * Authentication service handles registration of PAM handlers and calling of those handlers
 *
 * @since 4.3
 * @internal
 */
class AuthenticationService {
	
	use Loggable;
	
	/**
	 * Registered authentication handlers
	 *
	 * @var array
	 */
	protected array $handlers = [];
	
	/**
	 * Create new service
	 *
	 * @param HandlersService $handlerService handler service
	 */
	public function __construct(protected HandlersService $handlerService) {
	}
	
	/**
	 * Register an authentication handler
	 *
	 * @param callable $handler    A callable authentication handler
	 * @param string   $importance The importance of the authentication handler ('sufficient' or 'required')
	 * @param string   $policy     The policy for which the authentication handler can be used (eg. 'user' or 'api')
	 *
	 * @return void
	 */
	public function registerHandler($handler, string $importance = 'sufficient', string $policy = 'user'): void {
		$handler_string = $this->handlerService->describeCallable($handler);
		if (!isset($this->handlers[$policy])) {
			$this->handlers[$policy] = [];
		}
		
		$this->handlers[$policy][$handler_string] = [
			'handler' => $handler,
			'importance' => strtolower($importance),
		];
	}
	
	/**
	 * Unregister an authentication handler
	 *
	 * @param callable $handler The authentication handler to unregister
	 * @param string   $policy  The authentication handler policy
	 *
	 * @return void
	 */
	public function unregisterHandler($handler, string $policy = 'user'): void {
		$handler_string = $this->handlerService->describeCallable($handler);
		
		unset($this->handlers[$policy][$handler_string]);
	}
	
	/**
	 * Authenticate
	 *
	 * @param string $policy                Authentication policy (eg. 'user' or 'api')
	 * @param array  $authentication_params (optional) Credentials to use
	 *
	 * @throws AuthenticationException
	 * @return bool
	 */
	public function authenticate(string $policy, array $authentication_params = []): bool {
		if (!isset($this->handlers[$policy])) {
			return false;
		}
		
		$authenticated = false;
		$first_exception = null;
		
		foreach ($this->handlers[$policy] as $handler_string => $handler_config) {
			$handler = $handler_config['handler'];
			$importance = strtolower($handler_config['importance']);
			
			if (!$this->handlerService->isCallable($handler)) {
				$this->getLogger()->warning("PAM handler '{$handler_string}' for policy '{$policy}' isn't callable");
				continue;
			}
			
			$callable = $this->handlerService->resolveCallable($handler);
			
			try {
				$result = call_user_func($callable, $authentication_params);
				if ($result === true) {
					$authenticated = true;
				} elseif ($result === false && $importance === 'required') {
					return false;
				}
			} catch (\Exception $e) {
				if (!$e instanceof AuthenticationException) {
					$e = new AuthenticationException($e->getMessage(), $e->getCode(), $e);
				}
				
				if ($importance === 'required') {
					throw $e;
				}
				
				if (!isset($first_exception)) {
					$first_exception = $e;
				}
			}
		}
		
		if (!$authenticated && $first_exception instanceof AuthenticationException) {
			throw $first_exception;
		}
		
		return $authenticated;
	}
}
