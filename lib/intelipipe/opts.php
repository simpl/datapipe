<?php


function  opt_not_set_msg ($cmd, $names, $msg_type = 'error') {

  
  # build names string

  $names = explode (',', $names);
  
  $names_str = "'$names[0]'";
  
  if (count ($names) > 0) {
  
    for ($i=1; $i<count($names); $i++) {
    
      $names_str .= " or '$names[$i]'";
    }
  }
  
  # build the message
    
  $msg = "option $names_str not set";
    
  return  message ($cmd, $msg, $msg_type);
}


function  check_opt_set ($cmd, $opt, $name, $msg_type = 'error') {

  if (!is_null ($opt))
    return  true;

  opt_not_set_msg ($cmd, $name, $msg_type);
  
  return  false;
}


function  full_opt_name ($cmd, $name) {

  $cmd = explode (':', $cmd);
  
  if (count ($cmd) == 1)
    return  $name;

  return  "$cmd[1]_$name";
}


function  check_opt_type ($cmd, $opt, $name, $types, $msg_type = 'error') {


  # check if the opt is of the right type

  $types = explode (',', $types);

  foreach ($types as $i => $type) {
  
  
    # check for special enum types
  
    $sub_types = explode (':', $type);
  
    switch ($sub_types[0]) {

      case  'data_join_type'        :   $main_type = 'enum';
                                        $test = 'inner|outer';
                                        break;
    
      case  'data_source_type'      :   $main_type = 'enum';
                                        $test = 'file|pass|search';
                                        break;
                                        
      case  'data_structure_type'   :   $main_type = 'enum';
                                        $test = 'list_of_objects|list_of_columns';
                                        break;
                                        
      case  'data_type'             :   $main_type = 'enum';
                                        $test = 'index|array_of_indexes';
                                        break;
                                        
      case  'document_file_type'    :   $main_type = 'enum';
                                        $test = 'json|yaml';
                                        break;
                                        
      case  'filter_data_type'      :   $main_type = 'enum';
                                        $test = 'join';
                                        break;
    
      case  'http_request_method'   :   $main_type = 'enum';
                                        $test = 'get|post';
                                        break;
                                        
      case  'message_type'          :   $main_type = 'enum';
                                        $test = 'error|warning|notice|none';
                                        break;

      case  'suredone_action'       :   $main_type = 'enum';
                                        $test = 'add|edit';
                                        break;
                                        
      case  'suredone_type'         :   $main_type = 'enum';
                                        $test = 'categories|items|orders|pages|posts|tags';
                                        break;
                                    
      default                       :   $main_type = $sub_types[0];
                                        $test = @$sub_types[1];
                                        break;
    }
  
  
    # reset the types
    
    $type = "$main_type";
    
    if ($test)
      $type .= ":$test";
      
    $types[$i] = $type;
    
  
    # check the main types
  
    # TODO: add integer:min=x,max=y
  
    switch ($main_type) {
    
      case  'array'                   :   $true = is_array                    ($opt);         break;
      case  'array_of_arrays'         :   $true = is_array_of_arrays          ($opt);         break;
      case  'array_of_integers'       :   $true = is_array_of_integers        ($opt);         break;
      case  'array_of_strings'        :   $true = is_array_of_strings         ($opt);         break;
      case  'boolean'                 :   $true = is_bool                     ($opt);         break;
      case  'enum'                    :   $true = is_enum                     ($opt, $test);  break;
    /*case  'dir'                     :   $true = is_local_dir                ($opt);         break;
      case  'file'                    :   $true = is_local_file               ($opt);         break;*/
      case  'float'                   :   $true = is_float                    ($opt);         break;
      case  'integer'                 :   $true = is_int                      ($opt);         break;
      case  'positive_integer'        :   $true = is_positive_int             ($opt);         break;
      case  'string'                  :   $true = is_string                   ($opt);         break;
      case  'yaml_array'              :   $true = is_yaml_array               ($opt);         break;
      case  'yaml_array_of_integers'  :   $true = is_yaml_array_of_integers   ($opt);         break;
      case  'yaml_array_of_strings'   :   $true = is_yaml_array_of_strings    ($opt);         break;
      default :
        return  error ($cmd, "invalid check opt type '$type'");
    }
    
    if ($true) {
      return  true;
    }
  }
  
    
  # build the types string
      
  $types_str = $types[0];
  
  if (count ($types) > 0) {
  
    for ($i=1; $i<count($types); $i++) {
    
      $type = $types[$i];
    
      $types_str = "$types_str or $type";
    }
  }
  
  
  # build the message string
 
  $name = full_opt_name ($cmd, $name);
 
  $msg = "option '$name' is not of type $types_str";

  message ($cmd, $msg, $msg_type);

  return  false;
}


function  check_opt_set_type ($cmd, $opt, $name, $types, $msg_type = 'error') {

  if (!check_opt_set ($cmd, $opt, $name, $msg_type))
    return  false;

  return  check_opt_type ($cmd, $opt, $name, $types, $msg_type);
}


function  check_opt_if_set_type ($cmd, $opt, $name, $types, $msg_type_if_set = 'error', $msg_type_if_not_set = 'none') {

  if (is_null ($opt)) {
  
    if ($msg_type_if_not_set) {
    
      opt_not_set_msg ($cmd, $name, $msg_type_if_not_set);
    
      if ($msg_type_if_not_set == 'error')
        return  false;
    }
  
    return  true;
  }
  
  return  check_opt_type ($cmd, $opt, $name, $types, $msg_type_if_set);
}


