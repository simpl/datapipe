<?php


chdir (dirname (__FILE__));


include_once  '../lib/datapipe.php';


$commands = <<<'END'

- $array:
    one: ONE
    two: TWO
    three: THREE
    
- $list:
    - one
    - two
    - three

- dump_var: $array
- dump_var: $list
    
END;


process_commands ($commands);


?>