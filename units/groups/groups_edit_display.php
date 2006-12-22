<?php

    // Displays editing instructions for individual groups
    
    $friends = run("friends:get",array($_SESSION['userid']));
    $memberlist = "";
    $i = 0;
    
    $body = <<< END

        <a name="{$parameter[0]->ident}"></a>
END;
    $yourFriends = __gettext("Your friends:"); // gettext variable
    $column1 = <<< END
            <table border="0">
                <tr>
                    <td width="30%" valign="top" align="left">
                        <form action="index.php#{$parameter[0]->ident}" method="post"><p>
                            $yourFriends<br />
                            <select name="friends[]" size="5" multiple="multiple">
END;
                            if (!empty($friends)) {
                                foreach($friends as $friend) {
                                    $ok = true;
                                    if (!empty($parameter[0]->members)) {
                                        foreach($parameter[0]->members as $member) {
                                            if ($member->user_id == $friend->user_id) {
                                                $ok = false;
                                            }
                                            if ($i == 0) $memberlist .= "
                                <option value=\"{$member->user_id}\">".stripslashes($member->name)."</option>
";
                                        }
                                        $i++;
                                    }
                                    if ($ok == true) {
                                        $column1 .= <<< END
                                <option value="{$friend->user_id}">{$friend->name}</option>
END;
                                    }
                                }
        
                            }
    $addToGroup = __gettext("Add selected to group"); // gettext variable
    $column1 .= <<< END
                            </select><br />
                            <input type="submit" value="$addToGroup" />
                            <input type="hidden" name="groupid" value="{$parameter[0]->ident}" />
                            <input type="hidden" name="action" value="group:addmember" />
                        </p></form>
                    </td>
END;
    $removeFromGroup = __gettext("Remove selected from group"); // gettext variable
    $groupMembers = __gettext("Members of this group:"); // gettext variable
    $column2 = <<< END
                    <td width="70%" valign="top" align="left">
                        <form action="index.php#{$parameter[0]->ident}" method="post"><p>
                            $groupMembers<br />
                            <select name="members[]" size="5" multiple="multiple">
{$memberlist}
                            </select><br />
                            <input type="submit" value="$removeFromGroup" />
                            <input type="hidden" name="groupid" value="{$parameter[0]->ident}" />
                            <input type="hidden" name="action" value="group:removemember" />
                        </p></form>
                    </td>
                </tr>
            </table>
END;
    $namevalue = htmlspecialchars($parameter[0]->name, ENT_COMPAT, 'utf-8');
    // $accessvalue = run("display:access_level_select",array("groupaccess",$parameter[0]->access));
    $saveGroup = __gettext("Save this group"); // gettext variable
    $deleteGroup = __gettext("Delete this group"); // gettext variable
    $name = <<< END
                        <form action="index.php" method="post" style="display:inline">
                            <input type="text"   name="groupname" value="{$namevalue}" />
                            <input type="hidden" name="action" value="group:edit" />
                            <input type="hidden" name="groupid" value="{$parameter[0]->ident}" />
                            <input type="submit" value="{$saveGroup}" />
                        </form>
                        <form action="index.php" method="post" style="display:inline">
                            <input type="hidden" name="action" value="group:delete" />
                            <input type="hidden" name="groupid" value="{$parameter[0]->ident}" />
                            <input type="submit" value="{$deleteGroup}" />
                        </form>
END;

    $body .= templates_draw(array(
                    'context' => 'databoxvertical',
                    'name' => $name,
                    'contents' => $column1 . $column2
                )
                );

    $run_result .= $body;
                
?>