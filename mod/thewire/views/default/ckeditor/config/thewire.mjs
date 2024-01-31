import 'jquery';
import hooks from 'elgg/hooks';
import base from 'ckeditor/config/base';
import mentions from 'ckeditor/config/mentions';

// Using a hook to make the WordCount plugin function onUpdate editor specific
hooks.register('config', 'ckeditor', function(type, action, params, result) {
	var selector = params.selector;
	
	result.wordCount.onUpdate = function(stats) {
		var $input = $(selector);
		var max_length = $input.data('maxLength');
		if (max_length < 1) {
			return;
		}
		
		var $form = $input.closest('form');
		var $wrapper = $form.find('.thewire-characters-wrapper');
		if ($wrapper.length === 0) {
			return;
		}
		
		var $container = $wrapper.find('> div');
		var $submit_button = $form.find('button[type="submit"]');
		var remaining = max_length - stats.characters;
		$container.find('> span').text(remaining);
		
		if (remaining < 0) {
			$container.addClass('thewire-characters-remaining-warning');
			$submit_button.prop('disabled', true);
			$submit_button.addClass('elgg-state-disabled');
		} else {
			$container.removeClass('thewire-characters-remaining-warning');
			$submit_button.prop('disabled', false);
			$submit_button.removeClass('elgg-state-disabled');
		}
	}
	
	return result;
});

var config = $.extend(base, mentions, {
	toolbar: [],
	wordCount: {
		displayCharacters: false,
		displayWords: false
	}
});

// combine with extra plugins that need to be removed
config.removePlugins = config.removePlugins.concat(['Autoformat', 'Link', 'AutoLink', 'ImageInsert', 'AutoImage', 'Bold', 'Italic', 'Underline']);

export default config;
