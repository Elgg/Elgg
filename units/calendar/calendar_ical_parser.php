<?php
/*
 * $Id: calendar_ical_parser.php,v 1.2 2005/12/02 16:01:10 siexchange Exp $
 *
 * File Description:
 * This file incudes functions for parsing iCal data files during
 * an import.
 *
 * It will be included by import_handler.php.
 *
 * The iCal specification is available online at:
 * http://www.ietf.org/rfc/rfc2445.txt
 *
 */

$filename = $parameter[0];
$run_result = parse_ical($filename);


// Parse the ical file and return the data hash.
function parse_ical ( $cal_file ) {
  global $tz, $errormsg;

  $ical_data = array();

  if (!$fd=@fopen($cal_file,"r")) {
    $errormsg .= "Can't read temporary file: $cal_file\n";
    exit();
  } else {

    // Read in contents of entire file first
    $data = '';
    $line = 0;
    while (!feof($fd) && empty( $error ) ) {
      $line++;
      $data .= fgets($fd, 4096);
    }
    fclose($fd);
    // Now fix folding.  According to RFC, lines can fold by having
    // a CRLF and then a single white space character.
    // We will allow it to be CRLF, CR or LF or any repeated sequence
    // so long as there is a single white space character next.
    //echo "Orig:<br><pre>$data</pre><br/><br/>\n";
    $data = preg_replace ( "/[\r\n]+ /", "", $data );
    $data = preg_replace ( "/[\r\n]+/", "\n", $data );
    //echo "Data:<br><pre>$data</pre><P>";

    // reflect the section where we are in the file:
    // VEVENT, VTODO, VJORNAL, VFREEBUSY, VTIMEZONE
    $state = "NONE";
    $substate = "none"; // reflect the sub section
    $subsubstate = ""; // reflect the sub-sub section
    $error = false;
    $line = 0;
    $event = '';

    $lines = explode ( "\n", $data );
    for ( $n = 0; $n < count ( $lines ) && ! $error; $n++ ) {
      $line++;
      $buff = trim($lines[$n]);

      // parser debugging code...
//      echo "line = $line <br />";
//      echo "state = $state <br />";
//      echo "substate = $substate <br />";
//      echo "subsubstate = $subsubstate <br />";
//      echo "buff = " . htmlspecialchars ( $buff ) . ", ". ($buff == "END:VEVENT") . ", length: " . strlen($buff) . "<br/><br/>\n";

      if ($state == "VEVENT") {
          if ( ! empty ( $subsubstate ) ) {
            if (preg_match("/^END:(.+)$/i", $buff, $match)) {
              if ( $match[1] == $subsubstate ) {
                $subsubstate = '';
              }
            } else if ( $subsubstate == "VALARM" && 
              preg_match ( "/TRIGGER:(.+)$/i", $buff, $match ) ) {
              // Example: TRIGGER;VALUE=DATE-TIME:19970317T133000Z
              //echo "Set reminder to $match[1]<br />";
              // reminder time is $match[1]
            }
          }
          else if (preg_match("/^BEGIN:(.+)$/i", $buff, $match)) {
            $subsubstate = $match[1];
          }
           // we suppose ":" is on the same line as property name, this can perhaps cause problems
          else if (preg_match("/^SUMMARY.*:(.+)$/i", $buff, $match)) {
              $substate = "summary";
              $event[$substate] = $match[1];
          } elseif (preg_match("/^LOCATION:(.+)$/i", $buff, $match)) {
              $substate = "location";
              $event[$substate] = $match[1];
          } elseif (preg_match("/^DESCRIPTION.*:(.+)$/i", $buff, $match)) {
              $substate = "description";
              $event[$substate] = $match[1];
          } elseif (preg_match("/^CLASS.*:(.*)$/i", $buff, $match)) {
              $substate = "class";
              $event[$substate] = $match[1];
          } elseif (preg_match("/^PRIORITY.*:(.*)$/i", $buff, $match)) {
              $substate = "priority";
              $event[$substate] = $match[1];
          } elseif (preg_match("/^DTSTART.*:\s*(\d+T\d+Z?)\s*$/i", $buff, $match)) {
              $substate = "dtstart";
              $event[$substate] = $match[1];
          } elseif (preg_match("/^DTSTART.*:\s*(\d+)\s*$/i", $buff, $match)) {
              $substate = "dtstart";
              $event[$substate] = $match[1];
          } elseif (preg_match("/^DTEND.*:\s*(.*)\s*$/i", $buff, $match)) {
              $substate = "dtend";
              $event[$substate] = $match[1];
          } elseif (preg_match("/^DURATION.*:(.+)\s*$/i", $buff, $match)) {
              $substate = "duration";
              $durH = $durM = 0;
              if ( preg_match ( "/PT.*([0-9]+)H/", $match[1], $submatch ) )
                $durH = $submatch[1];
              if ( preg_match ( "/PT.*([0-9]+)M/", $match[1], $submatch ) )
                $durM = $submatch[1];
              $event[$substate] = $durH * 60 + $durM;
          } elseif (preg_match("/^RRULE.*:(.+)$/i", $buff, $match)) {
              $substate = "rrule";
              $event[$substate] = $match[1];
          } elseif (preg_match("/^EXDATE.*:(.+)$/i", $buff, $match)) {
              $substate = "exdate";
              $event[$substate] = $match[1];
          } elseif (preg_match("/^CATEGORIES.*:(.+)$/i", $buff, $match)) {
              $substate = "categories";
              $event[$substate] = $match[1];
          } elseif (preg_match("/^UID.*:(.+)$/i", $buff, $match)) {
              $substate = "uid";
              $event[$substate] = $match[1];
          } elseif (preg_match("/^END:VEVENT$/i", $buff, $match)) {
              $state = "VCALENDAR";
              $substate = "none";
              $subsubstate = '';
              if ($tmp_data = format_ical($event)) $ical_data[] = $tmp_data;
              // clear out data for new event
              $event = '';

   // TODO: QUOTED-PRINTABLE descriptions

   // folded lines
          // TODO: This is not the best way to handle folded lines.
          // We should fix the folding before we parse...
          } elseif (preg_match("/^\s(\S.*)$/", $buff, $match)) {
              if ($substate != "none") {
                  $event[$substate] .= $match[1];
              } else {
                  $errormsg .= "iCal parse error on line $line:<br />$buff\n";
                  $error = true;
              }
          // For unsupported properties
   		  } else {
            $substate = "none";
          }
          
      } elseif ($state == "VCALENDAR") {
          if (preg_match("/^BEGIN:VEVENT/i", $buff)) {
            $state = "VEVENT";
          } elseif (preg_match("/^END:VCALENDAR/i", $buff)) {
            $state = "NONE";
          } else if (preg_match("/^BEGIN:VTIMEZONE/i", $buff)) {
            $state = "VTIMEZONE";
          } else if (preg_match("/^BEGIN:VALARM/i", $buff)) {
            $state = "VALARM";
          }
      } elseif ($state == "VTIMEZONE") {
        // We don't do much with timezone info yet...
        if (preg_match("/^END:VTIMEZONE$/i", $buff)) {
          $state = "VCALENDAR";
        }
      } elseif ($state == "NONE") {
         if (preg_match("/^BEGIN:VCALENDAR$/i", $buff))
           $state = "VCALENDAR";
      }
    } // End while
  }
	
  return $ical_data;
}

