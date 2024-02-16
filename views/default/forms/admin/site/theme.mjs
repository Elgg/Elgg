import 'jquery';

$(document).on('click', '.elgg-button[data-original-theme-var]', function() {
	$button = $(this);
	$button.closest('tr').find('.elgg-input-text').val($button.data().originalThemeVar);
	$button.closest('td').html('&nbsp;');
});
