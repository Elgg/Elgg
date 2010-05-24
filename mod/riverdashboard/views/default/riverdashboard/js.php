<script type="text/javascript">
	$(document).ready(function() {
		$('.river_comment_form_button').click(function() {
			elgg_slide_toggle(this, '.river_item', '.river_comment_form');
		});

		$('.likes_user_list_button').click(function() {
			var myParent = $(this).closest('.river_item');
			if (myParent.find('.likes_list').css('display') == 'none') {
				// hide comments
				myParent.find('.river_comment').animate({"height": "toggle", "opacity": "toggle"}, { duration: 400 });
				// change selected tab
				myParent.find('.show_comments_button').addClass('off');
				myParent.find('.likes_user_list_button').removeClass('off');
				// show users that liked object
				elgg_slide_toggle(this, '.river_item', '.likes_list');
			}
		});

		$('.show_comments_button').click(function() {
			var myParent = $(this).closest('.river_item');
			if (myParent.find('.river_comment').css('display') == 'none') {
				// hide comments
				myParent.find('.likes_list').animate({"height": "toggle", "opacity": "toggle"}, { duration: 400 });
				// change selected tab
				myParent.find('.show_comments_button').removeClass('off');
				myParent.find('.likes_user_list_button').addClass('off');
				// show users that liked object
				elgg_slide_toggle(this, '.river_item', '.river_comment');
			}
		});

	});
</script>