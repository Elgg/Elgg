<?php
/**
 * The wire's JavaScript
 */

$site_url = elgg_get_site_url();

?>

elgg.provide('elgg.thewire');

elgg.thewire.init = function() {
	$("#thewire-textarea").live('keydown', function() {
		elgg.thewire.textCounter(this, $("#thewire-characters-remaining span"), 140);
	});
	$("#thewire-textarea").live('keyup', function() {
		elgg.thewire.textCounter(this, $("#thewire-characters-remaining span"), 140);
	});

	$(".thewire-previous").live('click', elgg.thewire.viewPrevious);

	$('#thewire-submit-button').live('click', function(event) {
		event.preventDefault();
		elgg.thewire.updateEntityList($("#thewire-textarea"), 'thewire/add', true);
		elgg.thewire.textCounter("#thewire-textarea", $("#thewire-characters-remaining span"), 140);
	});
	
	$('.ajax-delete').live('click', function(event) {
		event.preventDefault();
		elgg.thewire.deleteEntityListItem(this);
	});
}

/**
 * Update the number of characters left with every keystroke
 *
 * @param {Object}  textarea
 * @param {Object}  status
 * @param {integer} limit
 * @return void
 */
elgg.thewire.textCounter = function(textarea, status, limit) {

	var remaining_chars = limit - $(textarea).val().length;
	status.html(remaining_chars);
	if (remaining_chars < 0) {
		status.parent().css("color", "#D40D12");
		$("#thewire-submit-button").attr('disabled', 'disabled');
		$("#thewire-submit-button").addClass('elgg-state-disabled');
	} else {
		status.parent().css("color", "");
		$("#thewire-submit-button").removeAttr('disabled', 'disabled');
		$("#thewire-submit-button").removeClass('elgg-state-disabled');
	}
}

/**
 * Display the previous wire post
 *
 * Makes Ajax call to load the html and handles changing the previous link
 *
 * @param {Object} event
 * @return void
 */
elgg.thewire.viewPrevious = function(event) {
	var $link = $(this);
	var postGuid = $link.attr("href").split("/").pop();
	var $previousDiv = $("#thewire-previous-" + postGuid);

	if ($link.html() == "<?php echo elgg_echo('thewire:hide'); ?>") {
		$link.html("<?php echo elgg_echo('thewire:previous'); ?>");
		$link.attr("title", "<?php echo elgg_echo('thewire:previous:help'); ?>");
		$previousDiv.slideUp(400);
	} else {
		$link.html("<?php echo elgg_echo('thewire:hide'); ?>");
		$link.attr("title", "<?php echo elgg_echo('thewire:hide:help'); ?>");
		
		$.ajax({type: "GET",
			url: "<?php echo $site_url . "ajax/view/thewire/previous"; ?>",
			dataType: "html",
			cache: false,
			data: {guid: postGuid},
			success: function(htmlData) {
				if (htmlData.length > 0) {
					$previousDiv.html(htmlData);
					$previousDiv.slideDown(600);
				}
			}
		});

	}

	event.preventDefault();
}

/**
 * XHR handler function for updating a elgg-entity-list
 *
 * @param {Object} source
 * @param {String} page
 * @param {Boolean} prepend
 * @return void
 */

elgg.thewire.updateEntityList = function(source, page, prepend)  {
	elgg.action(page, {
		data: {
			body: $(source).val()
		},
		cache: false,
		success: function(json) {
				if (json.status != '-1') {
					$(source).val('');
					var tempDiv = document.createElement('div');
					$(tempDiv).html(json.output);
					if(prepend) {
						$('.elgg-entity-list').prepend($(tempDiv).find('li:first'));
					} else {
						$('.elgg-entity-list').append($(tempDiv).find('li:first'));
					}
					$(tempDiv).remove();
				}
		}
	});
}

/**
 * XHR handler function for deleting a elgg-entity-listitem
 *
 * @param {Object} object
 * @return void
 */

elgg.thewire.deleteEntityListItem = function(object) {
	elgg.action(object.href, {
		success: function(json) {
			if (json.status != '-1') {
				var guid = json.current_url.split('?')[1].split('&')[0].split('=')[1]; //Any other trivial way to get guid?
				$('#elgg-object-'+guid).remove();
			}
		}
	});
}

 
elgg.register_hook_handler('init', 'system', elgg.thewire.init);

