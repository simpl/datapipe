<?php


chdir (dirname (__FILE__));


include_once  '../lib/datapipe.php';


# NOTE: FTP login credentials taken from the Wizard of Math plugin


$commands= <<<'END'

- $ftp_svr: ftp.ercontent.com

- ftp_init:
    svr: $ftp_svr 
    usr: EROPTIONA
    pwd: @eR2oPtA251

    
END;


process_commands ($commands);


?>