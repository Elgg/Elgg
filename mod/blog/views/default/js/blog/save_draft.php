<?php
/**
 * Save draft through ajax
 *
 * @package Blog
 */
?>

<script type="text/javascript">
	setInterval("blogSaveDraft()", 60000);

	/*
	 * Attempt to save and update the input with the guid.
	 */
	function blogSaveDraftCallback(data, textStatus, XHR) {
		if (textStatus == 'success' && data.success == true) {
			var form = $('form[name=blog_post]');

			// update the guid input element for new posts that now have a guid
			form.find('input[name=guid]').val(data.guid);

			oldDescription = form.find('textarea[name=description]').val();

			var d = new Date();
			var mins = d.getMinutes() + '';
			if (mins.length == 1) {
				mins = '0' + mins;
			}
			$(".blog-save-status-time").html(d.toLocaleDateString() + " @ " + d.getHours() + ":" + mins);
		} else {
			$(".blog-save-status-time").html("<?php echo elgg_echo('error'); ?>");
		}
	}

	function blogSaveDraft() {
		if (typeof(tinyMCE) != 'undefined') {
			tinyMCE.triggerSave();
		}

		// only save on changed content
		var form = $('form[name=blog_post]');
		var description = form.find('textarea[name=description]').val();
		var title = form.find('input[name=title]').val();

		if (!(description && title) || (description == oldDescription)) {
			return false;
		}

		var draftURL = "<?php echo elgg_get_site_url(); ?>action/blog/auto_save_revision";
		var postData = form.serializeArray();

		// force draft status
		$(postData).each(function(i, e) {
			if (e.name == 'status') {
				e.value = 'draft';
			}
		});

		$.post(draftURL, postData, blogSaveDraftCallback, 'json');
	}

	$(document).ready(function() {
		// get a copy of the body to compare for auto save
		oldDescription = $('form[name=blog_post]').find('textarea[name=description]').val();

/* we don't seems to be using the text counter anymore�
		$('#excerpt.excerpt').each(function(){
			var allowed = 200;

			// set the initial value
			$('#countervalue').text(allowed);

			// bind on key up event
			$(this).keyup(function(){
				var counter_value = ((allowed - ($(this).val().length)));

				$("#countervalue").removeClass();

				if ((counter_value > 10)) {
					$("#countervalue").addClass("positive");
				}
				else if ((counter_value <= 10) && (counter_value >= 0)) {
					$("#countervalue").addClass("gettingclose");
				}
				else if ((counter_value < 0)) {
					$("#countervalue").addClass("negative");
				}

				// insert new length
				$('#countervalue').text(counter_value);

			});
		});
*/
	});

</script>
