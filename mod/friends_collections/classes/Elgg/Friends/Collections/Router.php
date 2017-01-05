<?php

namespace Elgg\Friends\Collections;

class Router {

	/**
	 * Page handler for friends collections
	 *
	 * @param array $segments URL segments
	 *
	 * @return bool
	 * @access private
	 */
	public static function collectionsPageHandler($segments) {

		elgg_push_context('friends');

		$page = array_shift($segments);

		switch ($page) {
			case 'add':
				$username = array_shift($segments);
				echo elgg_view_resource('friends/collections/add', [
					'username' => $username,
				]);
				return true;
				
			case 'edit':
				$collection_id = array_shift($segments);
				echo elgg_view_resource('friends/collections/edit', [
					'collection_id' => $collection_id,
				]);
				return true;

			case 'view' :
				$collection_id = array_shift($segments);
				echo elgg_view_resource('friends/collections/view', [
					'collection_id' => $collection_id,
				]);
				return true;

			case 'owner':
			default :
				$username = array_shift($segments);
				echo elgg_view_resource('friends/collections/owner', [
					'username' => $username,
				]);
				return true;
		}

		return false;
	}
}