// Convert ical format (yyyymmddThhmmssZ) to epoch time
function icaldate_to_timestamp ($vdate, $plus_d = '0', $plus_m = '0',
  $plus_y = '0') {
  global $SERVER_TIMEZONE, $calUser;

  $y = substr($vdate, 0, 4) + $plus_y;
  $m = substr($vdate, 4, 2) + $plus_m;
  $d = substr($vdate, 6, 2) + $plus_d;
  $H = substr($vdate, 9, 2);
  $M = substr($vdate, 11, 2);
  $S = substr($vdate, 13, 2);
  $Z = substr($vdate, 15, 1);

  if ($Z == 'Z') {
    $TS = mktime($H,$M,$S,$m,$d,$y);
  } else {
    // Convert time to user's timezone
    // TODO try to parse VTIMEZONE stuff
//  $user_TIMEZONE = get_pref_setting ( $calUser, "TIMEZONE" );
    $user_TIMEZONE = ( ! empty ( $user_TIMEZONE ) ? $user_TIMEZONE : $SERVER_TIMEZONE );
	
	if($y < 1970)
		$y = 1970;
		
    $TS = mktime($H,$M,$S,$m,$d,$y);
//    $tz_offset = get_tz_offset ( $user_TIMEZONE, $TS );
//    $TS = $TS - ( $tz_offset[0] * 3600 );
 
  }

  return $TS;
}


