<?php

$dir = dirname (__FILE__);
chdir ($dir);

$json_file = "$dir/files/suredone_search.json";

include_once  '../lib/datapipe.php';


$commands = <<<'END'

- create_data_from_json_file:
    file:             $json_file
    data_structure:   list_of_columns
    fields:           ['guid','sku','price','stock','brand']
    save_fields:      ['sku','price']
    
- dump_yaml
    
END;


process_commands ($commands);


?>