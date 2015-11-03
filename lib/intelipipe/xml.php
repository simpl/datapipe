<?php


function  xml_parse_document ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix
  
  $prefix = 'xml_parse_document';
  
  
  # merge opts

  $opts = merge_opts ($opts, $pipe, 'document');
  
  
  # get document opt
  
  $document = get_opt ($prefix, $opts, 'document');
  
  if (!check_opt_set_type ($cmd, $document, 'document', 'string'))
    return  false;

    
  # get doc type opt
  
  $doc_type = get_opt_config_value ($prefix, $opts, 'doc_type', 'xml');
  
  if (!check_opt_set_type ($cmd, $doc_type, 'doc_type', 'string'))
    return  false;
    
    
  # get logic opt
    
  $logic = get_opt ($prefix, $opts, 'logic');
  
  if (!check_opt_set_type ($cmd, $logic, 'logic', 'string,array'))
    return  false;
    
  if (is_string ($logic)) {
  
    $logic = @yaml_decode ($logic);

    if (!$logic)
      return  error ($cmd, "parse logic not valid YAML");
  }
    
    
  # get result type opt
  
  $result_type = get_opt_config_value ($prefix, $opts, 'result_type', 'elements');
  
  if (!check_opt_set_type ($cmd, $result_type, 'result_type', 'string'))
    return  false;
    
    
  # get merge results opt
  
  $merge_results = get_opt ($prefix, $opts, 'merge_results', false);
  
  if (!check_opt_set_type ($cmd, $merge_results, 'merge_results', 'boolean'))
    return  false;
    
    
  # load the document
  
  $dom = new DOMDocument('1.0');
  @$dom->loadHTML ($document);
  #$dom->preserveWhiteSpace = false;
    
    
  # check that the document was parsed correctly
    
  $res = xml_parse_element ($dom, $logic);
  
  if ($res === false)
    return  error ($cmd, "could not parse $type document");
  
  
  # display info about the parsing
  
  debug_echo ($cmd, "$doc_type document parsed successfully for $result_type");
  
  if (empty ($res)) {
  
    debug_echo ($cmd, "no $result_type found while parsing the $doc_type document");
  
  } else {
  
    debug_echo ($cmd, "the following $result_type were found in the $doc_type document");
    debug_dump_yaml ($res, true);
  }
  
  
  # return if we are not merging the results
  
  if (!$merge_results)
    return  $res;
  

  # merge the results
  
  $new_res = array ();
  $res_count = count ($res);

  
  for ($i=0; $i<$res_count; $i++) {

    $elt = $res[$i];
  
    foreach ($elt as $key => $value) {
    
      if (is_array ($value)) {
      
        $cur_value = @$new_res[$key];
        
        if ($cur_value) {
          $value = array_merge ($cur_value, $value);
        }
      }
      
      $new_res[$key] = $value;
    }
  }
  
  return  $new_res;
}


function  xml_parse_element ($node, $logic) {


  # get the attributes and elements

  $attributes = @$logic['attributes'];
  $elements = @$logic['elements'];

  
  # initiate the response
  
  $res = array ();
  
  
  # parse attributes
  
  if ($attributes && $node->hasAttributes()) {
      
    foreach ($attributes as $name => $test_value) {
    
    
      # check whether we should set the value or not
    
      if ($name[0] == '!') {
      
        $name = substr ($name, 1);
        $set_val = false;
        
      } else {
      
        $set_val = true;
      }
    
    
      # test value if necessary
    
      $value = $node->getAttribute ($name);
      
      if ($test_value) {
      
        if ($value != $test_value)
          return;
      }
    
    
      # set value if necessary
    
      if ($set_val)
        $res[$name] = $value;
    }
  }

  
  # parse elements

  if ($elements) {
  
    for ($i=0; $i<count ($elements); $i++) {
  
      $elt = $elements[$i];
      
      if (is_array ($elt)) {
      
        foreach ($elt as $tag => $elt_logic) {        
        
        
          # check for a label
          
          unset ($res_arr);
          
          $label = @$elt_logic['label'];

          if ($label) {
          
            if (is_array (@$res[$label])) {
            
              $res_arr = &$res[$label];
              
            } else {
          
              $res_arr = array ();
              
              $res[$label] = &$res_arr;
            }
              
          } else {
          
            $res_arr = &$res;
          }
          
          
          # parse the elements
        
          $elt_nodes = $node->getElementsByTagName ($tag);
        
          foreach ($elt_nodes as $elt_node) {
          
          
            # parse the node
          
            $elt_res = xml_parse_element ($elt_node, $elt_logic);
          
            if (!$elt_res)
              continue;
              
            
            # save the elt            
            
            $res_arr[] = $elt_res;
          }
        }
      }
    }
  }
  
  
  # check for empty arrays
  
  foreach ($res as $key => $val) {
  
    if (!$val)
      unset ($res[$key]);
  }
  
  ksort ($res);
      
  return  $res;
}


?>