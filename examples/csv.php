<?php


chdir (dirname (__FILE__));


include_once '../lib/datapipe.php';


$commands = <<<'END'

- $file: ../examples/files/test_file.csv
- $mapping:
    vendorUName: Weight

- csv_read_lines:
    path: $file

- csv_debug_mapping:

    
- echo: Successfully processed csv file
    
END;


process_commands ($commands);


?>