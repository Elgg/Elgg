<?php

	/**
	 * Update client language pack.
	 * 
	 * @package ElggUpdateClient
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */	

	$russian = array(
	
		'updateclient:label:core' => 'Ядро',
		'updateclient:label:plugins' => 'Приложения',
	
		'updateclient:settings:days' => 'Проверять на наличие обновлений каждые',
		'updateclient:days' => 'дней',
	
		'updateclient:settings:server' => 'Обновить сервер',
	
		'updateclient:message:title' => 'Вышла новая версия Elgg!',
		'updateclient:message:body' => 'Вышла новая верся Elgg (%s %s) под кодовым названием "%s"!
		
Скачать её можно здесь: %s

Или прочитайте аннотацию к новой версии:

%s',
	);
					
	add_translation("ru", $russian);
?>