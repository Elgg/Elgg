<?php
/**
 * Elgg Simple PAM library
 * Contains functions for managing authentication.
 * This is not a full implementation of PAM. It supports a single facility
 * (authentication) and allows multiple policies (user authentication is the
 * default). There are two control flags possible for each module: sufficient
 * or required. The entire chain for a policy is processed (or until a
 * required module fails). A module fails by returning false or throwing an
 * exception. The order that modules are processed is determined by the order
 * they are registered. For an example of a PAM, see \Elgg\PAM\User\Password.
 *
 * For more information on PAMs see:
 * http://www.freebsd.org/doc/en/articles/pam/index.html
 */

/**
 * Register a PAM handler
 *
 * A PAM handler should return true if the authentication attempt passed. For a
 * failure, return false or throw an \Elgg\Exceptions\AuthenticationException. Returning nothing indicates that
 * the handler wants to be skipped.
 *
 * @param callable $handler    A callable handler which can handle a given array of authentiation parameters (could be credentials)
 * @param string   $importance The importance of the authentication handler ('sufficient' (default) or 'required')
 * @param string   $policy     The policy for which the authentication handler can be used (eg. 'user' (default) or 'api')
 *
 * @return bool
 * @since 4.3
 */
function elgg_register_pam_handler($handler, string $importance = 'sufficient', string $policy = 'user'): bool {
	return _elgg_services()->authentication->registerHandler($handler, $importance, $policy);
}

/**
 * Unregisters a PAM handler
 *
 * @param callable $handler The callable PAM handler to unregister
 * @param string   $policy  The policy type, default is 'user'
 *
 * @return void
 * @since 4.3
 */
function elgg_unregister_pam_handler($handler, string $policy = 'user'): void {
	_elgg_services()->authentication->unregisterHandler($handler, $policy);
}

/**
 * Start an authentication process
 *
 * @param string $policy                The policy type
 * @param array  $authentication_params (optional) authentication params (eg. username/password)
 *
 * @return bool
 * @since 4.3
 * @throws \Elgg\Exceptions\AuthenticationException
 */
function elgg_pam_authenticate(string $policy, array $authentication_params = []): bool {
	return _elgg_services()->authentication->authenticate($policy, $authentication_params);
}
