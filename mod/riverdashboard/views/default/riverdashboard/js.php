<script type="text/javascript">
	$(document).ready(function() {
		$('.river-comment-form-button').click(function() {
			elgg_slide_toggle(this, '.elgg-module', '.river-comment-form');
		});

		$('.likes-user-list-button').click(function() {
			var myParent = $(this).closest('.elgg-river-item');
			var likesList = myParent.find('.likes-list');

			if (likesList.css('display') == 'none') {
				// pull in likes via ajax to save on loading many avatars
				var riverItem = $(this).closest('.elgg-river-item');
				var guid = riverItem.attr('id').replace('river-entity-', '');

				var params = {
					'entity_guid': guid
				}

				$(likesList).load('<?php echo elgg_get_site_url();?>mod/riverdashboard/endpoint/get_likes.php', params, function(data) {
					// hide comments
					myParent.find('.comments-container').animate({"height": "toggle", "opacity": "toggle"}, { duration: 400 });
					// change selected tab
					myParent.find('.show-comments-button').addClass('off');
					myParent.find('.likes-user-list-button').removeClass('off');
					// show users that liked object
					elgg_slide_toggle(this, '.elgg-river-item', '.likes-list');
				});
			}
		});

		$('.show-comments-button').click(function() {
			var myParent = $(this).closest('.elgg-river-item');
			if (myParent.find('.comments-container').css('display') == 'none') {
				// hide likes
				myParent.find('.likes-list').animate({"height": "toggle", "opacity": "toggle"}, { duration: 400 });
				// change selected tab
				myParent.find('.show-comments-button').removeClass('off');
				myParent.find('.likes-user-list-button').addClass('off');
				// show users that liked object
				elgg_slide_toggle(this, '.elgg-river-item', '.comments-container');
			}
		});

		// grab more comments
		$('.river-more-comments.show-more-button').click(function() {
			var showLess = $(this).next('.show-less-button');
			var showMore = $(this);
			var riverItem = $(this).closest('.elgg-river-item');

			var guid = riverItem.attr('id').replace('river-entity-', '');
			var commentsList = riverItem.find('.comments-list');
			var numComments = riverItem.find('.river-comment').length;

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
		$('.river-more-comments.show-less-button').click(function() {
			var showLess = $(this);
			var showMore = $(this).prev('.show-more-button');
			var riverItem = $(this).closest('.elgg-river-item');
			// want to keep the latest 3 comments
			var comments = riverItem.find('.river-comment')
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
	//setup_avatar_menu($('.river-item_list'));
</script>
