<script type="text/javascript">
	$(document).ready(function() {
		$('.river_comment_form_button').click(function() {
			elgg_slide_toggle(this, '.river_item', '.river_comment_form');
		});

		$('.likes_user_list_button').click(function() {
			var myParent = $(this).closest('.river_item');
			var likesList = myParent.find('.likes_list');

			if (likesList.css('display') == 'none') {
				// pull in likes via ajax to save on loading many avatars
				var riverItem = $(this).closest('.river_item');
				var guid = riverItem.attr('id').replace('river_entity_', '');

				var params = {
					'entity_guid': guid
				}

				$(likesList).load('<?php echo elgg_get_site_url();?>mod/riverdashboard/endpoint/get_likes.php', params, function(data) {
					// hide comments
					myParent.find('.comments_container').animate({"height": "toggle", "opacity": "toggle"}, { duration: 400 });
					// change selected tab
					myParent.find('.show_comments_button').addClass('off');
					myParent.find('.likes_user_list_button').removeClass('off');
					// show users that liked object
					elgg_slide_toggle(this, '.river_item', '.likes_list');
				});
			}
		});

		$('.show_comments_button').click(function() {
			var myParent = $(this).closest('.river_item');
			if (myParent.find('.comments_container').css('display') == 'none') {
				// hide likes
				myParent.find('.likes_list').animate({"height": "toggle", "opacity": "toggle"}, { duration: 400 });
				// change selected tab
				myParent.find('.show_comments_button').removeClass('off');
				myParent.find('.likes_user_list_button').addClass('off');
				// show users that liked object
				elgg_slide_toggle(this, '.river_item', '.comments_container');
			}
		});

		// grab more comments
		$('.river_more_comments.show_more_button').click(function() {
			var showLess = $(this).next('.show_less_button');
			var showMore = $(this);
			var riverItem = $(this).closest('.river_item');

			var guid = riverItem.attr('id').replace('river_entity_', '');
			var commentsList = riverItem.find('.comments_list');
			var numComments = riverItem.find('.river_comment').length;

			var params = {
				'entity_guid': guid,
				'offset': numComments
			}

			$.post('<?php echo elgg_get_site_url();?>mod/riverdashboard/endpoint/get_comments.php', params, function(data) {
				commentsList.prepend(data);

				showLess.toggle();
				showMore.toggle();

			});
		});

		// hide more comments
		$('.river_more_comments.show_less_button').click(function() {
			var showLess = $(this);
			var showMore = $(this).prev('.show_more_button');
			var riverItem = $(this).closest('.river_item');
			// want to keep the latest 3 comments
			var comments = riverItem.find('.river_comment')
			comments = $.makeArray(comments).reverse();
			//reverse().splice(0, 3);

			len = comments.length;

			for (i=3; i<len; i++) {
				$(comments[i]).empty().remove();
			}


			// remove them so we can force an ajax update when clicked again.

			showLess.toggle();
			showMore.toggle();

		});
	});

	// re-add avatar menus for new avatars
	//setup_avatar_menu($('.river_item_list'));
</script>
