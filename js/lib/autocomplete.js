/**
 * 
 */
elgg.provide('elgg.autocomplete');

elgg.autocomplete.init = function() {
	$('.elgg-input-autocomplete').autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: elgg.autocomplete.url, //gets set by input/autocomplete
				dataType: "json",
				data: {
					q: request.term
				},
				success: function( data ) {
					response( $.map( data, function( item ) {
						item.value = item.name;
						return item;
					}));
				}
			})
		},
		minLength: 1,
		select: function(event, ui) {
			var item = ui.item;
			item.value = item.name;
				
			if($(this).next().attr('type') == "hidden"){
				var hidden = $(this).next();
			} else {
				var hidden = $(this).after('<input type="hidden" name="'+this.name+'[]" />').next();
			}
			hidden.val(item.guid);
		}
	})
	
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
			.append('<a>'+r+'</a>')
			.appendTo(ul);
	};
};

elgg.register_hook_handler('init', 'system', elgg.autocomplete.init);
