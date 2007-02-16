<?php 

/**
 * Library of functions for database manipulation.
 * This library is most of lib/datalib.php from moodle
 * http://moodle.org || http://sourceforge.net/projects/moodle
 * Copyright (C) 2001-2003  Martin Dougiamas  http://dougiamas.com 
 * @author Martin Dougiamas and many others
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */


/// FUNCTIONS FOR DATABASE HANDLING  ////////////////////////////////

/**
 * Execute a given sql command string
 *
 * Completely general function - it just runs some SQL and reports success.
 *
 * @uses $db
 * @param string $command The sql string you wish to be executed.
 * @param bool $feedback Set this argument to true if the results generated should be printed. Default is true.
 * @return string
 */
function execute_sql($command, $feedback=true) {
/// Completely general function - it just runs some SQL and reports success.

    global $db, $CFG;

    $olddebug = $db->debug;

    if (!$feedback) {
        $db->debug = false;
    }
    
    if (defined('ELGG_PERFDB')) { global $PERF ; $PERF->dbqueries++; };

    $result = $db->Execute($command);

    $db->debug = $olddebug;

    if ($result) {
        // elggcache_purge(); // TODO - should probably be here, given function can do anything, but very inefficient
        if ($feedback) {
            notify(__gettext('Success'), 'notifysuccess');
        }
        return true;
    } else {
        if ($feedback) {
            echo '<p><span class="error">'. __gettext('Error') .'</span></p>';
        }
        if (!empty($CFG->dblogerror)) {
            $debug = debug_backtrace();
            foreach ($debug as $d) {
                if (strpos($d['file'],'datalib') === false) {
                    error_log("SQL ".$db->ErrorMsg()." in {$d['file']} on line {$d['line']}. STATEMENT:  $command");
                    break;
                }
            }
        }
        return false;
    }
}
/**
* on DBs that support it, switch to transaction mode and begin a transaction
* you'll need to ensure you call commit_sql() or your changes *will* be lost
* this is _very_ useful for massive updates
*/
function begin_sql() {
/// Completely general function - it just runs some SQL and reports success.

    global $CFG;
    if ($CFG->dbtype === 'postgres7') {
        return execute_sql('BEGIN', false);
    }
    return true;
}
/**
* on DBs that support it, commit the transaction 
*/
function rollback_sql() {
/// Completely general function - it just runs some SQL and reports success.

    global $CFG;
    if ($CFG->dbtype === 'postgres7') {
        return execute_sql('ROLLBACK', false);
    }
    return true;
}



/**
 * returns db specific uppercase function
 */
function db_uppercase() {
    global $CFG;
    switch (strtolower($CFG->dbtype)) {

    case "postgres7":
        return "upper";

    case "mysql":
    default:
        return "ucase";

    }
}

/**
 * returns db specific lowercase function
 */
function db_lowercase() {
    global $CFG;
    switch (strtolower($CFG->dbtype)) {

    case "postgres7":
        return "lower";

    case "mysql":
    default:
        return "lcase";

    }
}

/**
* on DBs that support it, commit the transaction 
*/
function commit_sql() {
/// Completely general function - it just runs some SQL and reports success.

    global $CFG;
    if ($CFG->dbtype === 'postgres7') {
        return execute_sql('COMMIT', false);
    }
    return true;
}

/**
 * Run an arbitrary sequence of semicolon-delimited SQL commands
 *
 * Assumes that the input text (file or string) consists of
 * a number of SQL statements ENDING WITH SEMICOLONS.  The
 * semicolons MUST be the last character in a line.
 * Lines that are blank or that start with "#" or "--" (postgres) are ignored.
 * Only tested with mysql dump files (mysqldump -p -d moodle)
 *
 * @uses $CFG
 * @param string $sqlfile The path where a file with sql commands can be found on the server.
 * @param string $sqlstring If no path is supplied then a string with semicolon delimited sql 
 * commands can be supplied in this argument.
 * @return bool Returns true if databse was modified successfully.
 */
function modify_database($sqlfile='', $sqlstring='') {

    global $CFG, $METATABLES, $db;

    $success = true;  // Let's be optimistic

    if (!empty($sqlfile)) {
        if (!is_readable($sqlfile)) {
            $success = false;
            echo '<p>Tried to modify database, but "'. $sqlfile .'" doesn\'t exist!</p>';
            return $success;
        } else {
            $lines = file($sqlfile);
        }
    } else {
        $sqlstring = trim($sqlstring);
        if ($sqlstring{strlen($sqlstring)-1} != ";") {
            $sqlstring .= ";"; // add it in if it's not there.
        }
        $lines[] = $sqlstring;
    }

    $command = '';

    foreach ($lines as $line) {
        $line = rtrim($line);
        $length = strlen($line);

        if ($length and $line[0] <> '#' and $line[0].$line[1] <> '--') {
            if (substr($line, $length-1, 1) == ';') {
                $line = substr($line, 0, $length-1);   // strip ;
                $command .= $line;
                $command = str_replace('prefix_', $CFG->prefix, $command); // Table prefixes
                if (! execute_sql($command)) {
                    $success = false;
                }
                $command = '';
            } else {
                $command .= $line;
            }
        }
    }

    $METATABLES = $db->Metatables();
    elggcache_purge();
    
    return $success;

}

/// FUNCTIONS TO MODIFY TABLES ////////////////////////////////////////////

/**
 * Add a new field to a table, or modify an existing one (if oldfield is defined).
 *
 * @uses $CFG
 * @uses $db
 * @param string $table ?
 * @param string $oldfield ?
 * @param string $field ?
 * @param string $type ?
 * @param string $size ?
 * @param string $signed ?
 * @param string $default ?
 * @param string $null ?
 * @todo Finish documenting this function
 */

