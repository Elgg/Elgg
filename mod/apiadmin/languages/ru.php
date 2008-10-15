<?php
	/**
	 * API Admin language pack.
	 * 
	 * @package ElggAPIAdmin
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */


	$russian = array(
	
		/**
		 * Menu items and titles
		 */
	
			'apiadmin' => 'Управление API',
	
	
			'apiadmin:keyrevoked' => 'Ключ API отозван',
			'apiadmin:keynotrevoked' => 'Ключ API не может быть отозван',
			'apiadmin:generated' => 'Ключ API успешно сгенерирован',
	
			'apiadmin:yourref' => 'Наименование',
			'apiadmin:generate' => 'Сгенерировать новую пару ключей',
	
			'apiadmin:noreference' => 'Необходимо указать наименование для вашего нового ключа.',
			'apiadmin:generationfail' => 'При генерации ключей случилась ошибка',
			'apiadmin:generated' => 'Новая пара ключей API успешно сгенерирована',
	
			'apiadmin:revoke' => 'Отозвать ключ',
			'apiadmin:public' => 'Публичный',
			'apiadmin:private' => 'Приватный',

	
			'item:object:api_key' => 'Ключи API',
	);
					
	add_translation("ru",$russian);
?>