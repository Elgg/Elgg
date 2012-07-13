<?php
/**
 * Validate form specific javascript functions.
 *
 * @since 1.9
 */
?>

elgg.provide('elgg.validate');
elgg.provide('elgg.validate.checks');

elgg.validate.checks.required = function(evt, elem) {
	if (!elem.val()) {
		return elgg.echo('register:fields');
	}
}

elgg.validate.checks.email = function(evt, elem) {
    if (!/\S+@\S+\.\S+/.test(elem.val())) {
		return elgg.echo('registration:notemail');
	/*} else if (@todo get if email is already registered) {
		return elgg.echo('registration:dupeemail');*/
	}
}

elgg.validate.checks.username = function(evt, elem) {
	if (elem.val().length < 4) {
		return elgg.echo('registration:usernametooshort', [4]);
	} else if (elem.val().length > 128) {
		return elgg.echo('registration:usernametoolong', [128]);
	/*} else if (@todo check invalid chars) {
		return elgg.echo('registration:invalidchars');*/
	/*} else if (@todo check if username already exists) {
		return elgg.echo('registration:userexists');*/
	}
}

elgg.validate.checks.password1 = function(evt, elem) {
	if (elem.val().length < 6) {
		return elgg.echo('registration:passwordtooshort', [6]);
	}
}

elgg.validate.checks.password2 = function(evt, elem) {	
	if (elem.val() != elem.parent().prev().find('.elgg-validate-password1').val()) {
		return elgg.echo('RegistrationException:PasswordMismatch');
	}
}

elgg.validate.check = function(evt) {
	$this = $(this);
	
	// Do not check error while writting.
	if (evt.type == 'keyup' && !$this.hasClass('elgg-validate-error')) {
		return true;
	}
	
	allowed_checks = [];
	
	for (allowed_check in elgg.validate.checks) {
		allowed_checks.push(allowed_check);
	}

	var classes = $this.attr('class').split(/\s+/);
	var error = false;

	for (c in classes) {
		check = classes[c].replace('elgg-validate-', '');
		if ($.inArray(check, allowed_checks) > -1) {
			error = error || window['elgg']['validate']['checks'][check](evt, $this);
		}

	}

	if (error && !$this.hasClass('elgg-validate-error')) {
		$this.addClass('elgg-validate-error').after('<p class="elgg-text-help">'+error+'</p>');
	} else if (!error && $this.hasClass('elgg-validate-error')) {
		$this.removeClass('elgg-validate-error').next().remove();
	} else if (error && $this.hasClass('elgg-validate-error')) {
		$this.next().text(error);
	}
}

elgg.validate.init = function () {
	$('.elgg-validate-form .elgg-validate').blur(elgg.validate.check).keyup(elgg.validate.check);
	$('.elgg-validate-form .elgg-validate-password1').keyup(function() {
		$(this).parent().next().find('.elgg-validate-password2').blur();
	});
	$('.elgg-validate-form').submit(function() {
		$(this).find('.elgg-validate').blur();
		return !$(this).find('.elgg-validate-error').length;
	});
}

elgg.register_hook_handler('init', 'system', elgg.validate.init);
