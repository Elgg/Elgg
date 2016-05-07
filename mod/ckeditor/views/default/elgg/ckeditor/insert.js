/**
 * Insert embed media from embed plugin
 *
 * This JavaScript view is extending the view embed/embed.js
 *
 * @deprecated 2.2
 */
require(['elgg', 'elgg/ckeditor'], function(elgg, elggCKEditor) {
	elgg.deprecated_notice('elgg/ckeditor/insert.js view has been deprecated. You should not need to use it. The handlers are now registered by elgg/ckeditor module', '2.2');
	elggCKEditor.registerHandlers();
});
