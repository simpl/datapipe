<?php

$dir = dirname (__FILE__);
chdir ($dir);

$json_file = "$dir/files/ss-inventory.json";

include_once  '../lib/datapipe.php';


$commands = <<<'END'

- create_data_from_json_file:
    file:                 $json_file
    data_structure:       list_of_objects

- modify_rows:
    limit:                20
    offset:               80
    
- dump_yaml
    
END;


process_commands ($commands);


?>