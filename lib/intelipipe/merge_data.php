<?php


function  merge_relational_data ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix
  
  $prefix = 'merge_relational_data';
  
  
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

    
  # get primary key opt

  $primary_key = get_opt_config_value ($prefix, $opts, 'primary_key');
  
  if (!check_opt_set_type ($cmd, $primary_key, 'primary_key', 'string'))
    return  false;

    
  # get check field opt

  $merge_rules = get_opt_config_value ($prefix, $opts, 'merge_rules');
  
  if (!check_opt_set_type ($cmd, $merge_rules, 'merge_rules', 'array,yaml_array'))
    return  false;  
    
  if (is_string ($merge_rules))
    $merge_rules = yaml_decode ($merge_rules);
    
    
  # set up primary key
  
  if (!in_array ($primary_key, $fields))
    return  error ($cmd, "field '$primary_key' does not exist");
    
  $fields_flipped = array_flip ($fields);
  
  $primary_key_index = $fields_flipped[$primary_key];
    
    
  # set up new fields
  
  $new_fields = array ();
  
  foreach ($fields as $field) {
  
    $new_fields[] = $field;
  }
  
        
    
  # loop through all the rules
  
  $rules = array ();
  
  foreach ($merge_rules as $i => $merge_rule) {
  
    $rule = array ();
    
  
    # get check field opt

    $check_field = get_opt_config_value ($prefix, $merge_rule, 'check_field');
    
    if (!check_opt_set_type ($cmd, $check_field, 'check_field', 'string'))
      return  false;
      
            
    # check that the fields exist
    
    if (!in_array ($check_field, $fields))
      return  error ($cmd, "field '$check_field' does not exist");
    
    $check_field_index = $fields_flipped[$check_field];
    
    $rule[0] = $check_field_index;
            
    
    # get value field opt

    $value_field = get_opt_config_value ($prefix, $merge_rule, 'value_field');
    
    if (!check_opt_if_set_type ($cmd, $value_field, 'value_field', 'string'))
      return  false;
      
    
    # get value field map opt
    
    $value_field_map = get_opt_config_value ($prefix, $merge_rule, 'value_field_map');
    
    if (!check_opt_if_set_type ($cmd, $value_field_map, 'value_field_map', 'array,yaml_array'))
      return  false;
      
    if ($value_field_map && !is_array ($value_field_map))
      $value_field_map = yaml_decode ($value_field_map);
      
    if ($value_field === null && $value_field_map === null)
      return  opt_not_set_msg ($cmd, 'value_field,value_field_map');
      
      

    # check the type of the rule
      
    if ($value_field !== null) {
    
      $rule[1] = 'f';
      
      if (!in_array ($value_field, $fields))
        return  error ($cmd, "field '$value_field' does not exist");  
      
      $value_field_index = $fields_flipped[$value_field];
      
      $rule[2] = $value_field_index;

    } else {
    
      $rule[1] = 'fm';
      
      
      # set up value field map
    
      foreach ($value_field_map as $value => $field) {
    
        if (!in_array ($field, $new_fields))  
          $new_fields[] = $field;
      }
      
      
      # create the value indexes
    
      $new_fields_flipped = array_flip ($new_fields);
      
      $values_indexes = array ();
      
      foreach ($value_field_map as $value => $field) {
      
        $values_indexes[$value] = $new_fields_flipped[$field];
      }
      
      $rule[2] = $values_indexes;
    }
      
      
    # get value opt

    # TODO: value_map
    
    if (array_key_exists ('value', $merge_rule)) {
    
      $rule[3] = 'v';
      $rule[4] = $merge_rule['value'];
    
    } elseif (array_key_exists ('value_map', $merge_rule)) {
    
      $value_map = get_opt_config_value ($prefix, $merge_rule, 'value_map');
    
      if (!check_opt_set_type ($cmd, $value_map, 'value_map', 'array,yaml_array'))
        return  false;
        
      if (is_string ($value_map))
        $value_map = yaml_decode ($value_map);
        
      $rule[3] = 'vm';
      $rule[4] = $value_map;
      
    } else {
    
      $rule[3] = 'cv';
    }
    
         
    # add rule to rules
    
    $rules[] = $rule;
  }
      

  # set up counts
  
  $data_count = count ($data);
  $field_count = count ($fields);
  $new_field_count = count ($new_fields);
  
  
  # set up new data

  $new_data = array ();
  $new_data_indexes = array ();
  
  
  # loop over data and apply rules
  
  debug_echo ($cmd, "merging relational data (this can take some time) ...");
  
  for ($i=0; $i<$data_count; $i++) {
  
  
    # get line and check if line already exists
  
    $line = $data[$i];
    
    $primary_value = $line[$primary_key_index];
  
    unset ($new_line);
  
    $new_line = &$new_data_indexes[$primary_value];
  
  
    # create new line
  
    if (!$new_line) {
    
      unset ($new_line);
      
      $new_line = array ();
      
      for ($j=0; $j<$field_count; $j++) {
      
        $new_line[$j] = $line[$j];
      }
      
      for ($j=$field_count; $j<$new_field_count; $j++) {
      
        $new_line[] = null;
      }
      
      $new_data[] = &$new_line;
      $new_data_indexes[$primary_value] = &$new_line;
      
    }
    

    # loop through the rules to modify the data
    
    foreach ($rules as $rule) {
    
    
      # set up rule parts
    
      $check_field_index = $rule[0];
      $value_field_type = $rule[1];
      $value_field_index = $rule[2];
      $value_type = $rule[3];
      
      
      # intiiate check
      
      $check_value = $line[$check_field_index];
            
      if ($check_value === null)
        continue;
      
      
      # get value field
      
      switch ($value_field_type) {
      
        case  'fm' :    $value_field_index = @$value_field_index[$check_value];     break;
      
      }
      
      
      if ($value_field_index === null)
        continue;
      
      
      # get value
      
      switch ($value_type) {
      
        case  'v'   :   $value = $rule[4];                break;
        case  'vm'  :   $value = $rule[4][$check_value];  break;
        case  'cv'  :   $value = $check_value;            break;
      }
      
      
      # set the value
      
      $new_line[$value_field_index] = $value;
    }
  }

  
  # build result
  
  $res = array (
    'data'    => $new_data,
    'fields'  => $new_fields,
  );
  
  return  $res;
}


?>