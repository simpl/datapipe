<?php


chdir (dirname (__FILE__));


include_once  '../lib/datapipe.php';


$commands = <<<'END'

- suredone_search:
    max_pages:      1
    query:         "mediacount:=0"

- dump_yaml
    
END;


process_commands ($commands);


?>