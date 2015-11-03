<?php


function  clean_data ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix
  
  $prefix = 'clean_data';
  
  
  # merge opts
  
  $opts = merge_opts ($opts, $pipe);
  
    
  # get data opt

  $data = get_opt_config_value ($prefix, $opts, 'data');
  
  if (!check_opt_set_type ($cmd, $data, 'data', 'array'))
    return  false;
  

  # get fields opt

  $fields = get_opt_config_value ($prefix, $opts, 'fields');
  
  if (!check_opt_set_type ($cmd, $fields, 'fields', 'array_of_strings'))
    return  false;
    
    
    
  # get old value opt

  $old_value = get_opt_config_value ($prefix, $opts, 'old_value');
  
    
  # get new value opt

  $new_value = get_opt_config_value ($prefix, $opts, 'new_value');
    
    
  # set up counts
    
  $data_count = count ($data);
  $field_count = count ($fields);
  
  
  # messages
  
  debug_echo ($cmd, "cleaning data (old value : $old_value, new value : $new_value)");

  
  
  # loop through data
  
  for ($i=0; $i<$data_count; $i++) {
  
    unset ($line);
  
    $line = &$data[$i];
    
    for ($j=0; $j<$field_count; $j++) {
    
      if ($line[$j] === $old_value)
        $line[$j] = $new_value;
    }
  }
  
  
  # set up result
  
  $res = array (
    'data'    => $data,
    'fields'  => $fields,
  );
  
  return  $res;
}


?>