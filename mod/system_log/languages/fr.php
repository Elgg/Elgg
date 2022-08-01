<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:administer_utilities:logbrowser' => 'Visualiseur de journal',
	'logbrowser:search' => 'Affiner les résultats',
	'logbrowser:user' => 'Identifiant à rechercher',
	'logbrowser:starttime' => 'Heure de début (en anglais, par exemple "last monday", "1 hour ago")',
	'logbrowser:endtime' => 'Heure de fin',

	'logbrowser:explore' => 'Explorer le journal',

	'logbrowser:date' => 'Date et heure',
	'logbrowser:ip_address' => 'Adresse IP',
	'logbrowser:user:name' => 'Utilisateur',
	'logbrowser:user:guid' => 'GUID de l\'utilisateur',
	'logbrowser:object' => 'Type d\'objet',
	'logbrowser:object:id' => 'ID de l\'objet',
	'logbrowser:action' => 'Action',

	'logrotate:period' => 'A quelle fréquence les journaux système devraient-ils être archivés ?',
	'logrotate:retention' => 'Supprimer les journaux archivés plus anciens que x jours',
	'logrotate:retention:help' => 'Le nombre de jours pendant lesquels vous souhaitez conserver les journaux archivés dans la base de données. Laissez vide afin de ne pas nettoyer les journaux archivés.',

	'logrotate:logrotated' => "Rotation du journal effectuée",
	'logrotate:lognotrotated' => "Erreur lors de la rotation du journal",

	'logrotate:logdeleted' => "Journal supprimé",
	'logrotate:lognotdeleted' => "Aucun journal supprimé",

	// not used any more since Elgg 4.1, can be cleaned in Elgg 5.0
	'logrotate:delete' => 'Supprimer les journaux archivés plus anciens que ',
	'logrotate:week' => 'semaine',
	'logrotate:month' => 'mois',
	'logrotate:year' => 'année',
);
