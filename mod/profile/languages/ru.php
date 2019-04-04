<?php

return array(
	'profile' => 'Профиль',
	'profile:notfound' => 'Извините, не удается найти указанный профиль.',
	'profile:upgrade:2017040700:title' => 'Migrate schema of profile fields',
	'profile:upgrade:2017040700:description' => 'This migration converts profile fields from metadata to annotations with each name
prefixed with "profile:". <strong>Note:</strong> If you have "inactive" profile fields you want migrated, re-create those fields
and re-load this page to make sure they get migrated.',
	
	'admin:configure_utilities:profile_fields' => 'Изменить поля профиля',
	
	'profile:edit' => 'Изменить профиль',
	'profile:aboutme' => "Обо мне",
	'profile:description' => "Обо мне",
	'profile:briefdescription' => "Краткое описание",
	'profile:location' => "Местоположение",
	'profile:skills' => "Навыки",
	'profile:interests' => "Интересы",
	'profile:contactemail' => "Электронная почта",
	'profile:phone' => "Телефон",
	'profile:mobile' => "Мобильный телефон",
	'profile:website' => "Страница в соц.сетях",
	'profile:twitter' => "Логин Twitter",
	'profile:saved' => "Ваш профиль успешно сохранён.",

	'profile:field:text' => 'Краткий текст',
	'profile:field:longtext' => 'Большая текстовая область',
	'profile:field:tags' => 'Теги',
	'profile:field:url' => 'Веб адрес',
	'profile:field:email' => 'Электронная почта',
	'profile:field:location' => 'Местоположение',
	'profile:field:date' => 'Дата',

	'profile:edit:default' => 'Изменить поля профиля',
	'profile:label' => "Метка профиля",
	'profile:type' => "Тип профиля",
	'profile:editdefault:delete:fail' => 'Removing profile field failed',
	'profile:editdefault:delete:success' => 'Profile field deleted',
	'profile:defaultprofile:reset' => 'Profile fields reset to the system default',
	'profile:resetdefault' => 'Reset profile fields to system defaults',
	'profile:resetdefault:confirm' => 'Are you sure you want to delete your custom profile fields?',
	'profile:explainchangefields' => "You can replace the existing profile fields with your own.
Click the 'Add' button and give the new profile field a label, for example, 'Favorite team', then select the field type (eg. text, url, tags).
To re-order the fields drag on the handle next to the field label.
To edit a field label - click on the edit icon.

At any time you can revert back to the default profile set up, but you will lose any information already entered into custom fields on profile pages.",
	'profile:editdefault:success' => 'New profile field added',
	'profile:editdefault:fail' => 'Default profile could not be saved',
	'profile:noaccess' => "You do not have permission to edit this profile.",
	'profile:invalid_email' => '%s must be a valid email address.',
);
