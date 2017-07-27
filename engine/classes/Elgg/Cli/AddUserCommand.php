<?php

namespace Elgg\Cli;

use Elgg\Application;
use Exception;
use RegistrationException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * user:add CLI command
 */
class AddUserCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('user:add')
				->setDescription('Create a new user account')
				->addOption('admin', null, InputOption::VALUE_NONE, 'If set, will make the user an admin')
				->addOption('notify', null, InputOption::VALUE_NONE, 'If set, will send a notification to the new user');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function handle() {

		$admin = $this->option('admin');
		$notify = $this->option('notify');

		$email = $this->ask('Enter account email: ');
		list($username, ) = explode('@', $email, 2);
		$username = $this->ask("Enter account username [$username]: ", $username);
		$password = $this->ask('Enter account password (leave empty to autegenerate): ', null, true, false);
		if (empty($password)) {
			$password = generate_random_cleartext_password();
		}
		$name = $this->ask("Enter account display name [$username]: ", $username);

		$guid = register_user($username, $password, $name, $email);
		$user = get_entity($guid);

		$user->admin_created = true;
		elgg_set_user_validation_status($user->guid, true, 'cli');

		$params = [
			'user' => $user,
			'password' => $password,
		];

		if (!elgg_trigger_plugin_hook('register', 'user', $params, TRUE)) {
			$ia = elgg_set_ignore_access(true);
			$user->delete();
			elgg_set_ignore_access($ia);
			throw new RegistrationException(elgg_echo('registerbad'));
		}

		if ($admin) {
			$ia = elgg_set_ignore_access(true);
			$user->makeAdmin();
			elgg_set_ignore_access($ia);
		}

		if ($notify) {
			$subject = elgg_echo('useradd:subject', array(), $user->language);
			$body = elgg_echo('useradd:body', array(
				$name,
				elgg_get_site_entity()->name,
				elgg_get_site_entity()->url,
				$username,
				$password,
					), $user->language);

			notify_user($user->guid, elgg_get_site_entity()->guid, $subject, $body, [
				'password' => $password,
			]);
		}

		if ($user->isAdmin()) {
			system_message("New admin user has been registered [guid: $user->guid]");
		} else {
			system_message("New user has been registered [guid: $user->guid]");
		}
	}

}
