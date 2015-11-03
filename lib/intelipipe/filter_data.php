<?php

/*
function  filter_data ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'filter_data';
  
  
  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'type');
  

  # get filter type opt
  
  $type = get_opt_config_value ($prefix, $opts, 'type', 'process');
   
  if (!check_opt_set_type ($cmd, $type, 'type', 'filter_data_type'))
    return  false;
    
    
  # get secondary data types if necessary
  
  $secondary_data_source = null;
  
  switch ($type) {
  
    case  'process' :
    
    
      debug_echo ($cmd, "filter uses a process on secondary data");
    
    
      # get secondary data source opt
      
      $secondary_data_source = get_opt_config_value ($prefix, $opts, 'secondary_data_source', 'search');
      
      if (!check_opt_set_type ($cmd, $secondary_data_source, 'secondary_data_source', 'data_source_type'))
        return  false;      
      
      break;
  }
  
  
  # get secondary data if necessary
  
  switch ($secondary_data_source) {
  
    case  'file' :
    
      # get secondary data file opt
      
      $secondary_data_file = get_opt_config_value ($prefix, $opts, 'secondary_data_file');
      
      if (!check_opt_set_type ($cmd, $secondary_data_file, 'secondary_data_file', 'string'))
        return  false;
  
      if (!check_opt_file_exists ($cmd, $secondary_data_file, 'secondary_data_file'))
        return  false;
  
  
      # message
      
      debug_echo ($cmd, "getting secondary data from file : $secondary_data_file");
      
      
      # load YAML / JSON data from file
  
      $secondary_data_yaml = file_get_contents ($secondary_data_file);
      
      $secondary_data = @yaml_decode ($secondary_data_yaml);
      
      if (!is_array ($secondary_data))
        return  error ($cmd, "data in secondary data file is not valid YAML : $secondary_data_file");
  
      break;
      
  
    case  'pass' :
      
      debug_echo ($cmd, "getting secondary data from passed data");
      
      $secondary_data_opts = $opts;
      break;
    
    
    case  'search' :
    
      # TODO: move out of here
    
      debug_echo ($cmd, "getting secondary data from SureDone search");
    
      $secondary_data_opts = suredone_search ($opts, null, $cmd, true);
      
      if (!$secondary_data_opts)
        return  false;
    
      $opts = merge_opts_for_output ($secondary_data_opts, $opts);
      break;
  }
  
  
  # get secondary data from opts
  
  if (@$secondary_data_opts !== null) {
  
    $secondary_data = get_opt_config_value ($prefix, $opts, 'secondary_data');
    
    if (!check_opt_set_type ($cmd, $secondary_data, 'secondary_data', 'array'))
      return  false;
  }
  
  
  # add secondary data to opts
  
  if (@$secondary_data) {

    
    # get secondary data type opt
    
    $secondary_data_type = get_opt_config_value ($prefix, $opts, 'secondary_data_type', 'array_of_indexes');
    
    if (!check_opt_set_type ($cmd, $secondary_data_type, 'secondary_data_type', 'data_type'))
      return  false; 

    
    # get secondary fields opt
    
    $secondary_fields = get_opt_config_value ($prefix, $opts, 'secondary_fields');
    
    if (!check_opt_set_type ($cmd, $secondary_fields, 'secondary_fields', 'array_of_strings'))
      return  false; 

      
    # store the 
      
    $opts['secondary_data']       = $secondary_data;
    $opts['secondary_data_type']  = $secondary_data_type;
    $opts['secondary_fields']     = $secondary_fields;
  }
    
    
  # filter data based on type
    
  switch ($type) {
  
    case  'process' :
    
      return  process_data ($opts, null, $cmd, true);
  }
}
*/

?>