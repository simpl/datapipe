<?php


function  format_color ($col) {

  return  ucwords (
            strtolower (
              str_replace ( "/" , " / " , 
                str_replace (" ", "", $col)
              )
            )
          );
}
  

function  format_date_year_first ($date) {

  return  date ("Y M d", strtotime ($date));
}

  
function  format_date_year_last ($date) {

  return  date ("d M Y", strtotime ($date));
}


function  format_filesize ($bytes, $decimals = 2) {

    $size = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
    
    $factor = floor ((strlen ($bytes) - 1) / 3);
    
    return  sprintf ("%.{$decimals}f", $bytes / pow (1024, $factor)) . @$size[$factor];
}


?>