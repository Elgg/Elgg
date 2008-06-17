<?php
global $CFG;
// $parameter = the ID number of the feed

// Convert $parameter to an integer, see if it exists
$parameter = (int) $parameter;
        
// Check database, get feed items
$feed = get_record('feeds','ident',$parameter);
$subscribers = count_records('feed_subscriptions','feed_id',$parameter);

if (!empty($feed) && !empty($subscribers)) {

    if ($subscribers > 10) {
        $update_time = $CFG->mintimebetweenrssupdate;
    } else if ($subscribers > 5) {
        $update_time = $CFG->mintimebetweenrssupdate * 2;
    } else if ($subscribers > 1) {
        $update_time = $CFG->mintimebetweenrssupdate * 4;
    } else {
        $update_time = $CFG->mintimebetweenrssupdate * 8;
    }
    
    $timenow = time();
    
    if ($feed->last_updated < ($timenow - $update_time)) {
        set_field('feeds','last_updated',$timenow,'ident',$parameter);
        if ($rss = run("rss:get", $feed->url)) {
            
            $feedtitle = (isset($rss->channel['title'])) ? trim(stripslashes($rss->channel['title'])) : '';
            $feedtagline = (isset($rss->channel['tagline'])) ? trim(stripslashes($rss->channel['tagline'])) : '';
            if (strlen($feedtagline) > 120) {
                $feedtagline = "";
            }
            $feedurl = (isset($rss->channel['link'])) ? trim(stripslashes($rss->channel['link'])) : '';
            $f = new StdClass;
            $f->siteurl = $feedurl;
            $f->name = $feedtitle;
            $f->tagline = $feedtagline;
            $f->ident = $parameter;
            update_record('feeds',$f);
            $feeditems = array();
            if ($feeditemstemp = get_records('feed_posts','feed',$parameter, '', 'ident,url')) {
                foreach($feeditemstemp as $feeditem) {
                    $feeditems[$feeditem->ident] = stripslashes($feeditem->url);
                }
            }
            unset($feeditemstemp);
            
            if (sizeof($rss->items > 0)) {
                
                $mintime = $timenow - ($CFG->rsspostsmaxage * 86400);
                
                foreach($rss->items as $item) {
                    $title = '';
                    if (isset($item['title'])) {
                        $title = trim(stripslashes($item['title']));
                    }
                    
                    $description = '';
                    if (isset($item['atom_content']) && isset($item['description'])) {
                        // assumption that longer is better :>
                        if (strlen($item['atom_content']) >= strlen($item['description'])) {
                            $description = trim(stripslashes($item['atom_content']));
                        } else {
                            $description = trim(stripslashes($item['description']));
                        }
                    } elseif (isset($item['atom_content'])) {
                        $description = trim(stripslashes($item['atom_content']));
                    } elseif (isset($item['description'])) {
                        $description = trim(stripslashes($item['description']));
                    }
                    
                    $posted = '';
                    if (isset($item['dc']['date'])) {
                        $posted = stripslashes($item['dc']['date']);
                    } elseif (isset($item['issued'])) {
                        $posted = stripslashes($item['issued']);
                    } elseif (isset($item['pubdate'])) {
                        $posted = stripslashes($item['pubdate']);
                    }
                    $posted = str_replace("T"," ",$posted);
                    $posted = str_replace("Z"," ",$posted);
                    $posted = str_replace("GM"," ",$posted);
                    $posted = str_replace("ES"," ",$posted);
                    $posted = str_replace("PS"," ",$posted);
                    $posted = str_replace("ue","Tue",$posted);
                    $posted = str_replace("hu","Thu",$posted);
                    $posted = preg_replace('/(\d\d\d\d)\-(\d\d)\-(\d\d)/','$1/$2/$3',$posted);
                    $posted = trim(preg_replace('/(\-.*)/','',$posted));
                    
                    $url = '';
                    if (isset($item['link'])) {
                        $url = str_replace(" ","%20",trim(stripslashes($item['link'])));
                        $url = substr($url, 0, 255); // trim urls down to the max length in the db, just in case. CURSE YOU, GUARDIAN BLOGGERS!
                    }
                    
                    if (!empty($item['date_timestamp'])) {
                        $added = (int) $item['date_timestamp'];
                    }
                    if (empty($added) && ($posted == "" || !($added = @strtotime($posted))) ) {
                        $added = $timenow;
                    }
                    if ($added > $timenow || $added == -1) {
                        $added = $timenow;
                    }
                    
                    if (!$CFG->rsspostsmaxage || $added > $mintime) {
                        //don't update/insert feed posts older than the pruning age
                        
                        if (in_array($url,$feeditems)) {
                            // update_record is not going to work here, we don't have a primary key that I can see (Penny)
                            $fp = new StdClass;
                            $fp->ident = array_search($url,$feeditems);
                            $fp->title = $title;
                            $fp->body =  $description;
                            $fp->posted = $posted;
                            $fp->url = $url;
                            $fp->feed = $parameter;
                            update_record('feed_posts',$fp);
                        } else {
                            $fp = new StdClass;
                            $fp->title = $title;
                            $fp->body = $description;
                            $fp->posted = $posted;
                            $fp->url = $url;
                            $fp->feed = $parameter;
                            $fp->added = $added;
                            insert_record('feed_posts',$fp);
                            if ($weblogs = get_records_select('feed_subscriptions','feed_id = ? AND autopost = ?',array($parameter,'yes'))) {
                                $description = clean_text($description);
                                $body = "<p><span class=\"blog_post_source\">$url</span></p> " . $description;
                                foreach($weblogs as $weblog) {
                                    $wp = new StdClass;
									$wp = plugin_hook("weblog_post","create",$wp);
                                    $wp->title = $title;
                                    $wp->body = $body;
                                    $wp->access = $CFG->default_access;
                                    $wp->owner = $weblog->user_id;
                                    $wp->weblog = $weblog->user_id;
                                    $wp->posted = $added;
                                    $id = insert_record('weblog_posts',$wp);
									$wp = plugin_hook("weblog_post","publish",$wp);
                                    $tags = trim($weblog->autopost_tag);
                                    insert_tags_from_string ($tags, 'weblog', $id, $CFG->default_access, $weblog->user_id);
                                    plugin_hook('weblog_post', 'publish', $wp);
                                    $rssresult = run("weblogs:rss:publish", array($weblog->user_id, false));
                                    $rssresult = run("profile:rss:publish", array($weblog->user_id, false));
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

?>