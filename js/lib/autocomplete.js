/**
 * 
 */
elgg.provide('elgg.autocomplete');

elgg.autocomplete.init = function() {
	$('.elgg-input-autocomplete').autocomplete({
		source: elgg.autocomplete.url, //gets set by input/autocomplete
		minLength: 1,
		select: function(event, ui) {
			var item = ui.item;
			$(this).val(item.name);
	
			var hidden = $(this).next();
			hidden.val(item.guid);
		}
	})
	
	//@todo This seems convoluted
	.data("autocomplete")._renderItem = function(ul, item) {
		switch (item.type) {
			case 'user':
			case 'group':
				r = item.icon + item.name + ' - ' + item.desc;
				break;

			default:
				r = item.name + ' - ' + item.desc;
				break;
		}
		
		return $("<li/>")
			.data("item.autocomplete", item)
			.append(r)
			.appendTo(ul);
	};
};

elgg.register_hook_handler('init', 'system', elgg.autocomplete.init);