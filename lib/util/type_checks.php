<?php


function  is_array_of_arrays ($arr) {

  if (!is_array ($arr))
    return  false;

  for ($i=0; $i<count($arr); $i++) {
  
    $val = $arr[$i];
    
    if (!is_array ($val)) {
      return  false;
    }
  }
  
  return  true;
}


function  is_array_of_integers ($arr) {

  if (!is_array ($arr))
    return  false;

  for ($i=0; $i<count($arr); $i++) {
  
    $val = $arr[$i];
    
    if (!is_integer ($val)) {
      return  false;
    }
  }
  
  return  true;
}


function  is_array_of_strings ($arr) {

  if (!is_array ($arr))
    return  false;

  for ($i=0; $i<count($arr); $i++) {
  
    $val = $arr[$i];
    
    if (!is_string ($val)) {
      return  false;
    }
  }
  
  return  true;
}


function  is_enum ($str, $options) {

  if (!is_string ($str))
    return  false;

  $options = explode ('|', $options);
  
  foreach ($options as $option) {
  
    if ($str == $option)
      return  true;
  }

  return  false;
}


function  is_local_dir ($dir) {

  if (!is_string ($dir))
    return  false;
    
  return  is_dir ($dir);
}


function  is_local_file ($file) {

  if (!is_string ($file))
    return  false;
    
  return  file_exists ($file);
}


function  is_negative_int ($int) {

  if (!is_int ($int) || $int > 0)
    return  false;
    
  return  true;
}


function  is_positive_int ($int) {

  if (!is_int ($int) || $int < 0)
    return  false;
    
  return  true;
}


function  is_yaml_array ($yaml_str) {

  if (!is_string ($yaml_str))
    return  false;
  
  $val = @yaml_decode ($yaml_str);
  
  if (!is_array ($val))
    return  false;
    
  return  true;
}


function  is_yaml_array_of_integers ($yaml_str) {

  if (!is_string ($yaml_str))
    return  false;
  
  $val = @yaml_decode ($yaml_str);
      
  return  is_array_of_integers ($val);
}


function  is_yaml_array_of_strings ($yaml_str) {

  if (!is_string ($yaml_str))
    return  false;
  
  $val = @yaml_decode ($yaml_str);
      
  return  is_array_of_strings ($val);
}


function  is_upc ($upc) {

  
}


function  is_ean ($ean) {


}

?>