<?php

namespace Elgg\Blog;

use Elgg\SystemMessagesService;

class Foo {

	private $system_messages;

	public function __construct(SystemMessagesService $system_messages) {
		$this->system_messages = $system_messages;
	}

	public function test($param) {
		$this->system_messages->addSuccessMessage($param);
	}
}
