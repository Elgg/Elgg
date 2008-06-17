<?php

    if (isset($parameter) && $parameter[0] == "onceonly") {
        
        $tag = urlencode($parameter[1]);
        $run_result .= "\t\t<rss>\n";
        $run_result .= "\t\t\t<link>".url."search/rss.php?tag=$tag</link>\n";
        $run_result .= "\t\t</rss>\n";
        
    }
?>