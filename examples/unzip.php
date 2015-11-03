<?php


chdir (dirname (__FILE__));


include_once  '../lib/datapipe.php';


$zip_file = __DIR__ . "/files/zip_file_for_test.zip";


$commands = <<<'END'

- unzip:
    file:             $zip_file
    extract_to_dir:   /tmp/unzip

END;


process_commands ($commands);


?>