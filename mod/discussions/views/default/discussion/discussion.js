require(['elgg'], function(elgg) {
	elgg.deprecated_notice('discussion/discussion.js has been deprecated. Use object/discussion_reply AMD module instead', '2.2');

	if (typeof elgg.discussion === 'undefined') {
		elgg.discussion = require(['object/discussion_reply']);
	}
});