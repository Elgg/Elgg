// Open https://www.transifex.com/languages/
// Open console
// Run this:

i18n_codes = (function(){
	var codes = {};
	$("#txlanguage-list li").each(function () {
		var $span = $('span', this);
		var code = $.trim($span.text()).replace(/[\(\))]/g, "").replace(/[^a-zA-Z0-9]/g, '_');
		$span.remove();
		var name = $.trim($(this).text());
		codes[code] = name;
	});

	var out = "\u0009\u0009return [\u000A";
	$.each(codes, function (code, name) {
		out += "\u0009\u0009\u0009\"" + code + "\", // " + name + "\u000A";
	});
	out += "\u0009\u0009];";

	$('body').html($("<pre />").text(out));

	return codes;
})();

// Copy into \Elgg\I18n\Translator::getAllLanguageCodes

// Run this:

!(function () {
	var out = "";
	$.each(i18n_codes, function (code, name) {
		out += "\u0009\"" + code + "\" => \"" + name + "\",\u000A";
	});

	$('body').html($("<pre />").text(out));
})();

// Copy into languages/en.php
