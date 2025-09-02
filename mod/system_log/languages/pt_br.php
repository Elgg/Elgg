<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:administer_utilities:logbrowser' => 'Navegador de Logs',
	'logbrowser:search' => 'Refinar Resultados',
	'logbrowser:user' => 'Nome de Usuário para buscar',
	'logbrowser:starttime' => 'Horário de Início (exemplo: "última segunda", "1 hora atrás")',
	'logbrowser:endtime' => 'Horário de Término',

	'logbrowser:explore' => 'Explorar Log',

	'logbrowser:date' => 'Data e Hora',
	'logbrowser:ip_address' => 'Endereço IP',
	'logbrowser:user:name' => 'Usuário',
	'logbrowser:user:guid' => 'GUID do Usuário',
	'logbrowser:object' => 'Tipo de Objeto',
	'logbrowser:object:id' => 'ID do Objeto',
	'logbrowser:action' => 'Ação',

	'logrotate:period' => 'Com que frequência o Log do Sistema deve ser arquivado?',
	'logrotate:retention' => 'Excluir Logs arquivados com mais de x dias',
	'logrotate:retention:help' => 'Número de dias que deseja manter os Logs arquivados no Banco de Dados. Deixe em branco para não excluir os Logs arquivados.',

	'logrotate:logrotated' => "Log arquivado com sucesso!",
	'logrotate:lognotrotated' => "Erro ao arquivar o Log...",

	'logrotate:logdeleted' => "Log excluído.",
	'logrotate:lognotdeleted' => "Nenhum Log excluído",
);
