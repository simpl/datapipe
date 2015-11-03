<?php


chdir (dirname (__FILE__));


include_once  '../lib/datapipe.php';


$map = <<<'END'

Image1: media1
Image3: media2

END;


$commands = <<<'END'

- create_data_from_csv_file:
    file:                     ../examples/files/MasterDataKMS.sample.csv
    action:                   add
    map:                      $map
    media_dir:                /tmp/kellogg/images
    media_prefix_field:       RelativePath
    media_prefix_replace:     [['\','/']]
    
- create_bulk_upload_files:
    output_dir: /tmp/kellog/bulk-uploads
    
- dump_yaml
    
END;


process_commands ($commands);


?>