// Put all ical data into import hash structure
function format_ical($event) {
  // Start and end time
  $fevent['StartTime'] = icaldate_to_timestamp($event['dtstart']);
  if ($fevent['StartTime'] == '-1') return false;
  if ( isset ( $event['dtend'] ) ) {
    $fevent['EndTime'] = icaldate_to_timestamp($event['dtend']);
  } else {
    if ( isset ( $event['duration'] ) ) {
      $fevent['EndTime'] = $fevent['StartTime'] + $event['duration'] * 60;
    } else {
      $fevent['EndTime'] = $fevent['StartTime'];
    }
  }
  
  $fevent['Location'] = (isset($event['location']) ? addslashes($event['location']) : "");
  if(strlen($fevent['Location']) > 50)
  	$fevent['Location'] = substr($fevent['Location'], 0, 47) . "...";

  // Calculate duration in minutes
  if ( isset ( $event['duration'] ) ) {
    $fevent['Duration'] = $event['duration'];
  } else if ( empty ( $fevent['Duration'] ) ) {
    $fevent['Duration'] = ($fevent['EndTime'] - $fevent['StartTime']) / 60;
  }
  if ( $fevent['Duration'] == '1440' ) {
    // All day event... nothing to do here :-)
  } else if ( preg_match ( "/\d\d\d\d\d\d\d\d$/",
    $event['dtstart'], $pmatch ) ) {
    // Untimed event
    $fevent['Duration'] = 0;
    $fevent['Untimed'] = 1;
  }
  if ( preg_match ( "/\d\d\d\d\d\d\d\d$/", $event['dtstart'], $pmatch ) && 
  		preg_match ( "/\d\d\d\d\d\d\d\d$/", $event['dtend'], $pmatch2 ) && 
  		$event['dtstart'] != $event['dtend'] ) {
    $startTime = icaldate_to_timestamp($event['dtstart']);
    $endTime = icaldate_to_timestamp($event['dtend']);
    // Not sure... should this be untimed or allday?
    if ( $endTime - $startTime == ( 3600 * 24 ) ) {
      // They used a DTEND set to the next day to say this is an all day
      // event.  We will call this an untimed event.
      $fevent['Duration'] = '0';
      $fevent['Untimed'] = 1;
    } else {
      // Event spans multiple days.  The EndTime actually represents
      // the first day the event does _not_ take place.  So,
      // we need to back up one day since WebCalendar end date is the
      // last day the event takes place.
      $fevent['Repeat']['Interval'] = '1'; // 1 = daily
      $fevent['Repeat']['Frequency'] = '1'; // 1 = every day
      $fevent['Duration'] = '0';
      $fevent['Untimed'] = 1;
      $fevent['Repeat']['EndTime'] = $endTime - ( 24 * 3600 );
    }
  }
  
  $date = getdate(time());
  	
  $fevent['Summary'] = addslashes(($event['summary'] != "" ? $event['summary'] : "Imported On: " . $date["month"] . " " . $date["mday"] . ", " . $date["year"]));
  
  if(strlen($fevent['Summary']) > 255)
  	$fevent['Summary'] = substr($fevent['Summary'], 0, 250) . "...";
  	
  if ( ! empty ( $event['description'] ) ) {
    $fevent['Description'] = addslashes($event['description']);
  } else {
    $fevent['Description'] = addslashes($event['summary']);
  }
  
  if ( ! empty ( $event['class'] ) ) {
    $fevent['Private'] = preg_match("/private|confidential/i", 
      $event['class']) ? '1' : '0';
  }
  $fevent['UID'] = $event['uid'];

  // Repeats
  //
  // Handle RRULE
  if ( ! empty ( $event['rrule'] ) ) {
    // first remove and EndTime that may have been calculated above
    unset ( $fevent['Repeat']['EndTime'] );
    //split into pieces
    //echo "RRULE line: $event[rrule] <br />\n";
    $RR = explode ( ";", $event['rrule'] );

    // create an associative array of key-value paris in $RR2[]
    for ( $i = 0; $i < count ( $RR ); $i++ ) {
      $ar = explode ( "=", $RR[$i] );
      $RR2[$ar[0]] = $ar[1];
    }

    for ( $i = 0; $i < count ( $RR ); $i++ ) {
      //echo "RR $i = $RR[$i] <br />";
      if ( preg_match ( "/^FREQ=(.+)$/i", $RR[$i], $match ) ) {
        if ( preg_match ( "/YEARLY/i", $match[1], $submatch ) ) {
          $fevent['Repeat']['Interval'] = 5;
        } else if ( preg_match ( "/MONTHLY/i", $match[1], $submatch ) ) {
          $fevent['Repeat']['Interval'] = 2;
        } else if ( preg_match ( "/WEEKLY/i", $match[1], $submatch ) ) {
          $fevent['Repeat']['Interval'] = 2;
        } else if ( preg_match ( "/DAILY/i", $match[1], $submatch ) ) {
          $fevent['Repeat']['Interval'] = 1;
        } else {
          // not supported :-(
          echo "Unsupported iCal FREQ value \"$match[1]\"<br />\n";
        }
      } else if ( preg_match ( "/^INTERVAL=(.+)$/i", $RR[$i], $match ) ) {
        $fevent['Repeat']['Frequency'] = $match[1];
      } else if ( preg_match ( "/^UNTIL=(.+)$/i", $RR[$i], $match ) ) {
        // specifies an end date
        $fevent['Repeat']['EndTime'] = icaldate_to_timestamp ( $match[1] );
      } else if ( preg_match ( "/^COUNT=(.+)$/i", $RR[$i], $match ) ) {
        // NOT YET SUPPORTED -- TODO
        //echo "Unsupported iCal COUNT value \"$RR[$i]\"<br />\n";
      } else if ( preg_match ( "/^BYSECOND=(.+)$/i", $RR[$i], $match ) ) {
        // NOT YET SUPPORTED -- TODO
        echo "Unsupported iCal BYSECOND value \"$RR[$i]\"<br />\n";
      } else if ( preg_match ( "/^BYMINUTE=(.+)$/i", $RR[$i], $match ) ) {
        // NOT YET SUPPORTED -- TODO
        echo "Unsupported iCal BYMINUTE value \"$RR[$i]\"<br />\n";
      } else if ( preg_match ( "/^BYHOUR=(.+)$/i", $RR[$i], $match ) ) {
        // NOT YET SUPPORTED -- TODO
        echo "Unsupported iCal BYHOUR value \"$RR[$i]\"<br />\n";
      } else if ( preg_match ( "/^BYMONTH=(.+)$/i", $RR[$i], $match ) ) {
        // this event repeats during the specified months
        $months = explode ( ",", $match[1] );
        if ( count ( $months ) == 1 ) {
          // Change this to a monthly event so we can support repeat by
          // day of month (if needed)
          // Frequency = 3 (by day), 4 (by date), 6 (by day reverse)
          if ( ! empty ( $RR2['BYDAY'] ) ) {
            if ( preg_match ( "/^-/", $RR2['BYDAY'], $junk ) )
              $fevent['Repeat']['Interval'] = 6; // monthly by day reverse
            else
              $fevent['Repeat']['Interval'] = 3; // monthly by day
            $fevent['Repeat']['Frequency'] = 12; // once every 12 months
          } else {
            // could convert this to monthly by date, but we will just
            // leave it as yearly.
            //$fevent['Repeat']['Interval'] = 4; // monthly by date
          }
        } else {
          // WebCalendar does not support this
          //echo "Unsupported iCal BYMONTH value \"$match[1]\"<br />\n";
        }
      } else if ( preg_match ( "/^BYDAY=(.+)$/i", $RR[$i], $match ) ) {
        $fevent['Repeat']['RepeatDays'] = rrule_repeat_days( $match[1] );
      } else if ( preg_match ( "/^BYMONTHDAY=(.+)$/i", $RR[$i], $match ) ) {
        // NOT YET SUPPORTED -- TODO
        //echo "Unsupported iCal BYMONTHDAY value \"$RR[$i]\"<br />\n";
      } else if ( preg_match ( "/^BYSETPOS=(.+)$/i", $RR[$i], $match ) ) {
        // NOT YET SUPPORTED -- TODO
        //echo "Unsupported iCal BYSETPOS value \"$RR[$i]\"<br />\n";
      }
    }

    // Repeating exceptions?
    if ( ! empty ( $event['exdate'] ) && $event['exdate']) {
      $fevent['Repeat']['Exceptions'] = array();
      $EX = explode(",", $event['exdate']);
      foreach ( $EX as $exdate ){
        $fevent['Repeat']['Exceptions'][] = icaldate_to_timestamp($exdate);
      }
    }
  } // end if rrule

  return $fevent;
}

