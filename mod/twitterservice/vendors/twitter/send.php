<?php

require_once 'twitter.class.php';


$twitter = new Twitter('pokusnyucet2', '123456');
$status = $twitter->send('Mám se fajn II');

echo $status ? 'OK' : 'ERROR';
