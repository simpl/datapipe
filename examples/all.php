<?php

$dir = dirname (__FILE__);

chdir ($dir);


$ignore_files = array (
  '.',
  '..',
  'all.php',                    // will create loop
  'ftp.php',                    // credentials don't work
  
  'csv.php',                    // dev
  'zip.php',                    // dev
);


$files = scandir ($dir);


foreach ($files as $file) {

  $GLOBALS['error'] = $GLOBALS['error_msg'] = null;
  $GLOBALS['debug'] = true;

  if (in_array ($file, $ignore_files))
    continue;
    
  if (is_dir ("$dir/$file"))
    continue;
    
  $bar = str_repeat ('=', strlen ($file) + 2) . "\n";
    
  echo $bar, " $file\n", $bar;
    
  chdir ($dir);
    
  include "$dir/$file";
}


?>
