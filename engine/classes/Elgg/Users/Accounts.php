<?php

namespace Elgg\Users;

use Elgg\Config;
use Elgg\Email;
use Elgg\Email\Address;
use Elgg\EmailService;
use Elgg\EventsService;
use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\I18n\Translator;
use Elgg\PasswordService;
use Elgg\Security\PasswordGeneratorService;
use Elgg\Validation\ValidationResults;

/**
 * User accounts service
 */
class Accounts {

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var Translator
	 */
	protected $translator;

	/**
	 * @var PasswordService
	 */
	protected $passwords;

	/**
	 * @var EventsService
	 */
	protected $events;
	
	/**
	 * @var EmailService
	 */
	protected $email;
	
	/**
	 * @var PasswordGeneratorService
	 */
	protected $password_generator;

	/**
	 * Constructor
	 *
	 * @param Config                   $config             Config
	 * @param Translator               $translator         Translator
	 * @param PasswordService          $passwords          Passwords
	 * @param EventsService            $events             Events service
	 * @param EmailService             $email              Email service
	 * @param PasswordGeneratorService $password_generator Password generator service
	 */
	public function __construct(
		Config $config,
		Translator $translator,
		PasswordService $passwords,
		EventsService $events,
		EmailService $email,
		PasswordGeneratorService $password_generator
	) {
		$this->config = $config;
		$this->translator = $translator;
		$this->passwords = $passwords;
		$this->events = $events;
		$this->email = $email;
		$this->password_generator = $password_generator;
	}

