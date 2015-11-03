<?php


chdir (dirname (__FILE__));


include_once  '../lib/datapipe.php';


$commands = <<<'END'

- echo: Test echo
- warning: Test warning
- notice: Test notice

- echo: Turning debugging on
- $debug: true

- debug_echo: Test debug echo
- debug_warning: Test debug warning
- debug_notice: Test debug notice

- echo: Turning debugging off (check source code to see that debug message commands are ignored)
- $debug: false

- debug_echo: Test debug echo
- debug_warning: Test debug warning
- debug_notice: Test debug notice

- echo: Setting var $$var to value 'value'
- $var: value
- echo: Test echo var value [$$var = ${var}]

- echo: Turning debugging back on
- $debug: true

- debug_error: Test debug error

END;


process_commands ($commands);


?>