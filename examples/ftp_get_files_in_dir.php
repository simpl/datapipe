<?php


chdir (dirname (__FILE__));


include_once  '../lib/datapipe.php';


# NOTE: FTP login credentials taken from Kellogg (task 4)
# NOTE: When created, there were two files in the folder both of which were of size 0


$commands = <<<'END'

- $ftp_svr:   ftp.brunswickboatgroup.com
- $ftp_dir:   /DLR90003/ATTACHMENTS
- $local_dir: /tmp/maws_ftp_get_files

- ftp_init:
    svr: $ftp_svr 
    usr: vbustersmarine
    pwd: Franco19
       
- ftp_get_files_in_dir:
    dir: $ftp_dir
    local_dir: $local_dir
    
END;


process_commands ($commands);


?>