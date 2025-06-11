<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	'item:object:file' => 'Arquivos',
	'item:object:file:application' => 'Aplicativo',
	'item:object:file:archive' => 'Arquivo',
	'item:object:file:excel' => 'Excel',
	'item:object:file:image' => 'Imagem',
	'item:object:file:music' => 'Música',
	'item:object:file:openoffice' => 'OpenOffice',
	'item:object:file:pdf' => 'PDF',
	'item:object:file:ppt' => 'PowerPoint',
	'item:object:file:text' => 'Texto',
	'item:object:file:vcard' => 'vCard',
	'item:object:file:video' => 'Vídeo',
	'item:object:file:word' => 'Word',
	
	'file:upgrade:2022092801:title' => 'Mover Arquivos',
	'file:upgrade:2022092801:description' => 'Move os arquivos enviados usando o plugin de arquivos para a pasta da entidade do arquivo, em vez da pasta da entidade do proprietário.',
	
	'collection:object:file' => 'Arquivos',
	'collection:object:file:all' => "Todos os Arquivos do Site",
	'collection:object:file:owner' => "Arquivos de %s",
	'collection:object:file:friends' => "Arquivos dos Amigos",
	'collection:object:file:group' => "Arquivos do Grupo",
	'add:object:file' => "Enviar um Arquivo",
	'edit:object:file' => "Editar Arquivo",
	'notification:object:file:create' => "Enviar Notificação quando um Arquivo for criado",
	'notifications:mute:object:file' => "sobre o Arquivo '%s'",
	
	'entity:edit:object:file:success' => 'O arquivo foi salvo com sucesso!',
	
	'file:more' => "Mais Arquivos",
	'file:list' => "Modo Lista",

	'file:num_files' => "Número de Arquivos para Exibir",
	'file:replace' => 'Substituir o conteúdo do Arquivo (deixe em branco para não alterar o Arquivo)',
	'file:list:title' => "%s's %s %s",

	'file:file' => "Arquivo",

	'file:list:list' => 'Mudar para a Visualização em Lista',
	'file:list:gallery' => 'Mudar para a Visualização em Galeria',

	'file:type:' => 'Arquivos',
	'file:type:all' => "Todos os Arquivos",
	'file:type:video' => "Vídeos",
	'file:type:document' => "Documentos",
	'file:type:audio' => "Áudio",
	'file:type:image' => "Imagens",
	'file:type:general' => "Geral",

	'file:user:type:video' => "Vídeos de %s",
	'file:user:type:document' => "Documentos de %s",
	'file:user:type:audio' => "Áudios de %s",
	'file:user:type:image' => "Imagens de %s",
	'file:user:type:general' => "Arquivos Gerais de %s",

	'file:friends:type:video' => "Vídeos dos seus Amigos",
	'file:friends:type:document' => "Documentos dos seus Amigos",
	'file:friends:type:audio' => "Áudios dos seus Amigos",
	'file:friends:type:image' => "Imagens dos seus Amigos",
	'file:friends:type:general' => "Arquivos Gerais dos seus Amigos",

	'widgets:filerepo:name' => "Widget dos Arquivos",
	'widgets:filerepo:description' => "Mostre os seus Arquivos mais recentes",

	'groups:tool:file' => 'Ativar arquivos do grupo',
	'groups:tool:file:description' => 'Permitir que membros do grupo compartilhem arquivos neste grupo.',

	'river:object:file:create' => '%s enviou o Arquivo %s',
	'river:object:file:comment' => '%s comentou no Arquivo %s',

	'file:notify:summary' => 'Novo Arquivo chamado %s',
	'file:notify:subject' => 'Novo Arquivo: %s',
	'file:notify:body' => '%s enviou um novo Arquivo: %s,

%s

Visualize e comente no Arquivo:
%s',
	
	'notification:mentions:object:file:subject' => '%s mencionou você em um Arquivo',

	/**
	 * Status messages
	 */

	'file:saved' => "O Arquivo foi salvo com sucesso!",
	'entity:delete:object:file:success' => "O Arquivo foi excluído com sucesso!",

	/**
	 * Error messages
	 */

	'file:none' => "Nenhum Arquivo.",
	'file:uploadfailed' => "Desculpe; não foi possível salvar o seu Arquivo.",
	'file:noaccess' => "Você não tem permissão para alterar este Arquivo",
	'file:cannotload' => "Ocorreu um erro ao enviar o Arquivo",
);