// Figure out days of week for weekly repeats
function rrule_repeat_days($RA) {
  $RA =  explode(",",  $RA );
  $T = count( $RA ) ;
  $sun = $mon = $tue = $wed = $thu = $fri = $sat = 'n';
  for ($i = 0; $i < $T; $i++) {
    if ($RA[$i] == 'SU') {
      $sun = 'y';
    } elseif ($RA[$i] == 'MO') {
      $mon = 'y';
    } elseif ($RA[$i] == 'TU') {
      $tue = 'y';
    } elseif ($RA[$i] == 'WE') {
      $wed = 'y';
    } elseif ($RA[$i] == 'TH') {
      $thu = 'y';
    } elseif ($RA[$i] == 'FR') {
      $fri = 'y';
    } elseif ($RA[$i] == 'SA') {
      $sat = 'y';
    }
  }
  return $sun.$mon.$tue.$wed.$thu.$fri.$sat;
}


// Calculate repeating ending time
function rrule_endtime($int,$freq,$start,$end) {

  // if # then we have to add the difference to the start time
  if (preg_match("/^#(.+)$/i", $end, $M)) {
    $T = $M[1] * $freq;
    $plus_d = $plus_m = $plus_y = '0';
    if ($int == '1') {
      $plus_d = $T;
    } elseif ($int == '2') {
      $plus_d = $T * 7;
    } elseif ($int == '3') {
      $plus_m = $T;
    } elseif ($int == '4') {
      $plus_m = $T;
    } elseif ($int == '5') {
      $plus_y = $T;
    } elseif ($int == '6') {
      $plus_m = $T;
    }
    $endtime = icaldate_to_timestamp($start,$plus_d,$plus_m,$plus_y);

  // if we have the enddate
  } else {
    $endtime = icaldate_to_timestamp($end);
  }
  return $endtime;
}

?>