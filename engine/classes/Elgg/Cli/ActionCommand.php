<?php

namespace Elgg\Cli;

use Elgg\Application;
use Elgg\Http\Request;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * route CLI command
 */
class ActionCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('action')
				->setDescription('Execute an action')
				->addArgument('action_name', InputArgument::REQUIRED, 'Name of the action')
				->addOption('query', null, InputOption::VALUE_OPTIONAL, 'Query string')
				->addOption('as', null, InputOption::VALUE_OPTIONAL, 'Username of the user to login');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function handle() {

		$action = trim($this->argument('action_name'), '/');
		$uri = "action/$action";

		$parameters = [];

		$query = trim((string) $this->option('query'), '?');
		parse_str($query, $parameters);

		$ts = time();
		$parameters['__elgg_ts'] = $ts;
		$parameters['__elgg_token'] = _elgg_services()->actions->generateActionToken($ts);

		$request = Request::create($uri, 'POST', $parameters);
		
		$cookie_name = _elgg_services()->config->getCookieConfig()['session']['name'];
		$session_id = _elgg_services()->session->getId();
		$request->cookies->set($cookie_name, $session_id);

		$request->headers->set('Referer', elgg_normalize_url('cli'));
		$request->headers->set('X-Elgg-Ajax-API', 2);
		elgg_set_viewtype('json');

		_elgg_services()->setValue('request', $request);
		Application::index();
	}

}
