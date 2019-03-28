<?php

namespace Elgg\Users;

use Elgg\Config;
use Elgg\Database\UsersTable;
use Elgg\I18n\Translator;
use Elgg\PasswordService;
use Elgg\PluginHooksService;
use Elgg\Validation\ValidationResults;
use ElggUser;
use RegistrationException;

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
	 * Constructor
	 *
	 * @param Config             $config     Config
	 * @param Translator         $translator Translator
	 * @param PasswordService    $passwords  Passwords
	 * @param UsersTable         $users      Users table
	 * @param PluginHooksService $hooks      Plugin hooks service
	 */
	public function __construct(
		Config $config,
		Translator $translator,
		PasswordService $passwords,
		UsersTable $users,
		PluginHooksService $hooks
	) {
		$this->config = $config;
		$this->translator = $translator;
		$this->passwords = $passwords;
		$this->users = $users;
		$this->hooks = $hooks;
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
	 *
	 * @return int|false The new user's GUID; false on failure
	 * @throws RegistrationException
	 */
	public function register($username, $password, $name, $email, $allow_multiple_emails = false, $subtype = null) {

		$this->assertValidAccountData($username, $password, $name, $email, $allow_multiple_emails);

		// Create user
		$constructor = ElggUser::class;
		if (isset($subtype)) {
			$class = elgg_get_entity_class('user', $subtype);
			if ($class && class_exists($class) && is_subclass_of($class, ElggUser::class)) {
				$constructor = $class;
			}
		}

		$user = new $constructor();
		/* @var $user ElggUser */

		if (isset($subtype)) {
			$user->subtype = $subtype;
		}

		$user->username = $username;
		$user->email = $email;
		$user->name = $name;
		$user->access_id = ACCESS_PUBLIC;
		$user->owner_guid = 0; // Users aren't owned by anyone, even if they are admin created.
		$user->container_guid = 0; // Users aren't contained by anyone, even if they are admin created.
		$user->language = $this->translator->getCurrentLanguage();

		if ($user->save() === false) {
			return false;
		}

		// doing this after save to prevent metadata save notices on unwritable metadata password_hash
		$user->setPassword($password);

		// Turn on email notifications by default
		$user->setNotificationSetting('email', true);

		return $user->getGUID();
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

		if (strlen($username) < $this->config->minusername) {
			$msg = $this->translator->translate('registration:usernametooshort', [$this->config->minusername]);
			throw new RegistrationException($msg);
		}

		// username in the database has a limit of 128 characters
		if (strlen($username) > 128) {
			$msg = $this->translator->translate('registration:usernametoolong', [128]);
			throw new RegistrationException($msg);
		}

		// Blacklist for bad characters (partially nicked from mediawiki)
		$blacklist = '/[' .
			'\x{0080}-\x{009f}' . // iso-8859-1 control chars
			'\x{00a0}' .          // non-breaking space
			'\x{2000}-\x{200f}' . // various whitespace
			'\x{2028}-\x{202f}' . // breaks and control chars
			'\x{3000}' .          // ideographic space
			'\x{e000}-\x{f8ff}' . // private use
			']/u';

		if (preg_match($blacklist, $username)) {
			// @todo error message needs work
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

		for ($n = 0; $n < strlen($blacklist2); $n++) {
			if (strpos($username, $blacklist2[$n]) !== false) {
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

		if (strlen($password) < $this->config->min_password_length) {
			$msg = $this->translator->translate('registration:passwordtooshort', [$this->config->min_password_length]);
			throw new RegistrationException($msg);
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
	 * @param ElggUser $user     User entity
	 * @param string   $password Password
	 *
	 * @return void
	 * @throws RegistrationException
	 */
	public function assertCurrentPassword(ElggUser $user, $password) {
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
}
