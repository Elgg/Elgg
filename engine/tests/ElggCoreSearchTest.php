<?php

/**
 * Test elgg_search()
 */
class ElggCoreSearchTest extends \ElggCoreGetEntitiesBaseTest {

	public function testCanSearchUsersByAttributeFields() {

		$users = elgg_search([
			'type' => 'user',
			'fields' => ['username'],
			'query' => 'test_user_',
		]);

		$this->assertTrue((bool) $users);
		foreach ($users as $user) {
			$this->assertEqual(1, preg_match('/test_user_/i', $user->username));
		}
	}

	public function testCanSearchUsersByExactMatchInMetadataFields() {

		$callback = function(\Elgg\Hook $hook) {
			$fields = $hook->getValue();
			$fields[] = 'country';
			return $fields;
		};

		elgg_register_plugin_hook_handler('search:fields', 'user', $callback);

		$users = elgg_search([
			'type' => 'user',
			'fields' => ['country'],
			'query' => 'united states',
			'partial_match' => false,
			'tokenize' => false,
		]);

		$this->assertTrue((bool) $users);
		foreach ($users as $user) {
			$this->assertEqual('United States', $user->country);
		}

		elgg_unregister_plugin_hook_handler('search:fields', 'user', $callback);
	}

	public function testCanSearchUsersByTokensInMetadataFields() {

		$callback = function(\Elgg\Hook $hook) {
			$fields = $hook->getValue();
			$fields[] = 'country';
			return $fields;
		};

		elgg_register_plugin_hook_handler('search:fields', 'user', $callback);

		$users = elgg_search([
			'type' => 'user',
			'fields' => ['country'],
			'query' => ' argentina ',
			'partial_match' => false,
			'tokenize' => true,
		]);

		$this->assertTrue((bool) $users);
		foreach ($users as $user) {
			$this->assertEqual($user->country, 'Argentina');
		}

		elgg_unregister_plugin_hook_handler('search:fields', 'user', $callback);
	}

	public function testCanSearchUsersByPartialMatchInMetadataFields() {

		$callback = function(\Elgg\Hook $hook) {
			$fields = $hook->getValue();
			$fields[] = 'country';
			return $fields;
		};

		elgg_register_plugin_hook_handler('search:fields', 'user', $callback);

		$users = elgg_search([
			'type' => 'user',
			'fields' => ['country'],
			'query' => 'unit',
			'partial_match' => true,
			'tokenize' => false,
		]);

		$this->assertTrue((bool) $users);
		foreach ($users as $user) {
			$this->assertTrue(in_array($user->country, ['United States', 'United Arab Emirates']));
		}

		elgg_unregister_plugin_hook_handler('search:fields', 'user', $callback);
	}

	public function testCanSearchUsersByTokensWithPartialMatchInMetadataFields() {

		$callback = function(\Elgg\Hook $hook) {
			$fields = $hook->getValue();
			$fields[] = 'country';
			return $fields;
		};

		elgg_register_plugin_hook_handler('search:fields', 'user', $callback);

		$users = elgg_search([
			'type' => 'user',
			'fields' => ['country'],
			'query' => 'united tes',
			'partial_match' => true,
			'tokenize' => true,
		]);

		$this->assertTrue((bool) $users);
		foreach ($users as $user) {
			$this->assertTrue(in_array($user->country, ['United States', 'United Arab Emirates']));
		}

		elgg_unregister_plugin_hook_handler('search:fields', 'user', $callback);
	}


	public function testCanSearchUsersAndSortByAttributeField() {

		$users = elgg_search([
			'type' => 'user',
			'sort' => 'username',
			'direction' => 'ASC',
			'query' => 'test_user_',
			'limit' => 2,
		]);

		$this->assertTrue((bool) $users);
		$this->assertTrue($users[0]->username > $users[1]->username);
	}

	public function testCanSearchUsersAndSortByMetadataField() {

		$callback = function(\Elgg\Hook $hook) {
			$fields = $hook->getValue();
			$fields[] = 'country';
			return $fields;
		};

		elgg_register_plugin_hook_handler('search:fields', 'user', $callback);

		$users = elgg_search([
			'type' => 'user',
			'sort' => 'country',
			'order' => 'ASC',
			'query' => 'test_user_',
			'limit' => 1,
			'order_by' => 'e.guid ASC',
		]);

		$this->assertTrue((bool) $users);
		$this->assertEqual('Argentina', $users[0]->country);

		elgg_unregister_plugin_hook_handler('search:fields', 'user', $callback);

	}
}