function table_column($table, $oldfield, $field, $type='integer', $size='10',
                      $signed='unsigned', $default='0', $null='not null', $after='') {
    global $CFG, $db;
    
    elggcache_cachepurgetype($table);

    if (empty($oldfield) && !empty($field)) { //adding
        // check it doesn't exist first.
        if ($columns = $db->MetaColumns($CFG->prefix . $table)) {
            foreach ($columns as $c) {
                if ($c->name == $field) {
                    $oldfield = $field;
                }
            }
        }
    }

    switch (strtolower($CFG->dbtype)) {

        case 'mysql':
        case 'mysqlt':

            switch (strtolower($type)) {
                case 'text':
                    $type = 'TEXT';
                    $signed = '';
                    break;
                case 'integer':
                    $type = 'INTEGER('. $size .')';
                    break;
                case 'varchar':
                    $type = 'VARCHAR('. $size .')';
                    $signed = '';
                    break;
                case 'char':
                    $type = 'CHAR('. $size .')';
                    $signed = '';
                    break;
            }

            if (!empty($oldfield)) {
                $operation = 'CHANGE '. $oldfield .' '. $field;
            } else {
                $operation = 'ADD '. $field;
            }

            $default = 'DEFAULT \''. $default .'\'';

            if (!empty($after)) {
                $after = 'AFTER `'. $after .'`';
            }

            return execute_sql('ALTER TABLE '. $CFG->prefix . $table .' '. $operation .' '. $type .' '. $signed .' '. $default .' '. $null .' '. $after);

        case 'postgres7':        // From Petri Asikainen
            //Check db-version
            $dbinfo = $db->ServerInfo();
            $dbver = substr($dbinfo['version'],0,3);

            //to prevent conflicts with reserved words
            $realfield = '"'. $field .'"';
            $field = '"'. $field .'_alter_column_tmp"';
            $oldfield = '"'. $oldfield .'"';

            switch (strtolower($type)) {
                case 'tinyint':
                case 'integer':
                    if ($size <= 4) {
                        $type = 'INT2';
                    }
                    if ($size <= 10) {
                        $type = 'INT';
                    }
                    if  ($size > 10) {
                        $type = 'INT8';
                    }
                    break;
                case 'varchar':
                    $type = 'VARCHAR('. $size .')';
                    break;
                case 'char':
                    $type = 'CHAR('. $size .')';
                    $signed = '';
                    break;
            }

            $default = '\''. $default .'\'';

            //After is not implemented in postgesql
            //if (!empty($after)) {
            //    $after = "AFTER '$after'";
            //}

            //Use transactions
            execute_sql('BEGIN');

            //Always use temporary column
            execute_sql('ALTER TABLE '. $CFG->prefix . $table .' ADD COLUMN '. $field .' '. $type);
            //Add default values
            execute_sql('UPDATE '. $CFG->prefix . $table .' SET '. $field .'='. $default);


            if ($dbver >= '7.3') {
                // modifying 'not null' is posible before 7.3
                //update default values to table
                if (strtoupper($null) == 'NOT NULL') {
                    execute_sql('UPDATE '. $CFG->prefix . $table .' SET '. $field .'='. $default .' WHERE '. $field .' IS NULL');
                    execute_sql('ALTER TABLE '. $CFG->prefix . $table .' ALTER COLUMN '. $field .' SET '. $null);
                } else {
                    execute_sql('ALTER TABLE '. $CFG->prefix . $table .' ALTER COLUMN '. $field .' DROP NOT NULL');
                }
            }

            execute_sql('ALTER TABLE '. $CFG->prefix . $table .' ALTER COLUMN '. $field .' SET DEFAULT '. $default);

            if ( $oldfield != '""' ) {

                // We are changing the type of a column. This may require doing some casts...
                $casting = '';
                $oldtype = column_type($table, $oldfield);
                $newtype = column_type($table, $field);

                // Do we need a cast?
                if($newtype == 'N' && $oldtype == 'C') {
                    $casting = 'CAST(CAST('.$oldfield.' AS TEXT) AS REAL)';
                }
                else if($newtype == 'I' && $oldtype == 'C') {
                    $casting = 'CAST(CAST('.$oldfield.' AS TEXT) AS INTEGER)';
                }
                else {
                    $casting = $oldfield;
                }

                // Run the update query, casting as necessary
                execute_sql('UPDATE '. $CFG->prefix . $table .' SET '. $field .' = '. $casting);
                execute_sql('ALTER TABLE  '. $CFG->prefix . $table .' DROP COLUMN '. $oldfield);
            }

            execute_sql('ALTER TABLE '. $CFG->prefix . $table .' RENAME COLUMN '. $field .' TO '. $realfield);

            return execute_sql('COMMIT');

        default:
            switch (strtolower($type)) {
                case 'integer':
                    $type = 'INTEGER';
                    break;
                case 'varchar':
                    $type = 'VARCHAR';
                    break;
            }

            $default = 'DEFAULT \''. $default .'\'';

            if (!empty($after)) {
                $after = 'AFTER '. $after;
            }

            if (!empty($oldfield)) {
                execute_sql('ALTER TABLE '. $CFG->prefix . $table .' RENAME COLUMN '. $oldfield .' '. $field);
            } else {
                execute_sql('ALTER TABLE '. $CFG->prefix . $table .' ADD COLUMN '. $field .' '. $type);
            }

            execute_sql('ALTER TABLE '. $CFG->prefix . $table .' ALTER COLUMN '. $field .' SET '. $null);
            return execute_sql('ALTER TABLE '. $CFG->prefix . $table .' ALTER COLUMN '. $field .' SET '. $default);
    }
}

/**
 * Get the data type of a table column, using an ADOdb MetaType() call.
 *
 * @uses $CFG
 * @uses $db
 * @param string $table The name of the database table
 * @param string $column The name of the field in the table
 * @return string Field type or false if error
 */

function column_type($table, $column) {
    global $CFG, $db;

    if (defined('ELGG_PERFDB')) { global $PERF ; $PERF->dbqueries++; };

    if(!$rs = $db->Execute('SELECT '.$column.' FROM '.$CFG->prefix.$table.' LIMIT 0')) {
        return false;
    }
    
    $field = $rs->FetchField(0);
    return $rs->MetaType($field->type);
}


/// GENERIC FUNCTIONS TO CHECK AND COUNT RECORDS ////////////////////////////////////////

/**
 * Test whether a record exists in a table where all the given fields match the given values.
 *
 * The record to test is specified by giving up to three fields that must
 * equal the corresponding values.
 *
 * @uses $CFG
 * @param string $table The table to check.
 * @param string $field1 the first field to check (optional).
 * @param string $value1 the value field1 must have (requred if field1 is given, else optional).
 * @param string $field2 the second field to check (optional).
 * @param string $value2 the value field2 must have (requred if field2 is given, else optional).
 * @param string $field3 the third field to check (optional).
 * @param string $value3 the value field3 must have (requred if field3 is given, else optional).
 * @return bool true if a matching record exists, else false.
 */
function record_exists($table, $field1=null, $value1=null, $field2=null, $value2=null, $field3=null, $value3=null) {

    global $CFG;

    $select = where_clause_prepared($field1, $field2, $field3);

    $values = where_values_prepared($value1, $value2, $value3);
    
    return record_exists_sql('SELECT * FROM '. $CFG->prefix . $table .' '. $select .' LIMIT 1',$values);
}


