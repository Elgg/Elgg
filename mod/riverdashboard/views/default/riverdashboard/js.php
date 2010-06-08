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

				$(likesList).load('<?php echo $vars['url'];?>mod/riverdashboard/endpoint/get_likes.php', params, function(data) {
					console.log(data);
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
		$('.river_show_more_comments').click(function() {
			var riverItem = $(this).closest('.river_item');
			var guid = riverItem.attr('id').replace('river_entity_', '');
			var commentsList = riverItem.find('.comments_list');
			var numComments = riverItem.find('.river_comment').length;

			var params = {
				'entity_guid': guid,
				'offset': numComments
			}

			$.post('<?php echo $vars['url'];?>mod/riverdashboard/endpoint/get_comments.php', params, function(data) {
				commentsList.prepend(data);
				commentsList.prev('.river_show_more_comments').hide();
			});
		});
	});
</script>