<?php


function  modify_rows ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix
  
  $prefix = 'modify_rows';
  
  
  # merge opts
  
  $opts = merge_opts ($opts, $pipe);
  
    
  # get data opt

  $data = get_opt_config_value ($prefix, $opts, 'data');
  
  if (!check_opt_set_type ($cmd, $data, 'data', 'array'))
    return  false;
  

  # get fields opt

  $res_fields = get_opt_config_value ($prefix, $opts, 'fields');
  
  if (!check_opt_set_type ($cmd, $res_fields, 'fields', 'array_of_strings'))
    return  false;
    
  
  # get limit opt

  $limit = get_opt_config_value ($prefix, $opts, 'limit');
  
  if (!check_opt_set_type ($cmd, $limit, 'limit', 'integer'))
    return  false;
    
    
  # get limit opt

  $offset = get_opt_config_value ($prefix, $opts, 'offset', 0);
  
  if (!check_opt_set_type ($cmd, $offset, 'offset', 'integer'))
    return  false;
    
  
  # display message
  
  debug_echo ($cmd, "modifying rows (limit = $limit, offset = $offset)");
  
  
  # set up end
  
  $end = $offset + $limit;
  
  
  # process the data
  
  $res_data = array ();
  
  for ($i=$offset; $i<$end; $i++) {
  
    $res_data[] = $data[$i];
  }
  
    
  # build response object
  
  return  build_result_data ($res_fields, $res_data);
}


?>