/**
* Determine whether a specified record exists.
*
* This function returns true if the SQL executed returns records.
*
* @uses $CFG
* @uses $db
* @param string $sql The SQL statement to be executed. If using $values, placeholder ?s are expected. If not, the string should be escaped correctly.
* @param array $values When using prepared statements, this is the value array. Optional.
* @return bool
*/
function record_exists_sql($sql,$values=null) {

    global $CFG, $db;

    if (defined('ELGG_PERFDB')) { global $PERF ; $PERF->dbqueries++; };

    $rs = false;
    if (!empty($values) && is_array($values) && count($values) > 0) {
        $stmt = $db->Prepare($sql);
        $rs = $db->Execute($stmt,$values);
    } else {
        $rs = $db->Execute($sql);
    }
    
    if (empty($rs)) {
        if (isset($CFG->debug) and $CFG->debug > 7) {
            notify($db->ErrorMsg().'<br /><br />'.$sql);
        }
        if (!empty($CFG->dblogerror)) {
            $debug = debug_backtrace();
            foreach ($debug as $d) {
                if (strpos($d['file'],'datalib') === false) {
                    error_log("SQL ".$db->ErrorMsg()." in {$d['file']} on line {$d['line']}. STATEMENT:  $sql");
                    break;
                }
            }
        }
        return false;
    }

    if ( $rs->RecordCount() ) {
        return true;
    } else {
        return false;
    }
    }


/**
 * Count the records in a table where all the given fields match the given values.
 *
 * @uses $CFG
 * @param string $table The table to query.
 * @param string $field1 the first field to check (optional).
 * @param string $value1 the value field1 must have (requred if field1 is given, else optional).
 * @param string $field2 the second field to check (optional).
 * @param string $value2 the value field2 must have (requred if field2 is given, else optional).
 * @param string $field3 the third field to check (optional).
 * @param string $value3 the value field3 must have (requred if field3 is given, else optional).
 * @return int The count of records returned from the specified criteria.
 */
function count_records($table, $field1=null, $value1=null, $field2=null, $value2=null, $field3=null, $value3=null) {

    global $CFG;

    $select = where_clause_prepared($field1, $field2, $field3);

    $values = where_values_prepared($value1, $value2, $value3);

    return count_records_sql('SELECT COUNT(*) FROM '. $CFG->prefix . $table .' '. $select, $values);
}

/**
 * Get all the records and count them
 *
 * @uses $CFG
 * @param string $table The database table to be checked against.
 * @param string $select A fragment of SQL to be used in a where clause in the SQL call.
 * @param array $values if using a prepared statement with placeholders in $select, pass values here. optional
 * @param string $countitem The count string to be used in the SQL call. Default is COUNT(*).
 * @return int The count of records returned from the specified criteria.
 */
function count_records_select($table, $select='', $values=null, $countitem='COUNT(*)') {

    global $CFG;

    if ($select) {
        $select = 'WHERE '.$select;
    }

    return count_records_sql('SELECT '. $countitem .' FROM '. $CFG->prefix . $table .' '. $select, $values);
}


/**
 * Get all the records returned from the specified SQL call and return the count of them
 *
 * @uses $CFG
 * @uses $db
 * @param string $sql The SQL string you wish to be executed.
 * @return int The count of records returned from the specified SQL string.
 */
function count_records_sql($sql, $values=null) {

    global $CFG, $db;

    if (defined('ELGG_PERFDB')) { global $PERF ; $PERF->dbqueries++; };

    $rs = false;
    if (!empty($values) && is_array($values) && count($values) > 0) {
        $stmt = $db->Prepare($sql);
        $rs = $db->Execute($stmt,$values);
    } else {
        $rs = $db->Execute($sql);
    }
    if (!$rs) {
        if (isset($CFG->debug) and $CFG->debug > 7) {
            notify($db->ErrorMsg() .'<br /><br />'. $sql);
        }
        if (!empty($CFG->dblogerror)) {
            $debug = debug_backtrace();
            foreach ($debug as $d) {
                if (strpos($d['file'],'datalib') === false) {
                    error_log("SQL ".$db->ErrorMsg()." in {$d['file']} on line {$d['line']}. STATEMENT:  $sql");
                    break;
                }
            }
        }
        return 0;
    }

    return reset($rs->fields);
}




/// GENERIC FUNCTIONS TO GET, INSERT, OR UPDATE DATA  ///////////////////////////////////

/**
 * Get a single record as an object
 *
 * @uses $CFG
 * @param string $table The table to select from.
 * @param string $field1 the first field to check (optional).
 * @param string $value1 the value field1 must have (requred if field1 is given, else optional).
 * @param string $field2 the second field to check (optional).
 * @param string $value2 the value field2 must have (requred if field2 is given, else optional).
 * @param string $field3 the third field to check (optional).
 * @param string $value3 the value field3 must have (requred if field3 is given, else optional).
 * @return mixed a fieldset object containing the first mathcing record, or false if none found.
 */
function get_record($table, $field1=null, $value1=null, $field2=null, $value2=null, $field3=null, $value3=null, $fields='*') {

    global $CFG;
    $trycache = false;
    
    //just cache things by primary key for now
    if ($field1 == "ident" && $value1 == intval($value1) && empty($field2)  && empty($value2) && empty($field3) && empty($value3) && $fields == "*") {
        $trycache = true;
        $cacheval = elggcache_get($table, $field1 . "_" . intval($value1));
        if (!is_null($cacheval)) {
            return $cacheval;
        } else {
            
        }
    }

    $select = where_clause_prepared($field1, $field2, $field3);

    $values = where_values_prepared($value1, $value2, $value3);
    
    $returnvalue = get_record_sql('SELECT '.$fields.' FROM '. $CFG->prefix . $table .' '. $select, $values);
    
    if ($trycache) {
        $setres = elggcache_set($table, $field1 . "_" . $value1, $returnvalue);
    }
    
    return $returnvalue;
}

/**
 * Get a single record as an object using the specified SQL statement
 *
 * A LIMIT is normally added to only look for 1 record
 * If debugging is OFF only the first record is returned even if there is
 * more than one matching record!
 *
 * @uses $CFG
 * @uses $db
 * @param string $sql The SQL string you wish to be executed.
 * @param array $values If using placeholder ?s in the $sql, pass values here.
 * @return Found record as object. False if not found or error
 */
function get_record_sql($sql, $values=null, $expectmultiple=false, $nolimit=false) {

    global $db, $CFG;

    if (isset($CFG->debug) && $CFG->debug > 7 && !$expectmultiple) {    // Debugging mode - don't use limit
       $limit = '';
    } else if ($nolimit) {
       $limit = '';
    } else {
       $limit = ' LIMIT 1';    // Workaround - limit to one record
    }

    if (defined('ELGG_PERFDB')) { global $PERF ; $PERF->dbqueries++; };

    $rs = false;
    if (!empty($values) && is_array($values) && count($values) > 0) {
        $stmt = $db->Prepare($sql. $limit);
        $rs = $db->Execute($stmt, $values);
    } else {
        $rs = $db->Execute($sql . $limit);
    } 
    if (!$rs) {
        if (isset($CFG->debug) and $CFG->debug > 7) {    // Debugging mode - print checks
            notify( $db->ErrorMsg() . '<br /><br />'. $sql . $limit );
        }
        if (!empty($CFG->dblogerror)) {
            $debug = debug_backtrace();
            foreach ($debug as $d) {
                if (strpos($d['file'],'datalib') === false) {
                    error_log("SQL ".$db->ErrorMsg()." in {$d['file']} on line {$d['line']}. STATEMENT:  $sql$limit");
                    break;
                }
            }
        }
        return false;
    }

    if (!$recordcount = $rs->RecordCount()) {
        return false;                 // Found no records
    }

    if ($recordcount == 1) {          // Found one record
        return (object)$rs->fields;

    } else {                          // Error: found more than one record
        notify('Error:  Turn off debugging to hide this error.');
        notify($sql . $limit);
        if ($records = elgg_GetAssoc($rs)) {
            notify('Found more than one record in get_record_sql !');
            print_object($records);
        } else {
            notify('Very strange error in get_record_sql !');
            print_object($rs);
        }
        print_continue("$CFG->wwwroot/$CFG->admin/config.php");
    }
}

