<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'annotation:delete:likes:fail' => "Возникла ошибка при удалении отзыва",
	'annotation:delete:likes:success' => "Ваш отзыв удалён",
	
	'likes:this' => 'Одобряю',
	'likes:deleted' => 'Ваш отзыв удалён',
	'likes:see' => 'Посмотреть кто это одобрил',
	'likes:remove' => 'Снять одобрение',
	'likes:notdeleted' => 'Возникла ошибка при удалении отзыва',
	'likes:likes' => 'Вы это одобрили',
	'likes:failure' => 'Возникла ошибка при добавлении отзыва',
	'likes:alreadyliked' => 'Вы уже одобрили это',
	'likes:notfound' => 'Не найден объект, который вы хотите одобрить',
	'likes:likethis' => 'Одобрить',
	'likes:userlikedthis' => ': %s',
	'likes:userslikedthis' => ': %s',
	'likes:river:annotate' => 'Одобрений',
	'likes:delete:confirm' => 'Вы уверены, что хотите удалить этот отзыв?',

	'river:likes' => '%s одобрил(а) %s',

	// notifications. yikes.
	'likes:notifications:subject' => '%s одобряет ваше сообщение "%s"',
	'likes:notifications:body' =>
'%1$s нравится ваша публикация "%2$s" в %3$s

Оригинал вашей публикации:

%4$s

Просмотр профиля %1$s:

%5$s',
);
