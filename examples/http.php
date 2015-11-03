<?php


chdir (dirname (__FILE__));


include_once  '../lib/datapipe.php';


$commands= <<<'END'

- http_request: 
    url: www.google.com
    save_request_head_to_file:    /tmp/google-request-head
    save_head_to_file:            /tmp/google-response-head
    save_body_to_file:            /tmp/google-response-body
    download_to_file:             /tmp/google-response
    use_downloaded_file:          false
    backup_old_downloaded_files:  false
    debug:                        true
    
END;


process_commands ($commands);


?>