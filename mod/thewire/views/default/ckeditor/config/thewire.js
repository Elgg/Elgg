define(['jquery', 'elgg', 'ckeditor/config/base', 'ckeditor/config/mentions'], function($, elgg, base, mentions) {
	return $.extend(base, mentions, {
		removePlugins: ['Autoformat', 'Link', 'AutoLink', 'ImageInsert', 'AutoImage', 'Bold', 'Italic', 'Underline'],
		toolbar: [],
		wordCount: {
			displayCharacters: false,
			displayWords: false,
			onUpdate: function (stats) {
				var $input = $('#thewire-textarea');
				var max_length = $input.data('maxLength');
				if (max_length < 1) {
					return;
				}
				
				$container = $('#thewire-characters-remaining');
				var remaining = max_length - stats.characters;
				$container.find('> span').text(remaining);
				
				if (remaining < 0) {
					$container.addClass('thewire-characters-remaining-warning');
					$('#thewire-submit-button').prop('disabled', true);
					$('#thewire-submit-button').addClass('elgg-state-disabled');
				} else {
					$container.removeClass('thewire-characters-remaining-warning');
					$('#thewire-submit-button').prop('disabled', false);
					$('#thewire-submit-button').removeClass('elgg-state-disabled');
				}
			}
		}
	});
});
