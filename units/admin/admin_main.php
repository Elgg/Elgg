<?php
global $USER,$CFG;
// Main admin panel screen
    
// Site stats

if (logged_on && user_flag_get("admin", $USER->ident)) {
    
        $run_result .= "<h2>" . __gettext("Site statistics") . "</h2>";
    
    // Number of users of each type
    if ($users = count_users() && !empty($users) && is_array($users)) {
        foreach($users as $user) {
            
            $run_result .= templates_draw(array(
                                                'context' => 'adminTable',
                                                'name' => "<h3>" . sprintf(__gettext("Accounts of type '%s'"), $user->user_type) . "</h3> ",
                                                'column1' => "<p>" . $user->numusers . "</p> ",
                                                'column2' => "&nbsp;"
                                                )
                                          );
            
        }
    }
    
    // Number of weblog posts
    $weblog_posts = count_records('weblog_posts');
    $weblog_comments = count_records('weblog_comments');
    $weblog_posts_7days = count_records_select('weblog_posts',"posted > ?",array(time() - (86400 * 7)));
    $weblog_comments_7days = count_records_select('weblog_comments',"posted > ?",array(time() - (86400 * 7)));
    $run_result .= templates_draw(array(
                                        'context' => 'adminTable',
                                        'name' => "<h3>" . __gettext("Weblog statistics") . "</h3> ",
                                        'column1' => "<h4>" . __gettext("All-time:") . "</h4><p>" 
                                        . sprintf(__gettext("%u weblog posts, %u comments"),$weblog_posts, $weblog_comments) 
                                        . "</p><h4>" . __gettext("Last 7 days:") . "</h4><p>" 
                                        . sprintf(__gettext("%u weblog posts, %u comments"),$weblog_posts_7days, $weblog_comments_7days) . "</p>",
                                        'column2' => "&nbsp;"
                                        )
                                  );
    
    // Number of files
    $files = get_record_sql('SELECT COUNT(ident) AS numfiles,SUM(size) AS totalsize FROM '.$CFG->prefix.'files');
    $files_7days = get_record_sql('SELECT COUNT(ident) AS numfiles, SUM(size) AS totalsize FROM '.$CFG->prefix.'files WHERE time_uploaded > ?',array(time() - (86400 * 7)));
    $run_result .= templates_draw(array(
                                        'context' => 'adminTable',
                                        'name' => "<h3>" . __gettext("File statistics") . "</h3> ",
                                        'column1' => "<h4>" . __gettext("All-time:") . "</h4> <p>" . sprintf(__gettext("%u files (%s bytes)"),$files->numfiles, $files->totalsize) 
                                        . "</p><h4>" . __gettext("Last 7 days:") . "</h4><p>" . sprintf(__gettext("%u files (%s bytes)"),$files_7days->numfiles, $files_7days->totalsize) . "</p>",
                                        'column2' => "&nbsp;"
                                        )
                                  );
    
        // DB size
        $totaldbsize = 0;
        if ($CFG->dbtype == 'mysql') {
            if ($dbsize = get_records_sql('SHOW TABLE STATUS')) {
                foreach($dbsize as $atable) {
                    // filter on prefix if we have it.
                    if (!empty($CFG->prefix) && strpos($atable->Name,$CFG->prefix) !== 0) {
                        continue;
                    }
                    $totaldbsize += intval($atable->Data_length) + intval($atable->Index_length);
                }
                $run_result .= templates_draw(array(
                                                    'context' => 'adminTable',
                                                    'name' => "<h3>" . __gettext("Database statistics") . "</h3> ",
                                                    'column1' => "<h4>" . __gettext("Total database size:") . "</h4> <p>" . sprintf(__gettext("%u bytes"),$totaldbsize) . "</p>",
                                                    'column2' => "&nbsp;"
                                                    )
                                              );
            }
        }
    // Users online right now
    $run_result .= "<h2>" . __gettext("Users online now") . "</h2>";
    $run_result .= "<p>" . __gettext("The following users have an active session and have performed an action within the past 10 minutes.") . "</p>";
    
    if ($users = get_records_select('users',"code != ? AND last_action > ?",array('',time() - 600),'username ASC')) {
        $run_result .= templates_draw(array(
                                            'context' => 'adminTable',
                                            'name' => "<h3>" . __gettext("Username") . "</h3>",
                                            'column1' => "<h3>" . __gettext("Full name") . "</h3>",
                                            'column2' => "<h3>" . __gettext("Email address") . "</h3>"
                                            )
                                      );
        foreach($users as $user) {
            $run_result .= run("admin:users:panel",$user);
        }
    } else {
        $users = array();
    }
    
    $run_result .= "<p>" . sprintf(__gettext("%u users in total."),sizeof($users)) . "</p>";
    
}

?>