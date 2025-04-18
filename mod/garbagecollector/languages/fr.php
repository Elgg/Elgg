<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'garbagecollector:period' => 'A quelle fréquence souhaitez-vous faire tourner le ramasse-miettes ?',
	'garbagecollector:period:optimize' => 'Optimiser les tables de la base de données après l\'exécution du ramasse-miettes',

	'garbagecollector:weekly' => 'Une fois par semaine',
	'garbagecollector:monthly' => 'Une fois par mois',
	'garbagecollector:yearly' => 'Une fois par an',

	'garbagecollector' => 'RAMASSE-MIETTES',
	'garbagecollector:start' => "Ramasse-miettes initialisé",
	'garbagecollector:done' => "Ramasse-miettes terminé",
	'garbagecollector:optimize' => "Optimisation en cours %s",
	
	'garbagecollector:orphaned' => "Nettoyer les données orphelines de la table \"%s\"",
	'garbagecollector:orphaned:done' => "Nettoyage des données orphelines terminé",
	
	'garbagecollector:cli:database:optimize:description' => "Optimiser les tables de la base de données",
);
