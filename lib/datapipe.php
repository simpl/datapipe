<?php


chdir (dirname (__FILE__));


#### DIRECTORIES TO INCLUDE ALL FILES ####

$dirs = array (
  'mawk',
  'util'
);


#### INCLUDE ALL FILES IN THOSE DIRECTORIES ####

foreach ($dirs as $dir) {

  $scan = scandir ($dir);

  foreach ($scan as $file) {
  
    if (substr ($file, -4) != '.php')
      continue;
  
    $full_file = "$dir/$file";

    include_once  $full_file;
  }
}


#### INCLUDE INTELIPIPE LAST ####

include_once  'intelipipe/all.php';


?>
