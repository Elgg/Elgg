<?php

namespace Elgg;

use Elgg\Cache\EntityCache;
use Elgg\Cache\SessionCache;
use Elgg\Exceptions\LoginException;
use Elgg\Exceptions\SecurityException;
use Elgg\I18n\Translator;

/**
 * Session manager
 *
 * @since 5.0
 */
class SessionManagerService {

	/**
	 * @var EntityCache
	 */
	protected $entity_cache;
	
	/**
	 * @var EventsService
	 */
	protected $events;
	
	/**
	 * @var bool
	 */
	protected $ignore_access = false;
	
	/**
	 * @var \ElggUser|null
	 */
	protected $logged_in_user;
	
	/**
	 * @var PersistentLoginService
	 */
	protected $persistent_login;
	
	/**
	 * @var bool
	 */
	protected $show_disabled_entities = false;

	/**
	 * @var bool
	 */
	protected $show_soft_deleted_entities = false;

	/**
	 * @var \ElggSession
	 */
	protected $session;
	
	/**
	 * @var SessionCache
	 */
	protected $session_cache;
	
	/**
	 * @var Translator
	 */
	protected $translator;
	
	/**
	 * Constructor
	 *
	 * @param \ElggSession           $session          the current session
	 * @param EventsService          $events           the events service
	 * @param Translator             $translator       the translator service
	 * @param PersistentLoginService $persistent_login the persistent login service
	 * @param SessionCache           $session_cache    the session cache
	 * @param EntityCache            $entity_cache     the entity cache
	 */
	public function __construct(
		\ElggSession $session,
		EventsService $events,
		Translator $translator,
		PersistentLoginService $persistent_login,
		SessionCache $session_cache,
		EntityCache $entity_cache
		) {
		$this->session = $session;
		$this->events = $events;
		$this->translator = $translator;
		$this->persistent_login = $persistent_login;
		$this->session_cache = $session_cache;
		$this->entity_cache = $entity_cache;
	}
	
	/**
	 * Get current ignore access setting.
	 *
	 * @return bool
	 */
	public function getIgnoreAccess(): bool {
		return $this->ignore_access;
	}
	
	/**
	 * Set ignore access.
	 *
	 * @param bool $ignore Ignore access
	 *
	 * @return bool Previous setting
	 */
	public function setIgnoreAccess(bool $ignore = true): bool {
		$prev = $this->ignore_access;
		$this->ignore_access = $ignore;
		
		return $prev;
	}
	
	/**
	 * Are disabled entities shown?
	 *
	 * @return bool
	 */
	public function getDisabledEntityVisibility(): bool {
		return $this->show_disabled_entities;
	}

	/**
	 * Are soft deleted entities shown?
	 *
	 * @return bool
	 */
	public function getSoftDeletedEntityVisibility(): bool {
		return $this->show_soft_deleted_entities;
	}
	
	/**
	 * Include disabled entities in queries
	 *
	 * @param bool $show Visibility status
	 *
	 * @return bool Previous setting
	 */
	public function setDisabledEntityVisibility(bool $show = false): bool {
		$prev = $this->show_disabled_entities;
		$this->show_disabled_entities = $show;
		
		return $prev;
	}

	/**
	 * Include soft deleted entities in queries
	 *
	 * @param bool $show Visibility status
	 *
	 * @return bool Previous setting
	 */
	public function setSoftDeletedEntityVisibility(bool $show = true): bool {
		$prev = $this->show_soft_deleted_entities;
		$this->show_soft_deleted_entities = $show;

		return $prev;
	}
	
	/**
	 * Set a user specific token in the session for the currently logged in user
	 *
	 * This will invalidate the session on a password change of the logged in user
	 *
	 * @param \ElggUser $user the user to set the token for (default: logged in user)
	 *
	 * @return void
	 * @since 3.3.25
	 */
	public function setUserToken(\ElggUser $user = null): void {
		if (!$user instanceof \ElggUser) {
			$user = $this->getLoggedInUser();
		}
		
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		$this->session->set('__user_token', $this->generateUserToken($user));
	}
	
	/**
	 * Validate the user token stored in the session
	 *
	 * @param \ElggUser $user the user to check for
	 *
	 * @return void
	 * @throws \Elgg\Exceptions\SecurityException
	 * @since 3.3.25
	 */
	public function validateUserToken(\ElggUser $user): void {
		$session_token = $this->session->get('__user_token');
		$user_token = $this->generateUserToken($user);
		
		if ($session_token !== $user_token) {
			throw new SecurityException($this->translator->translate('session_expired'));
		}
	}
	
