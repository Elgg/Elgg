<?php

    // Processes text

    if (isset($parameter)) {
        if (is_array($parameter)) {
            $run_result .= nl2br(trim($parameter[0]));
            $addrelnofollow = (isset($parameter[1])) ? (bool) $parameter[1] : false;
        } else {
            $run_result .= nl2br(trim($parameter));
            $addrelnofollow = false;
        }

         $cachekey = sha1($addrelnofollow . "_" . $run_result);
         if($cached = elggcache_get("weblogs_text_process:weblogs", $cachekey)) {
             $run_result = $cached;
         } else {

            //check for mismatched <>s and force escaping if necessary
            $numlt = substr_count($run_result, "<");
            $numgt = substr_count($run_result, ">");
            if ($numlt != $numgt) {
                $run_result = htmlspecialchars($run_result, ENT_COMPAT, 'utf-8');
            }

            if(run("video:text:process",$run_result)!=null){
              $run_result = run("video:text:process",$run_result);
            }

            // URLs to links
            $run_result = run("weblogs:html_activate_urls", $run_result);

            // Remove the evil font tag
            $run_result = preg_replace("/<font[^>]*>/i","",$run_result);
            $run_result = preg_replace("/<\/font>/i","",$run_result);

            // add rel="nofollow" to any links
            if ($addrelnofollow) {
                $run_result = preg_replace('/<a\s+([^>]*)\s+rel=/i', '<a $1 ', $run_result);
                $run_result = preg_replace('/<a\s+/i', '<a rel="nofollow" ', $run_result);
            }

            // Text cutting
            // Commented out for the moment as it seems to disproportionately increase
            // memory usage / load

            /*
            global $individual;

            if (!isset($individual) || $individual != 1) {
                $run_result = preg_replace("/\{\{cut\}\}(.|\n)*(\{\{uncut\}\})?/","{{more}}",$run_result);
            } else {
                // $run_result = preg_replace("/\{\{cut\}\}/","",$run_result);
                $run_result = str_replace("{{cut}}","",$run_result);
                $run_result = str_replace("{{uncut}}","",$run_result);
            }
            */
             $setresult = elggcache_set("weblogs_text_process:weblogs", $cachekey, $run_result);
         }
    }

?>