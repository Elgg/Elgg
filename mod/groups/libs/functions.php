<?php 

/**
 * List blogs
 *
 * @param string $selected_tab listing view (alpha, featured, popular or newest)
 * @param array  $vars         vars for the view
 *
 * @return string
 */
 
function groups_listing($selected_tab, $vars) {
	switch ($selected_tab) {
		case 'alpha':
			// alpha listing
			return elgg_list_entities([
				'type' => 'group',
				'order_by_metadata' => [
					'name' => 'name',
					'direction' => 'ASC',
				],
				'full_view' => false,
				'no_results' => elgg_echo('groups:none'),
			]);
			break;
		case 'featured':
			// featured lisitng
			return elgg_list_entities([
				'type' => 'group',
				'metadata_name' => 'featured_group',
				'metadata_value' => 'yes',
				'full_view' => false,
				'no_results' => elgg_echo('groups:nofeatured'),
			]);
			break;
		case 'popular':
			// popular listing
			return elgg_list_entities_from_relationship_count([
				'type' => 'group',
				'relationship' => 'member',
				'inverse_relationship' => false,
				'full_view' => false,
				'no_results' => elgg_echo('groups:none'),
			]);
			break;
		default:
			// newest listing
			return elgg_list_entities([
				'type' => 'group',
				'full_view' => false,
				'no_results' => elgg_echo('groups:none'),
			]);
			break;
	}
}

 ?>