	/**
	 * Generate a token for a specific user
	 *
	 * @param \ElggUser $user the user to generate the token for
	 *
	 * @return string
	 * @since 3.3.25
	 */
	protected function generateUserToken(\ElggUser $user): string {
		$hmac = _elgg_services()->hmac->getHmac([
			$user->time_created,
			$user->guid,
		], 'sha256', $user->password_hash);

		return $hmac->getToken();
	}
	
	/**
	 * Log in a user
	 *
	 * @param \ElggUser $user       A valid Elgg user object
	 * @param boolean   $persistent Should this be a persistent login?
	 *
	 * @return void
	 * @throws LoginException
	 * @since 4.3
	 */
	public function login(\ElggUser $user, bool $persistent = false): void {
		if ($user->isBanned()) {
			throw new LoginException($this->translator->translate('LoginException:BannedUser'));
		}

		// give plugins a chance to reject the login of this user (no user in session!)
		if (!$this->events->triggerBefore('login', 'user', $user)) {
			throw new LoginException($this->translator->translate('LoginException:Unknown'));
		}
		
		if (!$user->isEnabled()) {
			// fallback if no plugin provided a reason
			throw new LoginException($this->translator->translate('LoginException:DisabledUser'));
		}
		
		// #5933: set logged in user early so code in login event will be able to
		// use elgg_get_logged_in_user_entity().
		$this->setLoggedInUser($user);
		$this->setUserToken($user);
		
		// re-register at least the core language file for users with language other than site default
		$this->translator->registerTranslations(\Elgg\Project\Paths::elgg() . 'languages/');
		
		// if remember me checked, set cookie with token and store hash(token) for user
		if ($persistent) {
			$this->persistent_login->makeLoginPersistent($user);
		}
		
		// User's privilege has been elevated, so change the session id (prevents session fixation)
		$this->session->migrate();
		
		// check before updating last login to determine first login
		$first_login = empty($user->last_login);
		
		$user->setLastLogin();
		_elgg_services()->accounts->resetAuthenticationFailures($user); // can't inject DI service because of circular reference
		
		$this->events->triggerAfter('login', 'user', $user);
		
		if ($first_login) {
			$this->events->trigger('login:first', 'user', $user);
			$user->first_login = time();
		}
	}
	
	/**
	 * Log the current user out
	 *
	 * @return bool
	 * @since 4.3
	 */
	public function logout(): bool {
		$user = $this->getLoggedInUser();
		if (!$user instanceof \ElggUser) {
			return false;
		}

		if (!$this->events->triggerBefore('logout', 'user', $user)) {
			return false;
		}
		
		$this->persistent_login->removePersistentLogin();
		
		// pass along any messages into new session
		$old_msg = $this->session->get(SystemMessagesService::SESSION_KEY, []);
		$this->session->invalidate();
		
		$this->logged_in_user = null;
		
		$this->session->set(SystemMessagesService::SESSION_KEY, $old_msg);
		
		$this->events->triggerAfter('logout', 'user', $user);
		
		return true;
	}
	
	/**
	 * Sets the logged in user
	 *
	 * @param \ElggUser $user The user who is logged in
	 * @return void
	 * @since 1.9
	 */
	public function setLoggedInUser(\ElggUser $user): void {
		$current_user = $this->getLoggedInUser();
		if ($current_user != $user) {
			$this->session->set('guid', $user->guid);
			$this->logged_in_user = $user;
			$this->session_cache->clear();
			$this->entity_cache->save($user);
			$this->translator->setCurrentLanguage($user->language);
		}
	}
	
	/**
	 * Gets the logged in user
	 *
	 * @return \ElggUser|null
	 *
	 * @since 1.9
	 */
	public function getLoggedInUser(): ?\ElggUser {
		return $this->logged_in_user;
	}
	
	/**
	 * Return the current logged in user by guid.
	 *
	 * @return int
	 */
	public function getLoggedInUserGuid(): int {
		$user = $this->getLoggedInUser();
		return $user ? $user->guid : 0;
	}
	
	/**
	 * Returns whether or not the viewer is currently logged in and an admin user.
	 *
	 * @return bool
	 */
	public function isAdminLoggedIn(): bool {
		$user = $this->getLoggedInUser();
		
		return $user && $user->isAdmin();
	}
	
	/**
	 * Returns whether or not the user is currently logged in
	 *
	 * @return bool
	 */
	public function isLoggedIn(): bool {
		return (bool) $this->getLoggedInUser();
	}
	
	/**
	 * Remove the logged in user
	 *
	 * @return void
	 * @since 1.9
	 */
	public function removeLoggedInUser(): void {
		$this->logged_in_user = null;
		$this->session->remove('guid');
		$this->session_cache->clear();
	}
}
