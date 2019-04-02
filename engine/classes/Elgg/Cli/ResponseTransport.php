<?php

namespace Elgg\Cli;

use Symfony\Component\HttpFoundation\Response;

/**
 * Cli ResponseTransport
 */
class ResponseTransport implements \Elgg\Http\ResponseTransport {

	private $command;

	/**
	 * ResponseTransport constructor.
	 *
	 * @param Command $command Cli command
	 */
	public function __construct(Command $command) {
		$this->command = $command;
	}

	/**
	 * {@inheritdoc}
	 */
	public function send(Response $response) {
		$content = $response->getContent();
		$json = @json_decode($content);
		$this->command->write("Response: " . PHP_EOL);
		$this->command->dump($json ? $json : $content);

		return true;
	}
}
