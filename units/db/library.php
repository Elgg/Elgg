<?php

    // Database library functions

    // Generalised function to query the database
    // (returns an array of result rows)
        function db_query($sql_query) {
     error_log('DEBUG: db_query called with '.$sql_query); // TODO remove this later after we convert everything to datalib.
            global $querynum;
            global $querycache;
            global $db;
            global $db_connection;

            /*if (isset($querycache[$sql_query])) {
                return $querycache[$sql_query];
            }*/
            
            if (!isset($querynum)) {
                $querynum = 1;
            } else {
                $querynum++;
            }
            global $run_context;
            // echo "<b>" . $run_context . "</b>&nbsp;&nbsp;" . $sql_query . "<br />";
            if ($sql_query != "") {
                // echo "<!-- $sql_query -->\n";
                if ($result = $db->Execute($sql_query)) {
                    if ( $result->RecordCount() > 0 ) {
                        if ($records = $result->GetAssoc(true)) {
                            foreach ($records as $key => $record) {
                                $data[] = (object)$record;
                            }
                            return $data;
                        } else {
                            while ($record = $result->fetchrow()) {
                                $data[] = (object)$record;
                            }
                        @mysql_free_result($result);
                        }
                        return $data;
                    }
                    return true;
                } else {
                    if (ELGG_DEBUG) {
                        echo $sql_query . " :: " . @mysql_error($db_connection) . "<br />\n";
                    }
                    $querycache[$sql_query] = FALSE;
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        }
    
    // Rows affected by the last MySQL transaction
        function db_affected_rows() {
            error_log('DEBUG: db_affected_rows called'); //TODO remove this later after we convert everything to datalib.
             global $db_connection;
            return @mysql_affected_rows($db_connection);
        }
        
    // Returns the ID of the last MySQL transaction
        function db_id() {
            error_log('DEBUG: db_id called'); //TODO remove this later after we convert everything to datalib.
             global $db_connection;
            return @mysql_insert_id($db_connection);
        }

?>