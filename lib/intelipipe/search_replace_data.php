<?php


function  search_replace_data_by_key_value ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix
  
  $prefix = 'search_replace_data';
  
  
  # merge opts
  
  $opts = merge_opts ($opts, $pipe);
  
  
  # get fields opt
  
  $data = get_opt_config_value ($prefix, $opts, 'data');
  
  if (!check_opt_set_type ($cmd, $data, 'data', 'array'))
    return  false;
  
  
  # get fields opt

  $fields = get_opt_config_value ($prefix, $opts, 'fields');
  
  if (!check_opt_set_type ($cmd, $fields, 'fields', 'array_of_strings'))
    return  false;
    
    
  # get map opt
    
  $search = get_opt_config_value ($prefix, $opts, 'search');
  
  if (!check_opt_set_type ($cmd, $search, 'search', 'array,yaml_array'))
    return  false;

  if (is_string ($search))
    $search = yaml_decode ($search);
    
    
  # get replace opt
    
  $replace = get_opt_config_value ($prefix, $opts, 'replace');
  
  if (!check_opt_set_type ($cmd, $replace, 'replace', 'array,yaml_array'))
    return  false;

  if (is_string ($replace))
    $replace = yaml_decode ($replace);
    
    
  # set all the fields
  
  $search_fields = array_keys ($search);
  $value_fields = array_values ($search);
  $replace_fields = array_values ($replace);
  
  
  # set up new fields
  
  $new_fields = array ();
  
  foreach ($fields as $field) {
  
    if (in_array ($field, $search_fields) || in_array ($field, $value_fields))
      continue;
      
    $new_fields[] = $field;
  }
  
  foreach ($replace_fields as $field) {
  
    $new_fields[] = $field;
  }
    
  $new_fields_count = count ($new_fields);
  
  
  # set up copy fields
  
  $new_fields_flipped = array_flip ($new_fields);
  
  $copy_fields = array ();
  
  foreach ($fields as $old_index => $field) {

    if (!in_array ($field, $new_fields) || in_array ($field, $replace_fields))
      continue;

    $new_index = $new_fields_flipped[$field];
      
    $copy_fields[$old_index] = $new_index;
  }
  
  $copy_fields_count = count ($copy_fields);
    
    
  # set up search field indexes
  
  $fields_flipped = array_flip ($fields);
  
  $searches = array ();
  
  foreach ($search as $check_field => $value_field) {
  
    $check_field_idx = $fields_flipped[$check_field];
    $value_field_idx = $fields_flipped[$value_field];
  
    $searches[$check_field_idx] = $value_field_idx;
  }
    
    
  # set up replaces
    
  $replaces = array ();
  
  foreach ($replace as $value => $field) {
  
    $replaces[$value] = $new_fields_flipped[$field];
  }
  
  
  # display messages
  
  debug_echo ($cmd, "doing a search-replace by key value using the following pairs (check_field: value_field):");
  debug_dump_yaml ($search, true);
  debug_echo ($cmd, "the following found replacements were made (checked_value: replacement_field):");
  debug_dump_yaml ($replace, true);  
    
    
  # loop through data
  
  $new_data = array ();
  
  foreach ($data as $line) {
  
  
    # create new line
  
    $new_line = array ();
    
    for ($i=0; $i<$new_fields_count; $i++) {
      $new_line[] = null;
    }
    
  
    # copy data
    
    foreach ($copy_fields as $old_index => $new_index) {
    
      $new_line[$new_index] = $line[$old_index];
    }
  
  
    # search for values
    
    foreach ($searches as $check_field_index => $value_field_index) {
    
      $check = $line[$check_field_index];
      
      $replace_field_index = @$replaces[$check];
      
      if ($replace_field_index === null)
        continue;
      
      $new_line[$replace_field_index] = $line[$value_field_index];
    }
  
  
    # add the new line to the data
  
    $new_data[] = $new_line;
  }
    

  # build res
  
  $res = array (
    'fields'  => $new_fields,
    'data'    => $new_data,
  );
  
  
  return  $res;
}


?>