/**
 * Gets one record from a table, as an object
 *
 * @uses $CFG
 * @param string $table The database table to be checked against.
 * @param string $select A fragment of SQL to be used in a where clause in the SQL call.
 * @param array $values If using placeholder ? in $select, pass values here.
 * @param string $fields A comma separated list of fields to be returned from the chosen table.
 * @return object|false Returns an array of found records (as objects) or false if no records or error occured.
 */
function get_record_select($table, $select='', $values=null, $fields='*') {

    global $CFG;

    if ($select) {
        $select = 'WHERE '. $select;
    }

    return get_record_sql('SELECT '. $fields .' FROM '. $CFG->prefix . $table .' '. $select, $values);
}

/**
 * Get a number of records as an ADODB RecordSet.
 *
 * Selects records from the table $table.
 * 
 * If specified, only records where the field $field has value $value are retured.
 * 
 * If specified, the results will be sorted as specified by $sort. This
 * is added to the SQL as "ORDER BY $sort". Example values of $sort
 * mightbe "time ASC" or "time DESC".
 * 
 * If $fields is specified, only those fields are returned.
 * Use this wherever possible to reduce memory requirements.
 * 
 * If you only want some of the records, specify $limitfrom and $limitnum.
 * The query will skip the first $limitfrom records (according to the sort
 * order) and then return the next $limitnum records. If either of $limitfrom
 * or $limitnum is specified, both must be present.
 * 
 * The return value is an ADODB RecordSet object
 * @link http://phplens.com/adodb/reference.functions.adorecordset.html
 * if the query succeeds. If an error occurrs, false is returned.
 *
 * @param string $table the table to query.
 * @param string $field a field to check (optional).
 * @param string $value the value the field must have (requred if field1 is given, else optional).
 * @param string $sort an order to sort the results in (optional, a valid SQL ORDER BY parameter).
 * @param string $fields a comma separated list of fields to return (optional, by default all fields are returned).
 * @param int $limitfrom return a subset of records, starting at this point (optional, required if $limitnum is set).
 * @param int $limitnum return a subset comprising this many records (optional, required if $limitfrom is set).
 * @return mixed an ADODB RecordSet object, or false if an error occured.
 */
function get_recordset($table, $field='', $value='', $sort='', $fields='*', $limitfrom='', $limitnum='') {

    $values = null;
    if ($field) {
        $select = "$field = ?";
        $values = array($value);
    } else {
        $select = '';
    }
    
    return get_recordset_select($table, $select, $values, $sort, $fields, $limitfrom, $limitnum);
}

/**
 * Get a number of records as an ADODB RecordSet.
 *
 * If given, $select is used as the SELECT parameter in the SQL query,
 * otherwise all records from the table are returned.
 * 
 * Other arguments and the return type as for @see function get_recordset. 
 *
 * @uses $CFG
 * @param string $table the table to query.
 * @param string $select A fragment of SQL to be used in a where clause in the SQL call.
 * @param array $values If using placeholder ?s in $select, pass values here.
 * @param string $sort an order to sort the results in (optional, a valid SQL ORDER BY parameter).
 * @param string $fields a comma separated list of fields to return (optional, by default all fields are returned).
 * @param int $limitfrom return a subset of records, starting at this point (optional, required if $limitnum is set).
 * @param int $limitnum return a subset comprising this many records (optional, required if $limitfrom is set).
 * @return mixed an ADODB RecordSet object, or false if an error occured.
 */
function get_recordset_select($table, $select='', $values=null, $sort='', $fields='*', $limitfrom='', $limitnum='') {

    global $CFG;

    if ($select) {
        $select = ' WHERE '. $select;
    }

    if ($limitfrom !== '') {
        $limit = sql_paging_limit($limitfrom, $limitnum);
    } else {
        $limit = '';
    }

    if ($sort) {
        $sort = ' ORDER BY '. $sort;
    }

    return get_recordset_sql('SELECT '. $fields .' FROM '. $CFG->prefix . $table . $select . $sort .' '. $limit, $values);
}

/**
 * Get a number of records as an ADODB RecordSet.
 *
 * Only records where $field takes one of the values $values are returned.
 * $values should be a comma-separated list of values, for example "4,5,6,10"
 * or "'foo','bar','baz'".
 * 
 * Other arguments and the return type as for @see function get_recordset. 
 *
 * @param string $table the table to query.
 * @param string $field a field to check (optional).
 * @param array $values the value the field must have (requred if field1 is given, else optional). 
 * @param string $sort an order to sort the results in (optional, a valid SQL ORDER BY parameter).
 * @param string $fields a comma separated list of fields to return (optional, by default all fields are returned).
 * @param int $limitfrom return a subset of records, starting at this point (optional, required if $limitnum is set).
 * @param int $limitnum return a subset comprising this many records (optional, required if $limitfrom is set).
 * @return mixed an ADODB RecordSet object, or false if an error occured.
 */
function get_recordset_list($table, $field='', $values=null, $sort='', $fields='*', $limitfrom='', $limitnum='') {

    global $CFG;

    if (!empty($field) && is_array($values) && count($values) > 0) {
        $placeholder = array();
        for ($i = 0; $i < count($values); $i++) {
            $placeholder[] = '?';
        }
        $select = "$field IN (".implode(',',$placeholder).")";
    } else {
        $select = '';
    }

    get_recordset_select($table, $select, $values, $sort, $fields, $limitfrom, $limitnum);
}

/**
 * Get a number of records as an ADODB RecordSet.
 *
 * $sql must be a complete SQL query.
 *  
 * The return type is as for @see function get_recordset. 
 *
 * @uses $CFG
 * @uses $db
 * @param string $sql the SQL select query to execute.
 * @return mixed an ADODB RecordSet object, or false if an error occured.
 */