function  check_opt_file_exists ($cmd, $file, $name, $msg_type = 'error') {

  if (file_exists ($file))
    return  true;
    
  $name = full_opt_name ($cmd, $name);
    
  $msg = "file in option '$name' does not exist : $file";

  return  message ($cmd, $msg, $msg_type);
}

        
function  get_config_value ($name, $default = null) {

  if (array_key_exists ($name, $GLOBALS)) {
  
    return  $GLOBALS[$name];
  }
  
  return  $default;
}


function  set_config_value ($name, $value) {

  $GLOBALS[$name] = $value;
}


function  get_opt_key ($prefix, $opts, $name) {

  $opts_key = "${name}_key";
  
  $key = @$opts[$opts_key];
  
  if ($key)
    return  $key;
    
  $config_value_key = "${prefix}_${opts_key}";
    
  return  get_config_value ($config_value_key);
}


function  get_opt ($prefix, $opts, $name, $default = null) {

  
  # get the opt key (usually the same as name, but can be set separately using the _key suffix)
   
  $key = get_opt_key ($prefix, $opts, $name);

  if ($key) {
    $keys = array ($name, $key);
  } else {
    $keys = array ($name);
  }
  

  # loop through both names and keys
  
  foreach ($keys as $key) {
  
    $val = $val2 = null;
  
    # check for non-piped values
    
    if (array_key_exists ($key, $opts)) {
    
      $val = $opts[$key];
    
      if (!is_array ($val) && !is_null ($val))
        return  $val;
    }
      
      
    # check if we should ignore piped values
      
    if (array_key_exists ("ignore_piped_$key", $opts)) {
    
      if ($val)
        return  $val;
    
      return  $default;
    }
    
    
    # check for piped values
    
    $piped_name = "piped_$key";
    
    if (array_key_exists ($piped_name, $opts)) {
    
      $val2 = $opts[$piped_name];
      
      if (!is_array ($val2)) {
      
        if (is_array ($val))
          return  $val;
      
        return  $val2;
      }
      
      if (is_array ($val))
        return  array_merge ($val2, $val);
        
      return  $val2;
    }

    if ($val !== null)
      return  $val;
  }
    
  return  $default;
}


function  get_opt_config_value ($prefix, $opts, $name, $default = null) {

  $opt_val = get_opt ($prefix, $opts, $name);
  
  if ($opt_val !== null)
    return  $opt_val;
    
  $config_name = "${prefix}_${name}";
  
  return  get_config_value ($config_name, $default);
}


function  get_opts_or_pipe ($opts, $pipe) {

  if (is_null ($opts) || (is_array ($opts) && empty ($opts)))
    return  $pipe;

  return  $opts;
}


function  merge_opts ($opts, $pipe, $default_key = null) {
  
  if (is_bool ($pipe)) {  // !is_array ($pipe)?
  
    if (is_array ($opts))
      return  $opts;
  
    return  array ($default_key => $opts);
  }
  
  
  $new_opts = array ();
  
 
  # merge $pipe
  
  if (is_array ($pipe)) {
  
  
    # set previously piped values
  
    foreach ($pipe as $key => $value) {
    
      if (substr ($key, 0, 6) == 'piped_') {
      
        $new_opts[$key] = $value;
      }
    }   // TODO: merge the type piped values
    
    
    # set newly piped values
    
    foreach ($pipe as $key => $value) {
    
      if (substr ($key, 0, 6) != 'piped_') {
      
        $new_opts["piped_$key"] = $value;
      }
    }
      
  } elseif ($default_key && !is_null ($pipe)) {
  
    $new_opts["piped_$default_key"] = $pipe;
  }
  
 
  # merge $opts
  
  if (is_array ($opts)) {
  
    $new_opts = array_merge ($new_opts, $opts);
    
  } elseif ($default_key && !is_null ($opts)) {
  
    $new_opts[$default_key] = $opts;
  }
  
  
  return  $new_opts;
}


function  merge_opts_for_output ($res, $opts, $keys = null) {


  # default to the opts keys if no keys set

  if ($keys === null)
    $keys = array_keys ($opts);
    
    
  # remove the 'piped_' from the front if piped
    
  for ($i=0; $i<count($keys); $i++) {
  
    $key = $keys[$i];
    
    if (substr ($key, 0, 6) == 'piped_')
      $keys[$i] = substr ($key, 6);
  }
  
  
  # loop over opts
  
  foreach ($keys as $key) {
  
  
    # check to see if the value already exists in the $res
  
    $val = @$res[$key];
    
    if (!is_null ($val))
      continue;
  
  
    # check for a piped value
  
    $val = get_opt ('', $opts, $key);
    
    if (!is_null ($val))
      $res[$key] = $val;
  }
  
  return  $res;
}


function  merge_opts_for_data_processing ($res, $opts) {


  # keys shared by create_data() and search_data() functions

  $keys = array (
    'data',
    'fields',
    'search_indexes',
    'search_results',
  );

  return  merge_opts_for_output ($res, $opts, $keys);
}


function  adjust_opt_prefix ($cmd, &$opts, $opt_prefix, $default_opt_prefix) {

  if ($opt_prefix === false)
    return  $cmd;
    
  if (!is_string ($opt_prefix))
    $opt_prefix = $default_opt_prefix;
    
  $opt_prefix = "${opt_prefix}_";
  $opt_prefix_len = strlen ($opt_prefix);
  
  $keep_keys = array ();
  
  
  # switch the new opts
  
  foreach ($arr as $key => $value) {
  
    if (substr ($key, 0, $opt_prefix_len) == $opt_prefix) {
    
      $new_key = substr ($key, $opt_prefix_len); 

      $arr[$new_key] = $value;
      $keep_keys[] = $new_key;
    }
  }
  
  
  # delete the other opts
  
  foreach ($arr as $key => $value) {
  
    if (in_array ($key, $keep_keys))
      continue;
      
    unset ($arr[$key]);
  }
  

  return  "$cmd:$opt_prefix";
}


?>