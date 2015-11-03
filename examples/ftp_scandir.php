<?php


chdir (dirname (__FILE__));


include_once  '../lib/datapipe.php';


# NOTE: FTP login credentials taken from Kellogg (task 4)


$commands = <<<'END'

- $ftp_svr: ftp.brunswickboatgroup.com
- $ftp_dir: /vlnsinvent

- ftp_init:
    svr: $ftp_svr 
    usr: vbustersmarine
    pwd: Franco19
       
- ftp_scandir:
    dir: $ftp_dir

END;


process_commands ($commands);


?>