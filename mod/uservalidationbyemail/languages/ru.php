<?php
	/**
	 * Email user validation plugin language pack.
	 * 
	 * @package ElggUserValidationByEmail
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$russian = array(
	
		'email:validate:subject' => "%s, пожалуйста, подтвердите ваш адрес электронной почты!",
		'email:validate:body' => "Привет %s,

Пожалуйста, подтвердите ваш адрес электронной почты нажав на следующую ссылку:

%s
",
		'email:validate:success:subject' => "Адрес электронной почты подтверждён%s!",
		'email:validate:success:body' => "Привет %s,
			
Поздравляем, вы успешно подтвердили ваш адрес электронной почты.",
	
		'uservalidationbyemail:registerok' => "Чтобы активировать ваш аккаунт, пожалуйста подтвердите ваш адрес электронной почты нажатием на ссылку, которую мы вам выслали."
	
	);
					
	add_translation("ru",$russian);
?>