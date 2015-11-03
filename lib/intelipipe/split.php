<?php


function  split_file_on_cols ($opts, $pipe) {


  # set prefix

  $prefix = 'split_file_on_cols';


  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'file');
  

  # get file to split

  $file = get_opt ($prefix, $opts, 'file');

  if (!$file)
    return  error ("No file specified to split");
    
    
  # get split
  
  $splits = get_opt ($prefix, $opts, 'split');
  
  if (!$splits)
    return  error ("No columns split specified to split file '$file'");
    
  
  # check that the split is valid (i.e. an array of integers)
    
  if (!is_array ($splits))
    return  error ("File split on columns does not have a split that is an array");
    
  for ($i=0; $i<count($splits); $i++) {
  
    $val = $splits[$i];
    
    if (!is_integer ($val)) {
    
      $i++;
      $type = gettype ($val);
      
      return  error ("Value at position '$i' of split for file '$file' is not an integer (is of type '$type')"); 
    }
  }
    
    
  # get trim (i.e. whether the values should have leading and trailing whitespace removed)
  
  $trim = get_opt_config_value ($prefix, $opts, 'trim', true);
  
  
  
  # check file exists
  
  if (!is_file ($file))
    return  error ("File '$file' does not exist");
  
  
  # get lines
  
  $lines = file ($file);
  
  
  # loop over lines to split the file
  
  $data = array ();
  $total_splits = count ($splits);
  $max_split_index = $total_splits - 1;
  
  foreach ($lines as $line) {
  
    $line_data = array ();
  
    for ($i=0; $i<$total_splits; $i++) {
    
      $min = $splits[$i];
    
      if ($i == 0) {
      
        $min = 0;
        $len = $splits[0];
      
      } else {
      
        $min = $splits[$i-1];
        $len = ($splits[$i] - $min);
      }
    
      $val = substr ($line, $min, $len);
      
      if ($trim)
        $val = trim ($val);
      
      $line_data[] = $val;
    }
    
    $data[] = $line_data;
  }
  
  
  # return data depending on whether called internally or not
  
  if ($pipe === false)
    return  $data;
  
  $res = array (
    'data' => $data,
    'lines' => count ($lines),
  );
  
  return  $res;
}


?>