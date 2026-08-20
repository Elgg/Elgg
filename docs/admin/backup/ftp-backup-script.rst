FTP backup script
=================

Here is an automated script for backing up an Elgg installation.

.. code-block:: perl

   #!/usr/bin/perl -w
   
   # FTP Backup
   
   use Net::FTP;
   
   # DELETE BACKUP AFTER FTP UPLOAD (0 = no, 1 = yes)
   $delete_backup = 1;
   
   # ENTER THE PATH TO THE DIRECTORY YOU WANT TO BACKUP, NO TRAILING SLASH
   $directory_to_backup = '/home/userx/public_html';
   $directory_to_backup2 = '/home/userx/elggdata';
   
   # ENTER THE PATH TO THE DIRECTORY YOU WISH TO SAVE THE BACKUP FILE TO, NO TRAILING SLASH
   $backup_dest_dir = '/home/userx/sitebackups';
   
   # BACKUP FILE NAME OPTIONS
   ($a,$d,$d,$day,$month,$yearoffset,$r,$u,$o) = localtime();
   $year = 1900 + $yearoffset;
   $site_backup_file = "$backup_dest_dir/site_backup-$day-$month-$year.tar.gz";
   $full_backup_file = "$backup_dest_dir/full_site_backup-$day-$month-$year.tar.gz";
   
   # MariaDB BACKUP PARAMETERS
   $dbhost = 'localhost';
   $dbuser = 'userx_elgg';
   $dbpwd = 'dbpassword';
   $db_backup_file_elgg = "$backup_dest_dir/db_elgg-$day-$month-$year.sql.gz";
   
   # ENTER DATABASE NAME
   $database_names_elgg = 'userx_elgg';
   
   # FTP PARAMETERS
   $ftp_backup = 1;
   $ftp_host = "FTP HOSTNAME/IP";
   $ftp_user = "ftpuser";
   $ftp_pwd = "ftppassword";
   $ftp_dir = "/";
    
   # SYSTEM COMMANDS
   $cmd_dump = '/usr/bin/mariadb-dump';
   $cmd_gzip = '/usr/bin/gzip';
   
   # CURRENT DATE / TIME
   ($a,$d,$d,$day,$month,$yearoffset,$r,$u,$o) = localtime();
   $year = 1900 + $yearoffset;
   
   # BACKUP FILES
   $syscmd = "tar --exclude $backup_dest_dir" . "/* -czf $site_backup_file $directory_to_backup $directory_to_backup2";
   
   # elgg DATABASE BACKUP
   system($syscmd);
   $syscmd = "$cmd_dump --host=$dbhost --user=$dbuser --password=$dbpwd --add-drop-table --databases $database_names_elgg -c -l | $cmd_gzip > $db_backup_file_elgg";
   
   system($syscmd);
   
   # CREATING FULL SITE BACKUP FILE
   $syscmd = "tar -czf $full_backup_file $db_backup_file_elgg $site_backup_file";
   system($syscmd);
   
   # DELETING SITE AND DATABASE BACKUP FILES
   unlink($db_backup_file_elgg);
   unlink($site_backup_file);
   
   # UPLOADING FULL SITE BACKUP TO REMOTE FTP SERVER
   if($ftp_backup == 1)
   {
      my $ftp = Net::FTP->new($ftp_host, Debug => 0)
         or die "Cannot connect to server: $@";
      
      $ftp->login($ftp_user, $ftp_pwd)
         or die "Cannot login ", $ftp->message;
      
      $ftp->cwd($ftp_dir)
         or die "Can't CWD to remote FTP directory ", $ftp->message;
      
      $ftp->binary();
      
      $ftp->put($full_backup_file)
         or warn "Upload failed ", $ftp->message;
      
      $ftp->quit();
   }
   
   # DELETING FULL SITE BACKUP
   if($delete_backup = 1)
   {
      unlink($full_backup_file);
   }
