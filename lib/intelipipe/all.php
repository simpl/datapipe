<?php

chdir (dirname (__FILE__));


include_once  'default_config.php';   # should come first

include_once  'messages.php';         # needs to come before all functions
include_once  'commands.php';
include_once  'opts.php';
include_once  'values.php';
include_once  'array.php';

include_once  'json.php';
include_once  'lib/spyc.php';
include_once  'yaml.spyc.php';
include_once  'xml.php';

include_once  'filesystem.php';
include_once  'curl.php';
include_once  'curl_ftp.php';
include_once  'curl_http.php';
include_once  'curl_imap.php';
#include_once  'ftp.php';
#include_once  'http.php';

include_once  'load_data.php';
include_once  'build_indexes.php';
include_once  'modify_fields.php';
include_once  'modify_rows.php';
include_once  'process_data.php';
include_once  'convert_data.php';
include_once  'merge_data.php';
include_once  'clean_data.php';
include_once  'search_replace_data.php';
include_once  'result.php';

include_once  'csv.php';
include_once  'zip.php';

include_once  'process_commands_list.php';


?>
