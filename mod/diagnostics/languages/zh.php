<?php
	/**
	 * Elgg diagnostics language pack.
	 * 
	 * @package ElggDiagnostics
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
	
			'diagnostics' => '系统诊断',
	
			'diagnostics:description' => '下列诊断报告提供有用的信息，您可以附件错误来提交问题。',
	
			'diagnostics:download' => 'Download .txt',
	
	
			'diagnostics:header' => '========================================================================
Elgg 诊断报告
生成 %s 于 %s
========================================================================
			
',
			'diagnostics:report:basic' => '
Elgg 发布 %s, 版本 %s

------------------------------------------------------------------------',
			'diagnostics:report:php' => '
PHP 信息:
%s
------------------------------------------------------------------------',
			'diagnostics:report:plugins' => '
安装的插件信息:

%s
------------------------------------------------------------------------',
			'diagnostics:report:md5' => '
安装文件校验:

%s
------------------------------------------------------------------------',
			'diagnostics:report:globals' => '
全局变量:

%s
------------------------------------------------------------------------',
	
	);
					
	add_translation("zh",$chinese);
?>