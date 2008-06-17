<?php

function DoWordEntities ($string) {

// MS Word strangeness..
        $trans_tbl[chr(128)] = '&euro;';
        $trans_tbl[chr(130)] = '&sbquo;';
        $trans_tbl[chr(131)] = '&fnof;';
        $trans_tbl[chr(132)] = '&bdquo;';
        $trans_tbl[chr(133)] = '...';
        $trans_tbl[chr(134)] = '&dagger;';
        $trans_tbl[chr(135)] = '&Dagger;';
        $trans_tbl[chr(136)] = '&circ;';
        $trans_tbl[chr(137)] = '&permil;';
        $trans_tbl[chr(138)] = '&Scaron;';
        $trans_tbl[chr(139)] = '&lsaquo;';
        $trans_tbl[chr(140)] = '&OElig;';
        $trans_tbl[chr(142)] = '&Zcaron;';
        $trans_tbl[chr(145)] = '\'';
        $trans_tbl[chr(146)] = '\'';
        $trans_tbl[chr(147)] = '&quot;';
        $trans_tbl[chr(148)] = '&quot;';
        $trans_tbl[chr(149)] = '&bull;';
        $trans_tbl[chr(150)] = '-';
        $trans_tbl[chr(151)] = '&mdash;';
        $trans_tbl[chr(153)] = '&trade;';
        $trans_tbl[chr(154)] = '&scaron;';
        $trans_tbl[chr(155)] = '&rsaquo;';
        $trans_tbl[chr(132)] = '&bdquo;';
        $trans_tbl[chr(133)] = '...';
        $trans_tbl[chr(134)] = '&dagger;';
        $trans_tbl[chr(135)] = '&Dagger;';
        $trans_tbl[chr(136)] = '&circ;';
        $trans_tbl[chr(137)] = '&permil;';
        $trans_tbl[chr(138)] = '&Scaron;';
        $trans_tbl[chr(139)] = '&lsaquo;';
        $trans_tbl[chr(140)] = '&OElig;';
        $trans_tbl[chr(142)] = '&Zcaron;';
        $trans_tbl[chr(145)] = '\'';
        $trans_tbl[chr(146)] = '\'';
        $trans_tbl[chr(147)] = '&quot;';
        $trans_tbl[chr(148)] = '&quot;';
        $trans_tbl[chr(149)] = '&bull;';
        $trans_tbl[chr(150)] = '-';
        $trans_tbl[chr(151)] = '&mdash;';
        $trans_tbl[chr(153)] = '&trade;';
        $trans_tbl[chr(154)] = '&scaron;';
        $trans_tbl[chr(155)] = '&rsaquo;';
        $trans_tbl[chr(156)] = '&oelig;';
        $trans_tbl[chr(158)] = '&zcaron;';
        $trans_tbl[chr(159)] = '&Yuml;';

        return strtr ($string, $trans_tbl);
}


?>