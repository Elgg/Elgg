<?php

    // ELGG profile system initialisation
    
    // ID of profile to view / edit

        global $profile_id;
        global $page_owner;
        
        if ($profile_name = optional_param('profile_name')) {
            if ($profile_id = user_info_username('ident', $profile_name)) {
                $page_owner = $profile_id;
            }
        }
        
        if (empty($profile_id)) {
            $profile_id = optional_param("profile_id",optional_param("profileid",$_SESSION['userid'],PARAM_INT),PARAM_INT);
        }

        /*
        
        if (isset($_REQUEST['profile_name'])) {
            $profile_id = (int) user_info_username('ident', $_REQUEST['profile_name']);
        } else if (isset($_REQUEST['profile_id'])) {
            $profile_id = (int) $_REQUEST['profile_id'];
        } else if (isset($_POST['profileid'])) {
            $profile_id = (int) $_POST['profileid'];
        } else if (isset($_SESSION['userid'])) {
            $profile_id = (int) $_SESSION['userid'];
        } else {
            $profile_id = -1;
        }
        */

        
        if (!isset($page_owner) || optional_param("profile_id",-9000,PARAM_INT) != -9000) {
            $page_owner = $profile_id;
        }
        
        if (!defined('profileinit')) {
            define('profileinit', true);
        }

?>