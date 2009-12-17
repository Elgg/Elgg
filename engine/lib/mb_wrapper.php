<?php

// if mb functions are available, set internal encoding to UTF8
if (is_callable('mb_internal_encoding')) {
	mb_internal_encoding("UTF-8");
}

// map string functions to their mb_str_func alternatives
// and wrap them in elgg_str_fun()

// list of non-mb safe string functions to wrap in elgg_*()
// only will work with mb_* functions that take the same
// params in the same order as their non-mb safe counterparts.
$str_funcs = array(
	'parse_str',
	'split',
	'stristr',
	'strlen',
	'strpos',
	'strrchr',
	'strripos',
	'strrpos',
	'strstr',
	'strtolower',
	'strtoupper',
	'substr_count',
	'substr'
);

$eval_statement = '';
foreach ($str_funcs as $func) {
	// create wrapper function passing in the same args as given
	$mb_func = "mb_$func";
	$eval_statement .= "
	function elgg_$func() {
		\$args = func_get_args();
		if (is_callable('$mb_func')) {
			return call_user_func_array('$mb_func', \$args);
		}
		return call_user_func_array('$func', \$args);
	}
";
}

eval($eval_statement);

// TODO: Other wrapper functions