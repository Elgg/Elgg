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
	/**
	 *  Chinese Language Package
	 * 
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @translator Cosmo Mao
	 * @copyright cOSmoCommerce.com 2008
	 * @link http://www.elggsns.cn/
	 * @version 0.1
	 */
	$chinese = array(
	
		'updateclient:label:core' => '核心',
		'updateclient:label:plugins' => '插件',
	
		'updateclient:settings:days' => '检查更新每',
		'updateclient:days' => '天',
	
		'updateclient:settings:server' => '更新服务器',
	
		'updateclient:message:title' => '新版本系统发布了！',
		'updateclient:message:body' => '新的Elgg系统 (%s %s) 代号 "%s" 发布了!
		
下载地址: %s

或者查看发布记录:

%s',
	);
					
	add_translation("zh", $chinese);
?>