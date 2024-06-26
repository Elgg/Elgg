<?php

namespace Elgg\Cli;

use Symfony\Component\HttpFoundation\Response;

/**
 * Cli ResponseTransport
 */
class ResponseTransport implements \Elgg\Http\ResponseTransport {

	/**
	 * ResponseTransport constructor.
	 *
	 * @param Command $command Cli command
	 */
	public function __construct(protected Command $command) {
	}

	/**
	 * {@inheritdoc}
	 */
	public function send(Response $response) {
		$content = $response->getContent();
		$json = @json_decode($content);
		$this->command->write(elgg_echo('cli:response:output') . PHP_EOL);
		$this->command->dump($json ?: $content);

		return true;
	}
}
