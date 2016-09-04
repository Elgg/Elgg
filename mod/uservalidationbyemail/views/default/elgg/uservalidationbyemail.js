/**
 * Uservalidationbyemail module
 *
 * @note The name is required for inlining, do not remove it
 */
define('elgg/uservalidationbyemail', function (require) {
	var $ = require('jquery');
	var elgg = require('elgg');

	function init() {
		$('#uservalidationbyemail-checkall').click(function() {
			$('#uservalidationbyemail-form .elgg-body').find('input[type=checkbox]').prop('checked', this.checked);
		});

		$('.uservalidationbyemail-submit').click(function(event) {
			var form = $('#uservalidationbyemail-form')[0];
			event.preventDefault();

			// check if there are selected users
			if ($('.elgg-body', form).find('input[type=checkbox]:checked').length < 1) {
				return false;
			}

			// confirmation
			if (!confirm(this.title)) {
				return false;
			}

			form.action = this.href;
			form.submit();
		});
	}

	elgg.register_hook_handler('init', 'system', init);

	/**
	 * elgg.uservalidationbyemail object is deprecated. Do not call it directly.
	 * @deprecated 2.3
	 */
	elgg.uservalidationbyemail = {
		init: init
	};
});
