Controlador de pàgina
=====================

Elgg facilita la gestió de les pàgines dels complements mitjançant un controlador de pàgina, habilitant enllaços personalitzats com ``http://elteulloc/el_teu_complement/apartat``. Per afegir un controlador de pàgina en un complement, cal registrar la funció del controlador ``elgg_register_page_handler()`` a l'arxiu ``start.php`` del complement:

.. code:: php
   
   elgg_register_page_handler('el_teu_complement', 'controlador_de_pàgina_del_complement');
   
El controlador de pàgina del complement passa dos paràmetres: 

- una matriu que conté les seccions de la URL ha detonat al pas '/'. Amb aquesta informació el controlador serà capaç d'aplicar qualsevol lògica necessària, per exemple, carregar la vista apropiada i retornar el seu contingut.
- el controlador, aquest és el controlador que s'utilitza actualment (en l'exemple ``el_teu_complement``). Si no es registren diversos controladors de pàgina per a la mateixa funció, mai necessitaràs això.

Flux de codi
---------

Les pàgines en complements s'han d'utilitzar tan sols a través dels controladors de la pàgina, desades al directori ``pages/`` del complement i no són necessàries als arxius ``include`` o ``require`` o ``engine/start.php``. El propòsit d'aquests arxius és generar junts la sortida des de diferents punts de vista per formar la pàgina que l'usuari veu. El flux del programa és alguna cosa com això:

1. Un usuari/a sol·licita ``/nom_complement/apartat/entitat``
2. Elgg verifica si ``nom_complement`` està registrat en un controlador de pàgina i crida la funció, passa ``array('apartat', 'entitat')`` com a primer argument
3. La funció del controlador de pàgina determina quina pàgina s'ha de mostrar, estableix opcionalment alguns valors, i després inclou la pàgina correcta sota ``nom_complement/pages/nom_complement/``
4. L'arxiu inclòs combina molts punts de vista diferents, crida format de funcions com ``elgg_view_layout()`` i ``elgg_view_page()``, i després mostra la sortida final
5. L'usuari veu una pàgina totalment acabada

No hi ha sintaxi forçada a les adreces URL, però els estàndards de codificació d'Elgg suggereix un format determinat.
