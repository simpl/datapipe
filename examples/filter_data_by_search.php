<?php


chdir (dirname (__FILE__));


include_once  '../lib/datapipe.php';


$masterdata_csv_map = <<<'END'

PartNoID: guid
Image1: media1
Image2: media2
Image3: media3
WebDescription: description
WebLongDescription: longdescription
LongDescription: longdescription
UPC: upc
UOM: uom
VendorName: vendorname
LNSPartNo: lnspartnumber
DMPPartNo: dmppartnumber
RelativePath: relativepath
AS400Description: as400description
Level4Name: level4name
Level6Name: level6name
Attribute1Value: attribute1
Attribute2Value: attribute2
Attribute3Value: attribute3
Attribute4Value: attribute4
Attribute5Value: attribute5
Attribute6Value: attribute6
WebTitle: webtitle
WebMetaTagsKeyword: webmetatagskeyword
WebAltTag: webalttag
WebLinkingDocs: weblinkingdocs
LNSuse2: lnsuse2
SuggestedPrice: price

END;


$commands = <<<'END'

- create_data_from_csv_file:
    file:                   ../examples/files/MasterDataKMS.sample.csv
    action:                 add
    map:                    $masterdata_csv_map
    media_dir:              /tmp/kellogg/images
    media_prefix_field:     RelativePath
    media_prefix_replace:   [['\','/']]
    
- filter_data:
    search_max_pages:       1
    search_query:           "mediacount:=0"
    join_mawk:              ["if @@stock = 0 { @price = @@price } else { DELETE }"]
    
- dump_yaml
    
END;


process_commands ($commands);


?>