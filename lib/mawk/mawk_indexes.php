<?php


# TODO: change to using build_field_indexes
/*
function  mawk_get_indexes ($keys, $fields_arr, $cmd = __FUNCTION__) {


  # check for empty keys
  
  if (!$keys)
    return  array ();


  # turn string key into array of one

  $single_line = false;
  
  if (is_string ($keys)) {
    $single_line = true;
    $keys = array ($keys);
  }
  
  
  # invert arrays
  
  $inv_fields_arr = array ();
  
  foreach ($fields_arr as $i => $fields) {
  
    $inv_fields_arr[] = array_flip ($fields);
  }

  
  # setup indexes
  
  $indexes = array ();

  for ($i=0; $i<count($keys); $i++) {
    
    $key = $keys[$i];
    $key_indexes = array ();
    $fields_count = count ($fields_arr);
    
    for ($j=0; $j<$fields_count; $j++) {
          
      $index = @$inv_fields_arr[$j][$key];
      
      if ($index === null) {
      
        $source_index = $j + 1;
      echo debug_backtrace();
        return  error ($cmd, "mawk source data $i does not include primary key '$key'");
      }
      
      $key_indexes[] = $index;
    }
    
    $indexes[] = $key_indexes;
  }

  
  # return single line or array of lines

  if ($single_line)
    return  $indexes[0];

  return  $indexes;
}
*/


function  mawk_add_primary_index ($field_to_index, &$indexes_arr, $fields_arr, $cmd = __FUNCTION__) {


  # check for empty keys
  
  if (!$field_to_index)
    return  true;
 
  
  # invert arrays
  
  $inv_fields_arr = array ();
  
  foreach ($fields_arr as $i => $fields) {
  
    $inv_fields_arr[] = array_flip ($fields);
  }

  
  # setup indexes
     
  $fields_count = count ($fields_arr);
  
  for ($i=0; $i<$fields_count; $i++) {
        
    $index = @$inv_fields_arr[$i][$field_to_index];
    
    if ($index === null) {
    
      $source_index = $i + 1;
      
      #echo debug_backtrace();
      
      return  error ($cmd, "mawk source data $source_index does not include field '$field_to_index'");
    }
    
    if ($i != 0)
      $indexes_arr[$i][$index] = true;
    
    $indexes_arr[$i][-1] = $index;
  }

  return  true;
}


function  mawk_build_indexes (&$fields_arr, &$data_arr, &$indexes_arr, $cmd = __FUNCTION__, $debug = false) {


  # initiate indexes
  
  $full_indexes = array ();

  
  # loop over all indexes to define
  
  $sources_count = count ($fields_arr);

  for ($i=0; $i<$sources_count; $i++) {
  
  
    # unset old values
  
    unset ($fields);
    unset ($data);
    unset ($indexes);
    
    
    # check to see if there are any indexes
    
    $indexes = &$indexes_arr[$i];
  
    if (!$indexes)
      continue;
  
  
    # initiate for the source
    
    $fields = &$fields_arr[$i];
    $fields_count = count ($fields);
    $data = &$data_arr [$i];
    $data_count = count ($data);
        
    $source_no = $i + 1;
    $source_indexes = array ();
  
  
    # loop over all field indexes
  
    for ($j=0; $j<$fields_count; $j++) {
    
    
      # check which fields should be indexed
    
      if (@$indexes[$j]) {
      
        
        # display message

        $field = $fields[$j];
        
        debug_echo ($cmd, "creating index on field '$field' of source $source_no");
      
      
        # loop through all the data to define the index
        
        $index = array ();
        
        for ($k=0; $k<$data_count; $k++) {
        
        
          # get line data
        
          $line = &$data[$k];

          
          # get the key
          
          $key = $line[$j];

          
          # ignore keys that are null
          
          if ($key === null)
            continue;
            
          if (isset ($index[$key])) {
          
            if ($debug) {
          
              $line_no = $k + 1;
              
              debug_echo ($cmd, "ignoring duplicate index key ($key) on line $line_no");
            }
            
            continue;
          }
          
          
          # set the index value
          
          $index[$key] = &$line;
        }
        
        
        # add the index to the list
        
        $source_indexes[$j] = $index;
      }
    }
  
  
    # add the sources index to the indexes
    
    $full_indexes[] = $source_indexes;
  }

  
  return  $full_indexes;
}


?>