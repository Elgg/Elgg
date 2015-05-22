define(function (require) {
	var areyousure = require('elgg/echo!question:areyousure'),
		riverfriend = require('elgg/echo!river:friend:user:default');

	console.log(areyousure() === elgg.echo('question:areyousure'));

	console.log(riverfriend(['Alice', 'Bob']) === elgg.echo('river:friend:user:default', ['Alice', 'Bob']));
});
