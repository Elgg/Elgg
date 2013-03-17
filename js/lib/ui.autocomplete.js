/**
 * Entity autocomplete selector.
 */
elgg.provide('elgg.autocomplete');

/**
 * Register the autocomplete input.
 */
elgg.autocomplete.init = function() {
	$('.elgg-input-autocomplete').each(function(){
		var $this = $(this);
		$this.autocomplete({
			source: $this.data('url'), //gets set by input/autocomplete view
			select: function(event, ui) {
				var $content = $this.prev('.elgg-autocomplete-content');
				var $item = $(ui.item.label);
				$item.removeClass('elgg-autocomplete-item');
				$content.append($item);
				$content.removeClass('hidden');
				$this.addClass('hidden');
			},
			minLength: 2,
			html: "html"
		})
	});
	
	$('.elgg-autocomplete-clear').live('click', elgg.autocomplete.clearSelection);
};

/**
 * Handler attached to button that removes current autocomplete selection
 */
elgg.autocomplete.clearSelection = function(){
	var $container = $(this).parents('.elgg-autocomplete-content').first();
	var $input = $container.next('.elgg-input-autocomplete');
	var $button = $container.children('.elgg-menu-autocomplete').detach();
	$container.html($button);
	$container.addClass('hidden');
	$input.removeClass('hidden');
	$input.val('');
	return false;
}

elgg.register_hook_handler('init', 'system', elgg.autocomplete.init);