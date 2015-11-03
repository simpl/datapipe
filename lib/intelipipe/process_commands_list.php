<?php


## SET LIMIT / OFFSET ##

# These are used when loading data. When doing dev, the script automatically pass incremented
# values to the script, so as to handle this


$limit  = (@$limit > 0 ? $limit : (int) @$argv[1]);
$offset = (@$offset > 0 ? $offset : (int) @$argv[2]);


## PROCESS COMMANDS ##

if (@$plugin_name) {
  process_commands_list ($commands, $plugin_name);
}


?>