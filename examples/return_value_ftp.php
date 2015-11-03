<?php


chdir (dirname (__FILE__));


include_once  '../lib/datapipe.php';


# NOTE: FTP login credentials taken from the Wizard of Math plugin

$commands = <<<'END'

- $ftp_svr: ftp.brunswickboatgroup.com

- return_value:
    svr: $ftp_svr 
    usr: vbustersmarine
    pwd: Franco19

- ftp_init
    
- echo: Sucessfully logged into FTP server '$ftp_svr'
    
END;


process_commands ($commands);


?>