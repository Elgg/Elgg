<?php

$code = (int) get_input('code', 200);

switch ($code) {
	default:
		return array(
			'status' => 200,
			'output' => array(
				'foo' => 'bar',
			),
			'system_messages' => array(
				'sucess' => 'All is good',
			),
			'forward_url' => '/take/me/home',
		);

	case 403 :
		return array(
			'status' => 403,
			'output' => array(
				'foo' => 'baz',
			),
			'system_messages' => array(
				'error' => 'You are not allowed to do this',
			),
		);
}