function get_recordset_sql($sql,$values=null) {

    global $CFG, $db;

    if (defined('ELGG_PERFDB')) { global $PERF ; $PERF->dbqueries++; };

    if (!empty($CFG->vardumpsql)) {
        var_dump($sql);
    }
    
    $rs = false;
    if (!empty($values) && is_array($values) && count($values) > 0) {
        $stmt = $db->Prepare($sql);
        $rs = $db->Execute($stmt,$values);
    } else {
        $rs = $db->Execute($sql);
    }
    if (!$rs) {
        if (isset($CFG->debug) and $CFG->debug > 7) {
            notify($db->ErrorMsg() .'<br /><br />'. $sql);
        }
        if (!empty($CFG->dblogerror)) {
            $debug = debug_backtrace();
            foreach ($debug as $d) {
                if (strpos($d['file'],'datalib') === false) {
                    error_log("SQL ".$db->ErrorMsg()." in {$d['file']} on line {$d['line']}. STATEMENT:  $sql");
                    break;
                }
            }
        }
        return false;
    }

    return $rs;
}

/**
 * Utility function used by the following 4 methods.
 * 
 * @param object an ADODB RecordSet object.
 * @return mixed mixed an array of objects, or false if an error occured or the RecordSet was empty.
 */
function recordset_to_array($rs) {
    if ($rs && $rs->RecordCount() > 0) {
        if ($records = elgg_GetAssoc($rs)) {
            foreach ($records as $key => $record) {
                $objects[$key] = (object) $record;
            }
            return $objects;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Get a number of records as an array of objects.
 *
 * @deprecated try to use @see function get_recordset instead.
 *
 * Arguments as for @see function get_recordset.
 * 
 * If the query succeeds and returns at least one record, the
 * return value is an array of objects, one object for each
 * record found. The array key is the value from the first 
 * column of the result set. The object associated with that key
 * has a member variable for each column of the results.
 *
 * @param string $table the table to query.
 * @param string $field a field to check (optional).
 * @param string $value the value the field must have (requred if field1 is given, else optional).
 * @param string $sort an order to sort the results in (optional, a valid SQL ORDER BY parameter).
 * @param string $fields a comma separated list of fields to return (optional, by default all fields are returned).
 * @param int $limitfrom return a subset of records, starting at this point (optional, required if $limitnum is set).
 * @param int $limitnum return a subset comprising this many records (optional, required if $limitfrom is set).
 * @return mixed an array of objects, or false if no records were found or an error occured.
 */
function get_records($table, $field='', $value='', $sort='', $fields='*', $limitfrom='', $limitnum='') {
    $rs = get_recordset($table, $field, $value, $sort, $fields, $limitfrom, $limitnum);
    return recordset_to_array($rs);
}

/**
 * Get a number of records as an array of objects.
 *
 * @deprecated try to use @see function get_recordset_select instead.
 *
 * Arguments as for @see function get_recordset_select.
 * Return value as for @see function get_records.
 *
 * @param string $table the table to query.
 * @param string $select A fragment of SQL to be used in a where clause in the SQL call.
 * @param array $values if using placeholder ? in $select, pass values here.
 * @param string $sort an order to sort the results in (optional, a valid SQL ORDER BY parameter).
 * @param string $fields a comma separated list of fields to return (optional, by default all fields are returned).
 * @param int $limitfrom return a subset of records, starting at this point (optional, required if $limitnum is set).
 * @param int $limitnum return a subset comprising this many records (optional, required if $limitfrom is set).
 * @return mixed an array of objects, or false if no records were found or an error occured.
 */
function get_records_select($table, $select='', $values=null, $sort='', $fields='*', $limitfrom='', $limitnum='') {
    $rs = get_recordset_select($table, $select, $values, $sort, $fields, $limitfrom, $limitnum);
    return recordset_to_array($rs);
}

/**
 * Get a number of records as an array of objects.
 *
 * @deprecated try to use @see function get_recordset_list instead.
 *
 * Arguments as for @see function get_recordset_list.
 * Return value as for @see function get_records.
 *
 * @param string $table The database table to be checked against.
 * @param string $field The field to search
 * @param array $values Array of possible values
 * @param string $sort Sort order (as valid SQL sort parameter)
 * @param string $fields A comma separated list of fields to be returned from the chosen table.
 * @return mixed an array of objects, or false if no records were found or an error occured.
 */
function get_records_list($table, $field='', $values=null, $sort='', $fields='*', $limitfrom='', $limitnum='') {
    $rs = get_recordset_list($table, $field, $values, $sort, $fields, $limitfrom, $limitnum);
    return recordset_to_array($rs);
}

/**
 * Get a number of records as an array of objects.
 *
 * @deprecated try to use @see function get_recordset_list instead.
 *
 * Arguments as for @see function get_recordset_sql.
 * Return value as for @see function get_records.
 * 
 * @param string $sql the SQL select query to execute.
 * @return mixed an array of objects, or false if no records were found or an error occured.
 */
function get_records_sql($sql,$values=null) {
    $rs = get_recordset_sql($sql,$values);
    return recordset_to_array($rs);
}

/**
 * Utility function used by the following 3 methods.
 * 
 * @param object an ADODB RecordSet object with two columns.
 * @return mixed an associative array, or false if an error occured or the RecordSet was empty.
 */
function recordset_to_menu($rs) {
    if ($rs && $rs->RecordCount() > 0) {
        while (!$rs->EOF) {
            $menu[reset($rs->fields)] = $rs->fields[1];
            $rs->MoveNext();
        }
        return $menu;
    } else {
        return false;
    }
}

/**
 * Get the first two columns from a number of records as an associative array.
 *
 * Arguments as for @see function get_recordset.
 * 
 * If no errors occur, and at least one records is found, the return value
 * is an associative whose keys come from the first field of each record,
 * and whose values are the corresponding second fields. If no records are found,
 * or an error occurs, false is returned.
 *
 * @param string $table the table to query.
 * @param string $field a field to check (optional).
 * @param string $value the value the field must have (requred if field1 is given, else optional).
 * @param string $sort an order to sort the results in (optional, a valid SQL ORDER BY parameter).
 * @param string $fields a comma separated list of fields to return (optional, by default all fields are returned).
 * @return mixed an associative array, or false if no records were found or an error occured.
 */
function get_records_menu($table, $field='', $value='', $sort='', $fields='*') {
    $rs = get_recordset($table, $field, $value, $sort, $fields);
    return recordset_to_menu($rs);
}

/**
 * Get the first two columns from a number of records as an associative array.
 *
 * Arguments as for @see function get_recordset_select.
 * Return value as for @see function get_records_menu.
 *
 * @param string $table The database table to be checked against.
 * @param string $select A fragment of SQL to be used in a where clause in the SQL call.
 * @param string $sort Sort order (optional) - a valid SQL order parameter
 * @param string $fields A comma separated list of fields to be returned from the chosen table.
 * @return mixed an associative array, or false if no records were found or an error occured.
 */
function get_records_select_menu($table, $select='', $values=null, $sort='', $fields='*') {
    $rs = get_recordset_select($table, $select, $values, $sort, $fields);
    return recordset_to_menu($rs);
}

/**
 * Get the first two columns from a number of records as an associative array.
 *
 * Arguments as for @see function get_recordset_sql.
 * Return value as for @see function get_records_menu.
 *
 * @param string $sql The SQL string you wish to be executed.
 * @return mixed an associative array, or false if no records were found or an error occured.
 */
function get_records_sql_menu($sql,$values=null) {
    $rs = get_recordset_sql($sql,$values);
    return recordset_to_menu($rs);
}

/**
 * Get a single value from a table row where all the given fields match the given values.
 *
 * @uses $CFG
 * @uses $db
 * @param string $table the table to query.
 * @param string $return the field to return the value of.
 * @param string $field1 the first field to check (optional).
 * @param string $value1 the value field1 must have (requred if field1 is given, else optional).
 * @param string $field2 the second field to check (optional).
 * @param string $value2 the value field2 must have (requred if field2 is given, else optional).
 * @param string $field3 the third field to check (optional).
 * @param string $value3 the value field3 must have (requred if field3 is given, else optional).
 * @return mixed the specified value, or false if an error occured.
 */
function get_field($table, $return, $field1, $value1, $field2=null, $value2=null, $field3=null, $value3=null) {

    global $db, $CFG;

    $select = where_clause_prepared($field1, $field2, $field3);

    $values = where_values_prepared($value1, $value2, $value3);

    if (defined('ELGG_PERFDB')) { global $PERF ; $PERF->dbqueries++; };
    
    // this always generates a where query, so there must always be values to look up
    if (count($values)) {
        $stmt = $db->Prepare('SELECT '. $return .' FROM '. $CFG->prefix . $table .' '. $select);
        $rs = $db->Execute($stmt,$values);
        if (!$rs) {
            if (isset($CFG->debug) and $CFG->debug > 7) {
                notify($db->ErrorMsg() .'<br /><br />SELECT '. $return .' FROM '. $CFG->prefix . $table .' '. $select);
            }
            if (!empty($CFG->dblogerror)) {
                $debug = debug_backtrace();
                foreach ($debug as $d) {
                    if (strpos($d['file'],'datalib') === false) {
                        error_log("SQL ".$db->ErrorMsg()." in {$d['file']} on line {$d['line']}. STATEMENT:  SELECT $return FROM $CFG->prefix$table $select");
                        break;
                    }
                }
            }
            return false;
        }
    
        if ( $rs->RecordCount() == 1 ) {
            return $rs->fields[$return];
        } else {
            return false;
        }
    } else {
        return false;
    }
}


/**
 * Get a single field from a database record
 *
 * @uses $CFG
 * @uses $db
 * @param string $sql The SQL string you wish to be executed.
 * @return mixed|false Returns the value return from the SQL statment or false if an error occured.
 * @todo Finish documenting this function
 */
function get_field_sql($sql, $values=null) {

    global $db, $CFG;

    if (defined('ELGG_PERFDB')) { global $PERF ; $PERF->dbqueries++; };

    $rs = false;
    if (!empty($values) && is_array($values) && count($values) > 0) {
        $stmt = $db->Prepare($sql);
        $rs = $db->Execute($stmt,$values);
    } else {
        $rs = $db->Execute($sql);
    }
    if (!$rs) {
        if (isset($CFG->debug) and $CFG->debug > 7) {
            notify($db->ErrorMsg() .'<br /><br />'. $sql);
        }
        if (!empty($CFG->dblogerror)) {
            $debug = debug_backtrace();
            foreach ($debug as $d) {
                if (strpos($d['file'],'datalib') === false) {
                    error_log("SQL ".$db->ErrorMsg()." in {$d['file']} on line {$d['line']}. STATEMENT:  $sql");
                    break;
                }
            }
        }
        return false;
    }

    if ( $rs->RecordCount() == 1 ) {
        return reset($rs->fields);
    } else {
        return false;
    }
}

/**
 * Set a single field in the table row where all the given fields match the given values.
 *
 * @uses $CFG
 * @uses $db
 * @param string $table The database table to be checked against.
 * @param string $newfield the field to set.
 * @param string $newvalue the value to set the field to.
 * @param string $field1 the first field to check (optional).
 * @param string $value1 the value field1 must have (requred if field1 is given, else optional).
 * @param string $field2 the second field to check (optional).
 * @param string $value2 the value field2 must have (requred if field2 is given, else optional).
 * @param string $field3 the third field to check (optional).
 * @param string $value3 the value field3 must have (requred if field3 is given, else optional).
 * @return mixed An ADODB RecordSet object with the results from the SQL call or false.
 */
function set_field($table, $newfield, $newvalue, $field1, $value1, $field2=null, $value2=null, $field3=null, $value3=null) {

    global $db, $CFG;

    if (defined('ELGG_PERFDB')) { global $PERF ; $PERF->dbqueries++; };

    $select = where_clause_prepared($field1, $field2, $field3);
    $values = where_values_prepared($newvalue, $value1, $value2, $value3);
    
    $stmt = $db->Prepare('UPDATE '. $CFG->prefix . $table .' SET '. $newfield  .' = ? '. $select);
    $returnvalue = $db->Execute($stmt,$values);
    
    if ($field1 == "ident") {
         // updating by primary key
        elggcache_delete($table, $field1 . "_" . $value1);
    } else {
        // sledgehammer :(
        elggcache_cachepurgetype($table);
    }
    
    return $returnvalue;
}

/**
 * Delete the records from a table where all the given fields match the given values.
 *
 * @uses $CFG
 * @uses $db
 * @param string $table the table to delete from.
 * @param string $field1 the first field to check (optional).
 * @param string $value1 the value field1 must have (requred if field1 is given, else optional).
 * @param string $field2 the second field to check (optional).
 * @param string $value2 the value field2 must have (requred if field2 is given, else optional).
 * @param string $field3 the third field to check (optional).
 * @param string $value3 the value field3 must have (requred if field3 is given, else optional).
 * @return mixed An ADODB RecordSet object with the results from the SQL call or false.
 */
function delete_records($table, $field1=null, $value1=null, $field2=null, $value2=null, $field3=null, $value3=null) {

    global $db, $CFG;

    if (defined('ELGG_PERFDB')) { global $PERF ; $PERF->dbqueries++; };

    $select = where_clause_prepared($field1, $field2, $field3);
    $values = where_values_prepared($value1, $value2, $value3);
    
    $stmt = $db->Prepare('DELETE FROM '. $CFG->prefix . $table .' '. $select);
    $returnvalue = $db->Execute($stmt,$values);
    
    if ($field1 == "ident") {
         // updating by primary key
        elggcache_delete($table, $field1 . "_" . $value1);
    } else {
        // sledgehammer :(
        elggcache_cachepurgetype($table);
    }
    
    return $returnvalue;
}

/**
 * Delete one or more records from a table
 *
 * @uses $CFG
 * @uses $db
 * @param string $table The database table to be checked against.
 * @param string $select A fragment of SQL to be used in a where clause in the SQL call (used to define the selection criteria).
 * @return object A PHP standard object with the results from the SQL call.
 * @todo Verify return type.
 */
function delete_records_select($table, $select='',$values=null) {

    global $CFG, $db;

    if (defined('ELGG_PERFDB')) { global $PERF ; $PERF->dbqueries++; };

    if ($select) {
        $select = 'WHERE '.$select;
    }

    $result = false;
    if (!empty($values) && is_array($values) && count($values) > 0) {
        $stmt = $db->Prepare('DELETE FROM '. $CFG->prefix . $table .' '. $select);
        $result = $db->Execute($stmt,$values);
    } else {
        $result = $db->Execute('DELETE FROM '. $CFG->prefix . $table .' '. $select);
    }
    
    elggcache_cachepurgetype($table);
    
    return $result;
}

/**
 * Insert a record into a table and return the "ident" field if required
 *
 * If the return ID isn't required, then this just reports success as true/false.
 * $dataobject is an object containing needed data
 *
 * @uses $db
 * @uses $CFG
 * @param string $table The database table to be checked against.
 * @param array $dataobject A data object with values for one or more fields in the record
 * @param bool $returnid Should the id of the newly created record entry be returned? If this option is not requested then true/false is returned.
 * @param string $primarykey The primary key of the table we are inserting into (almost always "ident")
 */
function insert_record($table, $dataobject, $returnid=true, $primarykey='ident') {

    global $db, $CFG;
    static $table_columns;
    
    // Determine all the fields in the table
    if (is_array($table_columns) && array_key_exists($table,$table_columns)) {
        $columns = $table_columns[$table];
    } else {
        if (!$columns = $db->MetaColumns($CFG->prefix . $table)) {
            return false;
        }
        $table_columns[$table] = $columns;
    }
    

    if (defined('ELGG_PERFDB')) { global $PERF ; $PERF->dbqueries++; };

    /// Postgres doesn't have the concept of primary key built in
    /// and will return the OID which isn't what we want.
    /// The efficient and transaction-safe strategy is to 
    /// move the sequence forward first, and make the insert
    /// with an explicit id.
    if ( empty($dataobject->{$primarykey}) 
         && $CFG->dbtype === 'postgres7'      
         && $returnid == true ) {        
        if ($nextval = (int)get_field_sql("SELECT NEXTVAL('{$CFG->prefix}{$table}_{$primarykey}_seq')")) {
            $setfromseq = true;
            $dataobject->{$primarykey} = $nextval;
        } 
    }

    $data = (array)$dataobject;
    $ddd = array();

  // Pull out data matching these fields
    foreach ($columns as $column) {
        if ($column->name <> 'ident' and isset($data[$column->name]) ) {
            $ddd[$column->name] = $data[$column->name];
        }
    }

    if (!empty($setfromseq)) {
        $ddd['ident'] = $dataobject->ident;
    }

    // Construct SQL queries
    $numddd = count($ddd);
    $count = 0;
    $insertSQL = 'INSERT INTO '.$CFG->prefix . $table .' (';
    $fields = '';
    $values = '';
    foreach ($ddd as $key => $value) {
        $count++;
        $fields .= $key;
        $values .= '?';
        if ($count < $numddd) {
            $fields .= ', ';
            $values .= ', ';
        }
    }
    $insertSQL .= $fields.') VALUES ('.$values.')';

    /// Run the SQL statement
    $stmt = $db->Prepare($insertSQL);
    if (!$rs = $db->Execute($stmt,$ddd)) {
        if (isset($CFG->debug) and $CFG->debug > 7) {
            notify($db->ErrorMsg() .'<br /><br />'.$insertSQL);
        }
        if (!empty($CFG->dblogerror)) {
            $debug = debug_backtrace();
            foreach ($debug as $d) {
                if (strpos($d['file'],'datalib') === false) {
                    error_log("SQL ".$db->ErrorMsg()." in {$d['file']} on line {$d['line']}. STATEMENT:  $insertSQL");
                    break;
                }
            }
        }
        return false;
    }

/// If a return ID is not needed then just return true now
    if (!$returnid) {
        return true;
    }

/// We already know the record PK if it's been passed explicitly,
/// or if we've retrieved it from a sequence (Postgres).
    if (!empty($dataobject->{$primarykey})) {
        return $dataobject->{$primarykey};
    }

/// This only gets triggered with non-Postgres databases
/// however we have some postgres fallback in case we failed 
/// to find the sequence.
    $id = $db->Insert_ID();  

    if ($CFG->dbtype === 'postgres7') {
        // try to get the primary key based on id
        if ( ($rs = $db->Execute('SELECT '. $primarykey .' FROM '. $CFG->prefix . $table .' WHERE oid = '. $id))
             && ($rs->RecordCount() == 1) ) {
            trigger_error("Retrieved $primarykey from oid on table $table because we could not find the sequence.");
            return (integer) reset($rs->fields);
        } 
        trigger_error('Failed to retrieve primary key after insert: SELECT '. $primarykey .
                      ' FROM '. $CFG->prefix . $table .' WHERE oid = '. $id);
        return false;
    }

    return (integer)$id;
}

/** 
 * Escape all dangerous characters in a data record
 *
 * $dataobject is an object containing needed data
 * Run over each field exectuting addslashes() function
 * to escape SQL unfriendly characters (e.g. quotes)
 * Handy when writing back data read from the database
 *
 * @param $dataobject Object containing the database record
 * @return object Same object with neccessary characters escaped
 */
function addslashes_object( $dataobject ) {
    $a = get_object_vars( $dataobject);
    foreach ($a as $key=>$value) {
      $a[$key] = addslashes( $value );
    }
    return (object)$a;
}

/**
 * Update a record in a table
 *
 * $dataobject is an object containing needed data
 * Relies on $dataobject having a variable "ident" to
 * specify the record to update
 *
 * @uses $CFG
 * @uses $db
 * @param string $table The database table to be checked against.
 * @param array $dataobject An object with contents equal to fieldname=>fieldvalue. Must have an entry for 'ident' to map to the table specified.
 * @return bool
 * @todo Finish documenting this function. Dataobject is actually an associateive array, correct?
 */
function update_record($table, $dataobject) {

    global $db, $CFG;

    if (! isset($dataobject->ident) ) {
        return false;
    }

    static $table_columns;
    
    // Determine all the fields in the table
    if (is_array($table_columns) && array_key_exists($table,$table_columns)) {
        $columns = $table_columns[$table];
    } else {
        if (!$columns = $db->MetaColumns($CFG->prefix . $table)) {
            return false;
        }
        $table_columns[$table] = $columns;
    }

    $data = (array)$dataobject;
    $ddd = array();

    if (defined('ELGG_PERFDB')) { global $PERF ; $PERF->dbqueries++; };

    // Pull out data matching these fields
    foreach ($columns as $column) {
        if ($column->name <> 'ident' and isset($data[$column->name]) ) {
            $ddd[$column->name] = $data[$column->name];
        }
    }

    // Construct SQL queries
    $numddd = count($ddd);
    $count = 0;
    $update = '';

    foreach ($ddd as $key => $value) {
        $count++;
        $update .= $key .' = ?'; 
        if ($count < $numddd) {
            $update .= ', ';
        }
    }

    $stmt = $db->Prepare('UPDATE '. $CFG->prefix . $table .' SET '. $update .' WHERE ident = \''. $dataobject->ident .'\'');
    if ($rs = $db->Execute($stmt,$ddd)) {
        elggcache_delete($table, "ident_" . $dataobject->ident);
        return true;
    } else {
        if (isset($CFG->debug) and $CFG->debug > 7) {
            notify($db->ErrorMsg() .'<br /><br />UPDATE '. $CFG->prefix . $table .' SET '. $update .' WHERE ident = \''. $dataobject->ident .'\'');
        }
        if (!empty($CFG->dblogerror)) {
            $debug = debug_backtrace();
            foreach ($debug as $d) {
                if (strpos($d['file'],'datalib') === false) {
                    error_log("SQL ".$db->ErrorMsg()." in {$d['file']} on line {$d['line']}. STATEMENT:  UPDATE $CFG->prefix$table SET $update WHERE ident = '$dataobject->ident'");
                    break;
                }
            }
        }
        return false;
    }
}

/// GENERAL HELPFUL THINGS  ///////////////////////////////////

/**
 * Dump a given object's information in a PRE block.
 *
 * Mostly just used for debugging.
 *
 * @param mixed $object The data to be printed
 * @todo add example usage and example output
 */
function print_object($object) {

    echo '<pre>';
    print_r($object);
    echo '</pre>';
}

/**
 * Returns the proper SQL to do paging
 *
 * @uses $CFG
 * @param string $page Offset page number
 * @param string $recordsperpage Number of records per page
 * @return string
 */
function sql_paging_limit($page, $recordsperpage) {
    global $CFG;

    switch ($CFG->dbtype) {
        case 'postgres7':
             return ' LIMIT '. $recordsperpage .' OFFSET '. $page;
        default:
             return ' LIMIT '. $page .','. $recordsperpage;
    }
}

/**
 * Returns the proper SQL to do LIKE in a case-insensitive way
 *
 * @uses $CFG
 * @return string
 */
function sql_ilike() {
    global $CFG;

    switch ($CFG->dbtype) {
        case 'mysql':
             return 'LIKE';
        default:
             return 'ILIKE';
    }
}


/**
 * Returns the proper SQL to do LIKE in a case-insensitive way
 *
 * @uses $CFG
 * @param string $firstname User's first name
 * @param string $lastname User's last name
 * @return string
 */
function sql_fullname($firstname='firstname', $lastname='lastname') {
    global $CFG;

    switch ($CFG->dbtype) {
        case 'mysql':
             return ' CONCAT('. $firstname .'," ",'. $lastname .') ';
        case 'postgres7':
             return " ". $firstname ."||' '||". $lastname ." ";
        default:
             return ' '. $firstname .'||" "||'. $lastname .' ';
    }
}

/**
 * Returns the proper SQL to do IS NULL
 * @uses $CFG
 * @param string $fieldname The field to add IS NULL to
 * @return string
 */
function sql_isnull($fieldname) {
    global $CFG;

    switch ($CFG->dbtype) {
        case 'mysql':
             return $fieldname.' IS NULL';
        default:
             return $fieldname.' IS NULL';
    }
}

/** 
 * Prepare a SQL WHERE clause to select records where the given fields match the given values.
 * 
 * Prepares a where clause of the form
 *     WHERE field1 = value1 AND field2 = value2 AND field3 = value3
 * except that you need only specify as many arguments (zero to three) as you need.
 * 
 * @param string $field1 the first field to check (optional).
 * @param string $value1 the value field1 must have (requred if field1 is given, else optional).
 * @param string $field2 the second field to check (optional).
 * @param string $value2 the value field2 must have (requred if field2 is given, else optional).
 * @param string $field3 the third field to check (optional).
 * @param string $value3 the value field3 must have (requred if field3 is given, else optional).
 */
function where_clause($field1='', $value1='', $field2='', $value2='', $field3='', $value3='') {
    if ($field1) {
        $select = "WHERE $field1 = '$value1'";
        if ($field2) {
            $select .= " AND $field2 = '$value2'";
            if ($field3) {
                $select .= " AND $field3 = '$value3'";
            }
        }
    } else {
        $select = '';
    }
    return $select;
}

/**
 * Prepares a SQL WHERE clause to select records where the given fields match some values.
 * Uses ? as placeholders for prepared statments
 * 
 * @param string $field1 the first field to check (optional).
 * @param string $field2 the second field to check (optional).
 * @param string $field3 the third field to check (optional).
 */
function where_clause_prepared($field1='', $field2='', $field3='') {
    $select = '';
    if (!empty($field1)) {
        $select = " WHERE $field1 = ? ";
        if (!empty($field2)) {
            $select .= " AND $field2 = ? ";
            if (!empty($field3)) {
                $select .= " AND $field3 = ? ";
            }
        }
    } 
    return $select;
}

/*
 * useful helper function to only push optional values into the array 
 * for prepared statements to avoid empty slots.
 * all parameters are optional.
 */
function where_values_prepared($value1=null, $value2=null, $value3=null) {
    $values = array();
    if (isset($value1)) {
        $values[] = $value1;
        if (isset($value2)) {
            $values[] = $value2;
            if (isset($value3)) {
                $values[] = $value3;
            }
        }
    }
    return $values;
}


/**
 * Checks for pg or mysql > 4
 * (lots of stuff we might want to use 
 * requires more complicated JOIN syntax
 * that mysql < 4 will get upset by)
 */

function check_db_compat() {
    global $CFG,$db;
    
    if ($CFG->dbtype == 'postgres7') {
        return true;
    }
    
    if (!$rs = $db->Execute("SELECT version();")) {
        return false;
    }

    if (intval(reset($rs->fields)) <= 3) {
        return false;
    }

    return true;
}

function &elgg_GetAssoc(&$recordset) {
	// adaptation of adodb's GetAssoc(), which in order to have the key as a named field in the data, 
	// you have to get data back in an array with both named and numeric keys, because GetAssoc for some 
	// unknown reason always strips out the first data element to use as the key. 0 comes before the first 
	// named field in the array, and the name is preserved.
	// with this function, we can just get all data back as pure associative arrays, and halve the memory used by db calls.
	// 
	// ps. left out all the stuff in GetAssoc we (afaik) don't use :)
	
	$results = array();
	while (!$recordset->EOF) {
		$keys = array_keys($recordset->fields);
		$sliced_array = array();

		foreach($keys as $key) {
			$sliced_array[$key] = $recordset->fields[$key];
		}
		
		$results[trim(reset($recordset->fields))] = $sliced_array;
		$recordset->MoveNext();
	}
	
	$ref =& $results; # workaround accelerator incompat with PHP 4.4 :(
	return $ref; 
}



?>