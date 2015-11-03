<?php


function  process_data ($opts, $pipe, $cmd = __FUNCTION__, $opt_prefix = false) {

  
  # set prefix
  
  $prefix = 'process_data';
  
  
  # merge opts
  
  $opts = merge_opts ($opts, $pipe);
  
  
  # adjust opt prefix

  $cmd = adjust_opt_prefix ($cmd, $opts, $opt_prefix, 'edit');
  
  
  # get debug opt
  
  $debug = get_opt_config_value ($prefix, $opts, 'debug', false);
  
  if (!check_opt_set_type ($cmd, $debug, 'debug', 'boolean'))
    return  false;
  
  
  # get type opt

  $join_type = get_opt_config_value ($prefix, $opts, 'join_type', 'outer');
  
  if (!check_opt_set_type ($cmd, $join_type, 'type', 'data_join_type'))
    return  false;


  # get sources opt
  
  $sources = get_opt ($prefix, $opts, 'sources');
  
  if (!check_opt_if_set_type ($cmd, $sources, 'sources', 'array'))
    return  false;
    
  if (!$sources) {
  
    $source = build_source_data ($prefix, $opts, $cmd);
    
    if (!$source)
      return  error ($cmd, "sources opt is not set and the data and fields opts are not set either, so can't be used to create one");
  
    $sources = array ($source);
  }
    
  
  # get sources values
    
  foreach ($sources as $i => $val) {
  
    $sources[$i] = value ($val);
  }
  
    
  # get create_indexes opt
  
  $create_indexes = get_opt ($prefix, $opts, 'create_indexes');
  
  if (!check_opt_if_set_type ($cmd, $create_indexes, 'create_indexes', 'array_of_strings'))
    return  false;
    
  
  # get join field opt

  $primary_key = get_opt_config_value ($prefix, $opts, 'primary_key', 'guid');
  
  if (!check_opt_set_type ($cmd, $primary_key, 'primary_key', 'string'))
    return  false;
  
  
  # get mawk rules opt

  $mawk = get_opt_config_value ($prefix, $opts, 'mawk');
  
  if (!check_opt_set_type ($cmd, $mawk, 'mawk', 'string'))
    return  false;  

   
  # get update frequency opt
   
  $update_frequency = get_opt_config_value ($prefix, $opts, 'update_frequency', 0);
  
  if (!check_opt_set_type ($cmd, $update_frequency, 'update_frequency', 'integer'))
    return  false;  
    

  # create fields and data arrays
  
  $fields_arr = array ();
  $data_arr = array ();
  
  /*
  for ($i=0; $i<count ($sources); $i++) {
  
    $source_no = $i+1;
  
    $fields = $sources[$i]['fields'];
    
    if (!$fields)
      return  error ($cmd, "no fields defined for source $source_no");
  
    $fields_arr[] = $fields;
    
    if ($i == 0) {
    
      $data_arr[] = $sources[$i]['data'];
    
    } else {
    
      $data = $sources[$i]['indexes'][$primary_key];
      
      if ($data === null) {
      
        return  error ($cmd, "index on primary key '$primary_key' does not exis for source $source_no");   # TODO: auto-create
      }
      
      $data_arr[] = $data;
    }
  }*/
  
  $sources_count = count ($sources);
  
  for ($i=0; $i<$sources_count; $i++) {
  
    $source_no = $i+1;
  
  
    # add fields
  
    $fields = $sources[$i]['fields'];
    
    if (!$fields)
      return  error ($cmd, "no fields defined for source $source_no");
  
    if ($sources_count > 1) {
  
      if (!in_array ($primary_key, $fields))
        return  error ($cmd, "primary key '$primary_key' not defined for source $source_no");
    }
    
    $fields_arr[] = $fields;
    
    
    # add data
    
    $data = $sources[$i]['data'];
    
    if (!$data)
      return  error ($cmd, "no data defined for source $source_no");
  
    $data_arr[] = $data;
  }
  

  # display info about the join, if there is one
  
  if (count ($fields_arr) > 1)
    debug_echo ($cmd, "performing $join_type join on primary key / field '$primary_key'");
  
  for ($i=1; $i<=count ($fields_arr); $i++) {
  
    debug_echo ($cmd, "fields for data source $i :");
    debug_dump_list ($fields_arr[$i-1], true);
  }


  # parse the line rules
  
  $parsed_mawk = mawk_parse_code ($mawk, $fields_arr, $cmd);
  
  if (is_int ($parsed_mawk))
    return  error ($cmd, "code line $i of the mawk has an invalid syntax");

  $parsed_mawk_code = $parsed_mawk['code'];
    
  
  # set up indexes
  
  $indexes_arr = $parsed_mawk['indexes'];
  
  if (count ($fields_arr) > 1) {
  
    $r = mawk_add_primary_index ($primary_key, $indexes_arr, $fields_arr, $cmd);
    
    if ($r === false)
      return  false;
  }
  
  
  # display info about the line rules

  debug_echo ($cmd, "the following mawk code will be used :");
  debug_echo_txt (mawk_clean_code ($mawk), true);
  
  if ($debug) {
    debug_echo ($cmd, "which translates to the following parsed code :");
    debug_dump_yaml ($parsed_mawk_code, true);

    if ($indexes_arr) {
      debug_echo ($cmd, "and includes the following indexes :");
      debug_dump_yaml ($indexes_arr, true);
    }
  }
  
  /*
  
  if (count ($fields_arr) > 1) {
  
    $primary_indexes = mawk_get_indexes ($primary_key, $fields_arr, $cmd);
    
    if ($primary_indexes === false)
      return  false;
      
  } else {
    $primary_indexes = array ();
  }
  
  
  # set up create indexes
     
  $new_indexes = mawk_get_indexes ($create_indexes, $fields_arr, $cmd);
  
  if ($new_indexes === false)
    return  false;
  
  */
  # set up discard
  
  if ($join_type == 'inner') {
    $inner_join = true;
  } else {
    $inner_join = false;
  }
  
  
  # run mawk code

  try {

    debug_echo ($cmd, "processing the data (this can take some time depending on the inputs) ...");
  
    $mawk_res = mawk_process_data ($parsed_mawk_code, $fields_arr, $data_arr, $indexes_arr, $update_frequency, $inner_join, $cmd, $debug);
    
  } catch (MawkError $e) {
  
    $msg = $e->getMessage();
  
    return  error ($cmd, $msg);
  }
  
  return  $mawk_res;
}


?>