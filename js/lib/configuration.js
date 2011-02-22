elgg.provide('elgg.config');

elgg.config.wwwroot = '/';

elgg.get_site_url = function() {
	return elgg.config.wwwroot;
}