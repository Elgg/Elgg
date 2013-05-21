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
		var multi = $this.attr('multiple') !== undefined;
		var $content = $this.prev('.elgg-autocomplete-content');
		var $delBut = $content.prev();
		$delBut.detach();
		$delBut.removeClass('hidden');
		if (multi) {
			var $hf = $('<input type="hidden">').attr('name', $this.attr('name')+'[]');
			$this.removeAttr('name');
		}
		$this.autocomplete({
			source: $this.data('url'), //gets set by input/autocomplete view
			select: function(event, ui) {
				var $item = $(ui.item.label);
				$item.removeClass('elgg-autocomplete-item');
				
				$wrapper = $('<li class="elgg-autocomplete-item">');
				$wrapper.append($delBut.clone(true, true));
				$wrapper.append($item);
				$content.append($wrapper);
				
				$content.removeClass('hidden');
				if (!multi) {
					$this.addClass('hidden');
				} else {
					$wrapper.append($hf.clone(true, true).val(ui.item.value));
					$this.val('');
					return false;//don't update the value
				}
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
	var $item = $(this).parents('.elgg-autocomplete-item').first();
	var $container = $item.parents('.elgg-autocomplete-content').first();
	var $input = $container.next('.elgg-input-autocomplete');
	
	$item.remove();

	if ($container.children().length == 0) {
		$container.addClass('hidden');//only if empty
	}
	$input.removeClass('hidden');
	$input.val('');
	return false;
}

elgg.register_hook_handler('init', 'system', elgg.autocomplete.init);