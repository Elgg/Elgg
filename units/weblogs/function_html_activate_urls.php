<?php
    // Function for URL autodiscovery

    $str = trim($parameter);
    
    $search = array();
    $replace = array();

    // lift all links, images and image maps
    $url_tags = array (
                      "'<a[^>]*>.*?</a>'si",
                      "'<map[^>]*>.*?</map>'si",
                      "'<script[^>]*>.*?</script>'si",
                      "'<style[^>]*>.*?</style>'si",
                      "'<[^>]+>'si"
                      );
    
    foreach($url_tags as $url_tag)
    {
        preg_match_all($url_tag, $str, $matches, PREG_SET_ORDER);
        foreach($matches as $match)
        {
            $key = "<" . md5($match[0]) . ">";
            $search[] = $key;
            $replace[] = $match[0];
        }
    }
    
    $str = str_replace($replace, $search, $str);
    
    // indicate where urls end if they have these trailing special chars
    $sentinals = array("/&(quot|#34);/i",    // Replace html entities
                        "/&(lt|#60);/i",
                        "/&(gt|#62);/i",
                        "/&(nbsp|#160);/i",
                        "/&(iexcl|#161);/i",
                        "/&(cent|#162);/i",
                        "/&(pound|#163);/i",
                        "/&(copy|#169);/i");
    
    $str = preg_replace($sentinals, "<marker>\\0", $str);
    
    // URL into links
    $str =
        preg_replace( "|(\w{3,10}://[\w\.\-_]+(:\d+)?[^\s\"\'<>\(\)\{\}]{0,40})[^\s\"\'<>\(\)\{\}]*|",
        '<a href="$0">$1</a>', $str );
    
    $str = str_replace("<marker>", '', $str);
    $run_result = str_replace($search, $replace, $str);
    
?>