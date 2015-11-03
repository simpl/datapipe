<?php


function  build_result_data (&$fields, &$data, &$indexes = null) {


  # build the result to include a sources array, which is used by process_data

  $res = array (
    'fields'  => &$fields,
    'data'    => &$data,
  );
  
  /*
  $sources_res = array (
    'fields'  => &$fields,
    'data'    => &$data,
  );
  */
  
  if ($indexes != null) {
  
    $res['indexes']         = &$indexes;
    #$sources_res['indexes'] = &$indexes;
  }
  
  #$res['sources'] = array ($sources_res);
  
  return  $res;
}


function  build_source_data ($prefix, $opts, $cmd) {


  # get data opt

  $data = &get_opt_config_value ($prefix, $opts, 'data');
  
  if (!check_opt_if_set_type ($cmd, $data, 'type', 'array'))
    return  false;

    
  # get fields opt
    
  $fields = &get_opt_config_value ($prefix, $opts, 'fields');
  
  if (!check_opt_if_set_type ($cmd, $fields, 'type', 'array_of_strings'))
    return  false;

    
  # get indexes opt
  
  $indexes = &get_opt_config_value ($prefix, $opts, 'indexes');
  
  if (!check_opt_if_set_type ($cmd, $indexes, 'type', 'array'))
    return  false;

    
  # check if data and fields are set
  
  if (!is_array ($data) || !is_array ($fields))
    return  false;
    
    
  return  build_result_data ($fields, $data, $indexes);
}


function  set_as_key ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'result';

  
  # merge opts
  
  $opts = merge_opts ($opts, null, 'key');

  
  # get key opt
  
  $key = get_opt ($prefix, $opts, 'key');
  
  if (!check_opt_set_type ($cmd, $key, 'key', 'string'))
    return  false;
  
  
  # build $res
  
  $arr = merge_opts_for_output (array (), $pipe);
  
  return  array ($key => $arr);
}


function  set_as_key_index ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'result_set_as_key_index';

  
  # merge opts
  
  $opts = merge_opts ($opts, null, 'key');

  
  # get key opt
  
  $key = get_opt ($prefix, $opts, 'key');
  
  if (!check_opt_set_type ($cmd, $key, 'key', 'string'))
    return  false;
  
  
  # get index opt
  
  $index = get_opt ($prefix, $opts, 'index', 0);
  
  if (!check_opt_set_type ($cmd, $index, 'index', 'integer'))
    return  false;
  
  
  # build $res
  
  $arr = merge_opts_for_output (array (), $pipe);
  
  $res = array (
    $key => array(),
  );
  
  $res[$key][$index] = $arr;
  
  return  $res;
}


function  result_switch_keys ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'result_switch_keys';

  
  # merge_opts
  
  $opts = merge_opts ($opts, $pipe);
  
  
  # get key opt
  
  $keys = get_opt ($prefix, $opts, 'keys');
  
  if (!check_opt_set_type ($cmd, $keys, 'keys', 'array'))
    return  false;
    
  
  # check validity of pipe
    
  if (!is_array ($pipe))
    return  error ($cmd, "pipe is not an array");
    

  # store values
  
  $vals = array ();
  
  foreach ($keys as $old_key => $new_key) {
  
    $vals[$new_key] = $pipe[$old_key];
  }
  
  
  # unset old keys
  
  foreach ($keys as $old_key => $new_key) {
  
    unset ($pipe[$old_key]);
  }
  
  
  # set the new keys
  
  foreach ($vals as $new_key => $val) {
  
    $pipe[$new_key] = $val;
  }
  
  return  $pipe;
}



?>