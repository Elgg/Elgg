<?php

namespace Elgg\Users;

use Elgg\Config;
use Elgg\Database\UsersTable;
use Elgg\Email;
use Elgg\Email\Address;
use Elgg\EmailService;
use Elgg\Exceptions\InvalidParameterException;
use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\I18n\Translator;
use Elgg\PasswordService;
use Elgg\PluginHooksService;
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
	 * @var UsersTable
	 */
	protected $users;

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;
	
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
	 * @param UsersTable               $users              Users table
	 * @param PluginHooksService       $hooks              Plugin hooks service
	 * @param EmailService             $email              Email service
	 * @param PasswordGeneratorService $password_generator Password generator service
	 */
	public function __construct(
		Config $config,
		Translator $translator,
		PasswordService $passwords,
		UsersTable $users,
		PluginHooksService $hooks,
		EmailService $email,
		PasswordGeneratorService $password_generator
	) {
		$this->config = $config;
		$this->translator = $translator;
		$this->passwords = $passwords;
		$this->users = $users;
		$this->hooks = $hooks;
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
	public function validateAccountData($username, $password, $name, $email, $allow_multiple_emails = false) {

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
	 * @param string $username              The username of the new user
	 * @param string $password              The password
	 * @param string $name                  The user's display name
	 * @param string $email                 The user's email address
	 * @param bool   $allow_multiple_emails Allow the same email address to be
	 *                                      registered multiple times?
	 *
	 * @return void
	 * @throws RegistrationException
	 */
	public function assertValidAccountData($username, $password, $name, $email, $allow_multiple_emails = false) {

		$results = $this->validateAccountData($username, $password, $name, $email, $allow_multiple_emails);

		foreach ($results->all() as $result) {
			if (!$result->isValid()) {
				throw new RegistrationException($result->getError());
			}
		}

	}

	/**
	 * Registers a user, returning false if the username already exists
	 *
	 * @param string $username              The username of the new user
	 * @param string $password              The password
	 * @param string $name                  The user's display name
	 * @param string $email                 The user's email address
	 * @param bool   $allow_multiple_emails Allow the same email address to be
	 *                                      registered multiple times?
	 * @param string $subtype               Subtype of the user entity
	 * @param array  $params                Additional parameters
	 *
	 * @return int|false The new user's GUID; false on failure
	 */
	public function register($username, $password, $name, $email, $allow_multiple_emails = false, $subtype = null, array $params = []) {

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
		
		$user->language = $this->translator->getCurrentLanguage();

		if (!$user->save()) {
			return false;
		}

		// doing this after save to prevent metadata save notices on unwritable metadata password_hash
		$user->setPassword($password);

		// Turn on email notifications by default
		$user->setNotificationSetting('email', true);
		
		if (elgg_extract('validated', $params, true)) {
			$user->setValidationStatus(true, 'on_create');
		}

		return $user->guid;
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
	public function assertValidUsername($username, $assert_unregistered = false) {

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
		$whitelist = '/[\p{L}\p{M}\p{Nd}._-]+/';
		if (!preg_match_all($whitelist, $username)) {
			throw new RegistrationException($this->translator->translate('registration:invalidchars'));
		}

		// Belts and braces
		// @todo Tidy into main unicode
		$blacklist2 = '\'/\\"*& ?#%^(){}[]~?<>;|¬`@+=,:';

		$blacklist2 = $this->hooks->trigger(
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

		$result = $this->hooks->trigger(
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
				return $this->users->getByUsername($username);
			});

			if ($exists) {
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
	public function assertValidPassword($password) {

		if (is_array($password)) {
			list($password, $password2) = $password;

			if (empty($password) || empty($password2)) {
				throw new RegistrationException(elgg_echo('RegistrationException:EmptyPassword'));
			}

			if (strcmp($password, $password2) != 0) {
				throw new RegistrationException(elgg_echo('RegistrationException:PasswordMismatch'));
			}
		}
		
		$result = $this->hooks->trigger(
			'registeruser:validate:password',
			'all',
			['password' => $password],
			true
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
	public function assertCurrentPassword(\ElggUser $user, $password) {
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
	public function assertValidEmail($address, $assert_unregistered = false) {
		if (!$this->isValidEmail($address)) {
			throw new RegistrationException($this->translator->translate('registration:notemail'));
		}

		$result = $this->hooks->trigger(
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
				return $this->users->getByEmail($address);
			});

			if ($exists) {
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
	public function isValidEmail($address) {
		return filter_var($address, FILTER_VALIDATE_EMAIL) === $address;
	}
	
	/**
	 * Send out an e-mail to the new email address the user wanted
	 *
	 * @param \ElggUser $user  user with new e-mail address
	 * @param string    $email E-mail address
	 *
	 * @return bool
	 * @throws InvalidParameterException
	 */
	public function requestNewEmailValidation(\ElggUser $user, $email) {
		if (!$this->isValidEmail($email)) {
			throw new InvalidParameterException($this->translator->translate('registration:notemail'));
		}
		
		$site = elgg_get_site_entity();
		
		$user->setPrivateSetting('new_email', $email);
		
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
}