	/**
	 * Validate registration details to ensure they can be used to register a new user account
	 *
	 * @param string       $username              The username of the new user
	 * @param string|array $password              The password
	 *                                            Can be an array [$password, $oonfirm_password]
	 * @param string       $name                  The user's display name
	 * @param string       $email                 The user's email address
	 * @param bool         $allow_multiple_emails Allow the same email address to be
	 *                                            registered multiple times?
	 *
	 * @return ValidationResults
	 */
	public function validateAccountData(string $username, string|array $password, string $name, string $email, bool $allow_multiple_emails = false): ValidationResults {

		return elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function () use ($username, $email, $password, $name, $allow_multiple_emails) {
			$results = new ValidationResults();

			if (empty($name)) {
				$error = $this->translator->translate('registration:noname');
				$results->fail('name', $name, $error);
			} else {
				$results->pass('name', $name);
			}

			try {
				$this->assertValidEmail($email, !$allow_multiple_emails);

				$results->pass('email', $email);
			} catch (RegistrationException $ex) {
				$results->fail('email', $email, $ex->getMessage());
			}

			try {
				$this->assertValidPassword($password);

				$results->pass('password', $password);
			} catch (RegistrationException $ex) {
				$results->fail('password', $password, $ex->getMessage());
			}

			try {
				$this->assertValidUsername($username, true);

				$results->pass('username', $username);
			} catch (RegistrationException $ex) {
				$results->fail('username', $username, $ex->getMessage());
			}

			return $results;
		});
	}

	/**
	 * Assert that given registration details are valid and can be used to register the user
	 *
	 * @param string       $username              The username of the new user
	 * @param string|array $password              The password
	 * @param string       $name                  The user's display name
	 * @param string       $email                 The user's email address
	 * @param bool         $allow_multiple_emails Allow the same email address to be registered multiple times?
	 *
	 * @return void
	 * @throws RegistrationException
	 */
	public function assertValidAccountData(string $username, string|array $password, string $name, string $email, bool $allow_multiple_emails = false): void {

		$results = $this->validateAccountData($username, $password, $name, $email, $allow_multiple_emails);

		foreach ($results->all() as $result) {
			if (!$result->isValid()) {
				throw new RegistrationException($result->getError());
			}
		}
	}

	/**
	 * Registers a user
	 *
	 * @param array $params Array of options with keys:
	 *                      (string) username              => The username of the new user
	 *                      (string) password              => The password
	 *                      (string) name                  => The user's display name
	 *                      (string) email                 => The user's email address
	 *                      (string) subtype               => (optional) Subtype of the user entity
	 *                      (string) language              => (optional) user language (defaults to current language)
	 *                      (bool)   allow_multiple_emails => (optional) Allow the same email address to be registered multiple times (default false)
	 *                      (bool)   validated             => (optional) Is the user validated (default true)
	 *
	 * @return \ElggUser
	 * @throws RegistrationException
	 */
	public function register(array $params = []): \ElggUser {
		$username = (string) elgg_extract('username', $params);
		$password = (string) elgg_extract('password', $params);
		$name = (string) elgg_extract('name', $params);
		$email = (string) elgg_extract('email', $params);
		$subtype = elgg_extract('subtype', $params);
		$language = (string) elgg_extract('language', $params, $this->translator->getCurrentLanguage());
		$allow_multiple_emails = (bool) elgg_extract('allow_multiple_emails', $params, false);
		$validated = (bool) elgg_extract('validated', $params, true);

		$this->assertValidAccountData($username, $password, $name, $email, $allow_multiple_emails);

		// Create user
		$constructor = \ElggUser::class;
		if (isset($subtype)) {
			$class = elgg_get_entity_class('user', $subtype);
			if ($class && class_exists($class) && is_subclass_of($class, \ElggUser::class)) {
				$constructor = $class;
			}
		}

		/* @var $user \ElggUser */
		$user = new $constructor();
		
		if (isset($subtype)) {
			$user->setSubtype($subtype);
		}

		$user->username = $username;
		$user->email = $email;
		$user->name = $name;
		$user->language = $language;

		if (!$user->save()) {
			throw new RegistrationException($this->translator->translate('registerbad'));
		}

		// doing this after save to prevent metadata save notices on unwritable metadata password_hash
		$user->setPassword($password);

		// Turn on email notifications by default
		$user->setNotificationSetting('email', true);
		
		if ($validated) {
			$user->setValidationStatus(true, 'on_create');
		}

		return $user;
	}

	/**
	 * Simple function which ensures that a username contains only valid characters.
	 *
	 * This should only permit chars that are valid on the file system as well.
	 *
	 * @param string $username            Username
	 * @param bool   $assert_unregistered Also assert that the username has not yet been registered
	 *
	 * @return void
	 * @throws RegistrationException
	 */
	public function assertValidUsername(string $username, bool $assert_unregistered = false): void {

		if (elgg_strlen($username) < $this->config->minusername) {
			$msg = $this->translator->translate('registration:usernametooshort', [$this->config->minusername]);
			throw new RegistrationException($msg);
		}

		// username in the database has a limit of 128 characters
		if (strlen($username) > 128) {
			$msg = $this->translator->translate('registration:usernametoolong', [128]);
			throw new RegistrationException($msg);
		}

		// Whitelist all supported route characters
		// @see Elgg\Router\RouteRegistrationService::register()
		// @link https://github.com/Elgg/Elgg/issues/12518
		// @link https://github.com/Elgg/Elgg/issues/14239
		$invalid_chars = [];
		if (preg_match_all('/[^\p{L}\p{M}\p{Nd}._-]+/iu', $username, $invalid_chars)) {
			throw new RegistrationException($this->translator->translate('registration:invalidchars:route', [implode(',', $invalid_chars[0])]));
		}

		// Belts and braces
		// @todo Tidy into main unicode
		$blacklist2 = '\'/\\"*& ?#%^(){}[]~?<>;|¬`@+=,:';

		$blacklist2 = $this->events->triggerResults(
			'username:character_blacklist',
			'user',
			['blacklist' => $blacklist2],
			$blacklist2
		);

		for ($n = 0; $n < elgg_strlen($blacklist2); $n++) {
			if (elgg_strpos($username, $blacklist2[$n]) !== false) {
				$msg = $this->translator->translate('registration:invalidchars', [$blacklist2[$n], $blacklist2]);
				$msg = htmlspecialchars($msg, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
				throw new RegistrationException($msg);
			}
		}

		$result = $this->events->triggerResults(
			'registeruser:validate:username',
			'all',
			['username' => $username],
			true
		);

		if (!$result) {
			throw new RegistrationException($this->translator->translate('registration:usernamenotvalid'));
		}

		if ($assert_unregistered) {
			$exists = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function () use ($username) {
				return elgg_get_user_by_username($username);
			});

			if ($exists instanceof \ElggUser) {
				throw new RegistrationException($this->translator->translate('registration:userexists'));
			}
		}
	}

	/**
	 * Simple validation of a password
	 *
	 * @param string|array $password Clear text password
	 *                               Can be an array [$password, $confirm_password]
	 *
	 * @return void
	 * @throws RegistrationException
	 */
	public function assertValidPassword(string|array $password): void {

		if (is_array($password)) {
			list($password, $password2) = $password;

			if (empty($password) || empty($password2)) {
				throw new RegistrationException(elgg_echo('RegistrationException:EmptyPassword'));
			}

			if (strcmp($password, $password2) != 0) {
				throw new RegistrationException(elgg_echo('RegistrationException:PasswordMismatch'));
			}
		}
		
		$result = $this->events->triggerResults(
			'registeruser:validate:password',
			'all',
			['password' => $password],
			!empty($password)
		);

		if (!$result) {
			throw new RegistrationException($this->translator->translate('registration:passwordnotvalid'));
		}
	}

	/**
	 * Assert that user can authenticate with the given password
	 *
	 * @param \ElggUser $user     User entity
	 * @param string    $password Password
	 *
	 * @return void
	 * @throws RegistrationException
	 */
	public function assertCurrentPassword(\ElggUser $user, string $password): void {
		if (!$this->passwords->verify($password, $user->password_hash)) {
			throw new RegistrationException($this->translator->translate('LoginException:PasswordFailure'));
		}
	}

	/**
	 * Simple validation of a email.
	 *
	 * @param string $address             Email address
	 * @param bool   $assert_unregistered Also assert that the email address has not yet been used for a user account
	 *
	 * @return void
	 * @throws RegistrationException
	 */
	public function assertValidEmail(string $address, bool $assert_unregistered = false): void {
		if (!$this->isValidEmail($address)) {
			throw new RegistrationException($this->translator->translate('registration:notemail'));
		}

		$result = $this->events->triggerResults(
			'registeruser:validate:email',
			'all',
			['email' => $address],
			true
		);

		if (!$result) {
			throw new RegistrationException($this->translator->translate('registration:emailnotvalid'));
		}

		if ($assert_unregistered) {
			$exists = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function () use ($address) {
				return elgg_get_user_by_email($address);
			});

			if ($exists instanceof \ElggUser) {
				throw new RegistrationException($this->translator->translate('registration:dupeemail'));
			}
		}
	}

	/**
	 * Validates an email address.
	 *
	 * @param string $address Email address
	 *
	 * @return bool
	 */
	public function isValidEmail(string $address): bool {
		return filter_var($address, FILTER_VALIDATE_EMAIL) === $address;
	}
	
	/**
	 * Send out an e-mail to the new email address the user wanted
	 *
	 * @param \ElggUser $user  user with new e-mail address
	 * @param string    $email E-mail address
	 *
	 * @return bool
	 * @throws InvalidArgumentException
	 */
	public function requestNewEmailValidation(\ElggUser $user, string $email): bool {
		if (!$this->isValidEmail($email)) {
			throw new InvalidArgumentException($this->translator->translate('registration:notemail'));
		}
		
		$site = elgg_get_site_entity();
		
		$user->new_email = $email;
		
		$url = elgg_generate_url('account:email:confirm', [
			'guid' => $user->guid,
		]);
		$url = elgg_http_get_signed_url($url, '+1 hour');
		
		$notification = Email::factory([
			'from' => $site,
			'to' => new Address($email, $user->getDisplayName()),
			'subject' => $this->translator->translate('email:request:email:subject', [], $user->getLanguage()),
			'body' => $this->translator->translate('email:request:email:body', [
				$site->getDisplayName(),
				$url,
			], $user->getLanguage()),
		]);
		
		return $this->email->send($notification);
	}
	
	/**
	 * Registers an authentication failure for a user
	 *
	 * @param \ElggUser $user user to log the failure for
	 *
	 * @return void
	 * @since 4.3
	 */
	public function registerAuthenticationFailure(\ElggUser $user): void {
		$fails = (int) $user->authentication_failures;
		$fails++;

		$user->authentication_failures = $fails;
		$user->{"authentication_failure_{$fails}"} = time();
	}
	
	/**
	 * Resets all authentication failures for a given user
	 *
	 * @param \ElggUser $user user to clear the failures for
	 *
	 * @return void
	 * @since 4.3
	 */
	public function resetAuthenticationFailures(\ElggUser $user): void {
		$fails = (int) $user->authentication_failures;
		if (empty($fails)) {
			return;
		}
		
		for ($n = 1; $n <= $fails; $n++) {
			unset($user->{"authentication_failure_{$n}"});
		}

		unset($user->authentication_failures);
	}
	
	/**
	 * Checks if the authentication failure limit has been reached
	 *
	 * @param \ElggUser $user     User to check the limit for
	 * @param int       $limit    (optional) number of allowed failures
	 * @param int       $lifetime (optional) number of seconds before a failure is considered expired
	 *
	 * @return bool
	 * @since 4.3
	 */
	public function isAuthenticationFailureLimitReached(\ElggUser $user, int $limit = null, int $lifetime = null): bool {
		$limit = $limit ?? $this->config->authentication_failures_limit;
		$lifetime = $lifetime ?? $this->config->authentication_failures_lifetime;
		
		$fails = (int) $user->authentication_failures;
		if (empty($fails) || $fails < $limit) {
			return false;
		}
		
		$failure_count = 0;
		$min_time = time() - $lifetime;
		for ($n = $fails; $n > 0; $n--) {
			$failure_timestamp = $user->{"authentication_failure_{$n}"};
			if ($failure_timestamp > $min_time) {
				$failure_count++;
			}

			if ($failure_count === $limit) {
				// Limit reached
				return true;
			}
		}
		
		return false;
	}
}
