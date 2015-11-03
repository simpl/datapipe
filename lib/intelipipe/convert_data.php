<?php


function  convert_data_to_objects ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix
  
  $prefix = 'convert_data_to_objects';
  
  
  # merge opts
  
  $opts = merge_opts ($opts, $pipe);


  # get fields opt

  $fields = get_opt ($prefix, $opts, 'fields');

  if (!check_opt_set_type ($cmd, $fields, 'fields', 'array_of_strings'))
    return  false;
    
    
  # get data opt
  
  $data = get_opt ($prefix, $opts, 'data');
  
  if (!check_opt_set_type ($cmd, $data, 'data', 'array_of_arrays'))
    return  false;

    
  # initiate response
  
  $field_count = count ($fields);
  $data_objects = array ();
  
  
  # loop over data
  
  debug_echo ($cmd, "converting data to objects");
  
  foreach ($data as $line) {
  
    $data_object = array ();
    
    for ($i=0; $i<$field_count; $i++) {
    
      $data_object[$fields[$i]] = $line[$i];
    }
    
    $data_objects[] = $data_object;
  }
  
  
  # build res
  
  $res = array (
  
    'data_objects' => $data_objects,
  );
    
  
  return  $res;
}


?>