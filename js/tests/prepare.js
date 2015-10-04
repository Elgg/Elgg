// These modules are typically built by PHP on the server. We can't do that with the test runner.
var elgg = elgg || {};

define('elgg', function() {
	return elgg;
});
define('languages/early/en', {
	"js:lightbox:current": "image %s of %s",
	"next": "Next"
});
define('languages/late/en', {
	"ajax:error": "Unexpected error"
});
define('languages/early/es', {
	//'js:lightbox:current': "imagen %s de %s",
	'next': "Siguiente"
});
define('languages/late/es', {
	"ajax:error": "Error inesperado"
});
