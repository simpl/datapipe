<?php

$dir = dirname (__FILE__);
chdir ($dir);

$col_file = "$dir/files/DSEDIAL-example";

include_once  '../lib/datapipe.php';


$commands = <<<'END'

- create_data_from_column_file:
    file:         $col_file
    fields:       ['code','name','code2','description','a']
    save_fields:  ['code','name','code2','description']
    splits:       [23,53,76,106,107]
    
- dump_yaml

    
END;


process_commands ($commands);


?>