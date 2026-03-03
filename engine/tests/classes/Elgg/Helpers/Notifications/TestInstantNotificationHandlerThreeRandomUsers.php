<?php

namespace Elgg\Helpers\Notifications;

class TestInstantNotificationHandlerThreeRandomUsers extends TestInstantNotificationHandlerTwoRandomUsers {
	
	public function getSubscriptions(): array {
		$result = [];
		
		$user = $this->getRandomUser(static::$user_guids);
		static::$user_guids[] = $user->guid;
		$result[$user->guid] = ['test_method'];
		
		$user = $this->getRandomUser(static::$user_guids);
		static::$user_guids[] = $user->guid;
		$result[$user->guid] = ['test_method'];
		
		$user = $this->getRandomUser(static::$user_guids);
		static::$user_guids[] = $user->guid;
		$result[$user->guid] = ['test_method'];
		
		return $result;
	}
}
