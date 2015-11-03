<?php


function  mawk_process_data (&$parsed_mawk, &$fields_arr, &$data_arr, &$indexes_arr, $update_frequency = 0, 
                                                    $inner_join = false, $cmd = __FUNCTION__, $debug = false) {

                                                    
  # set up counts
  
  $sources_count = count ($data_arr);


  # build the indexes

  $indexes = mawk_build_indexes ($fields_arr, $data_arr, $indexes_arr, $cmd, $debug);
  
  if ($indexes === false)
    return  false;
    
  $GLOBALS['mawk_indexes'] = $indexes;
  
  
  # build the fields ref
  
  $fields_ref = array ();
  
  for ($i=0; $i<$sources_count; $i++) {
  
    $fields_ref[$i] = array_flip ($fields_arr[$i]);
  }
  
  $GLOBALS['mawk_fields'] = $fields_ref;
  
  
  # initiate vars
  
  $GLOBALS['mawk_vars'] = array ();
  
  
  # set primary data
  
  $data0 = &$data_arr[0];
  $data0_count = count ($data0);
  
  
  # get the primary indexes
  
  if ($sources_count > 1) {
  
    $primary_index1 = $indexes_arr[0][-1];
    $primary_indexes = array ();
  
    for ($i=1; $i<$sources_count; $i++) {
    
      $primary_indexes[$i] = &$indexes[$i][$indexes_arr[$i][-1]];
    }
  }
   

  # set up response data
  
  $response_data = array ();
  
  for ($i=0; $i<$sources_count; $i++) {
  
    $response_data[$i] = array ();
  }
  
  
  # loop over primary data
  
  for ($i=0; $i<$data0_count; $i++) {
  
  
    # create data array
  
    $data = array();
  
  
    # add primary data
  
    $data0_i = &$data0[$i];
    
    $data[0] = &$data0_i;
  
  
    # add other data if we're doing a join
  
    if ($sources_count > 1) {
  
      # get primary key
      
      $key = $data0_i[$primary_index1];

    
      # add other data (note: we pass these by value (not reference) since they might be accessed again)

      for ($j=1; $j<$sources_count; $j++) {

        $dataj = @$primary_indexes[$j][$key];
      
      
        # discard the line if we're doing an inner join
      
        if (is_null ($dataj) && $inner_join)
          continue 2;
      
        $data[$j] = &$dataj;
      }
    }
    
    $GLOBALS['mawk_data'] = $data;
       

    # set up options
    
    unset ($options);
    
    $options = array ();
    
    $GLOBALS['mawk_options'] = &$options;
    
       
    
    # map the data
    
    try {
    
      mawk_process_block ($parsed_mawk, 0);
    
    } catch (MawkHalt $h) {
    
      # just used for breaking out
      
    } catch (MawkError $e) {
    
      # re-throw the error
    
      $msg = $e->getMessage();
    
      throw new MawkError ($msg);
    }
    
    
    # keep or discard
        
    if (!@$options['discard']) {
    
    
      # save the response data

      for ($j=0; $j<$sources_count; $j++) {
    
        $response_data[$j][] = $data[$j];
      }
    }
    
    
    # check for update frequency
    
    if ($update_frequency) {
    
      $line_no = $i + 1;
      
      if ($line_no % $update_frequency == 0)
        debug_echo ($cmd, "$line_no lines processed");
    }
    
    
    # stop or continue
    
    if (@$options['stop'])
      break;
  }
  
  
  #dump_yaml ($response_data);
  
  
  $res = array (
    'all_fields'  => $fields_arr,
    'all_data'    => $response_data,
    'fields'      => $fields_arr[0],
    'data'        => $response_data[0],
  );
  
  return  $res;
}


function  mawk_process_expression (&$expr) {

  if (!is_array ($expr))
    return  $expr;


  if (!isset ($expr[0])) {
  
  
    #var_dump ($expr);
    
    $a = debug_backtrace ();
    
    var_dump ($a[0]);;
    exit;
  }
  
  $opr = $expr[0];

  
  switch ($opr) {
  
  
    # values
  
    case    OP_TRUE :
    
      return  true;
    
    case    OP_FALSE :

      return  false;
      
    case    OP_NULL :
    
      return  null;
      
    case    OP_VALUE :
    
      return  $expr[1];
    
    # variables

    case    OP_RUN_VAR :
    case    OP_FIELD_VAR :
    case    OP_INDEX_VAR :

      return  mawk_process_var ($expr);
  
  
    # unary ops
  
    case    OP_UNARY_MINUS :
    case    OP_NOT :
    case    OP_BIT_NOT :
  
      return  mawk_process_unary_op ($expr);
  
  
    # binary ops
    
    ## arithmetic

    case    OP_ADD :
    case    OP_SUB :
    case    OP_MUL :
    case    OP_MUL :
    case    OP_DIV :
    case    OP_POW :
    case    OP_MOD :
  
    ## bitop
  
    case    OP_BIT_SHIFT_LEFT :
    case    OP_BIT_SHIFT_RIGHT :
    case    OP_BIT_AND :
    case    OP_BIT_OR :
    case    OP_BIT_XOR :
  
    ## comparison
  
    case    OP_EQUAL_TO :
    case    OP_NOT_EQUAL_TO :
    case    OP_IDENTICAL_TO :
    case    OP_NOT_IDENTICAL_TO :
    case    OP_LESS_THAN :
    case    OP_LESS_THAN_OR_EQUAL_TO :
    case    OP_GREATER_THAN :
    case    OP_GREATER_THAN_OR_EQUAL_TO :

    ## logic
  
    case    OP_AND :
    case    OP_OR :
  
    ## string
  
    case    OP_CONCAT :  
  
      return  mawk_process_binary_op ($expr);
      
  
    # assignment ops
  
    case    OP_ASSIGN     :
    
    ## arithmetic assign
    
    case    OP_ADD_ASSIGN :
    case    OP_SUB_ASSIGN :
    case    OP_MUL_ASSIGN :
    case    OP_MUL_ASSIGN :
    case    OP_DIV_ASSIGN :
    case    OP_POW_ASSIGN :
    case    OP_POW_ASSIGN :
    case    OP_MOD_ASSIGN :
    
    ## bitop assign
    
    case    OP_BIT_SHIFT_LEFT_ASSIGN :
    case    OP_BIT_SHIFT_RIGHT_ASSIGN :
    case    OP_BIT_AND_ASSIGN :
    case    OP_BIT_OR_ASSIGN :
    case    OP_BIT_XOR_ASSIGN :

    ## logic assign
    
    case    OP_AND_ASSIGN :
    case    OP_OR_ASSIGN :
    
    ## string assign
    
    case    OP_CONCAT_ASSIGN :
    
      return  mawk_process_assign_op ($expr);

      
    # breaks
      
    case    OP_HALT : 
    case    OP_DISCARD :  
    case    OP_STOP : 
    case    OP_FINISH_AND_DISCARD : 
    case    OP_FINISH_AND_STOP : 
    
      return  mawk_process_break ($expr);
      
      
    # misc ops
  
    case    OP_FUNC :
    
      return  mawk_process_func ($expr);
    
    case    OP_IF :
    
      return  mawk_process_if ($expr);
      
    case    OP_BLOCK :  
    
      return  mawk_process_block ($expr);
    
    
    default   :   mawk_error ("unknown operator '$opr'");
  }
  
  
}

 
function  mawk_process_var (&$expr) {


  # get primary variable value
  
  switch ($expr[0]) {
  
    case    OP_RUN_VAR :
    
      $val = @$GLOBALS['mawk_vars'][$expr[1]];
      $start = 2;
      break;
  
    case    OP_FIELD_VAR :
    
      $val = @$GLOBALS['mawk_data'][$expr[1]][$expr[2]];
      $start = 3;
      break;
      
    case    OP_INDEX_VAR :
    
    
      # get the values
    
      $source_idx = $expr[1];
      $index_idx = $expr[2];
      
      $key = $opd1[3];
      
      if (is_array ($key))
        $key = mawk_process_expression ($key);

      $field_idx = $opd1[4];
      
      if (is_array ($field_idx))
        $field_idx = mawk_process_expression ($field_idx);
      
      
      # process values
      
      if (!(is_string ($key) || is_numeric ($key)))
        mawk_error ("index var key is not a string or a number");
      
      if (!is_integer ($field_idx)) {
      
        $field_name = $field_idx;
      
        if (!is_string ($field_name))
          mawk_error ("index var field index is not an integer or a string");
      
        $field_idx = @$GLOBALS['mawk_fields'][$source_idx][$index_idx][$field_name];
        
        if (!is_int ($field_idx))
          mawk_error ("index var field '$field_name' does not exist");
      }
      
      $val = $GLOBALS['mawk_indexes'][$source_idx][$index_idx][$key][$field_idx];
      $start = 5;
  }
    
    
  # get sub arrays
  
  $expr_count = count ($expr);
  
  for ($i=$start; $i<$expr_count; $i++) {
  
    $key = mawk_process_expression ($expr[$i]);
    $val = @$val[$key];
  
    if ($val === null)
      return  null;
  }
    
  return  $val;
}


function  mawk_process_unary_op (&$expr) {

  $opr = $expr[0];
  $opd = $expr[1];
  
  $val = mawk_process_expression ($opd);

  switch ($opr) {
  
    case    OP_UNARY_MINUS    :    return  - ($val);
    case    OP_NOT            :    return  ! ($val);
    case    OP_BIT_NOT        :    return  ~ ($val);
      
    default   :   mawk_error ("unknown unary operator '$opr'");
  }
}


function  mawk_process_binary_op (&$expr) {


  # set up variables

  $opr = $expr[0];
  $opd1 = $expr[1];
  
  $val1 = mawk_process_expression ($opd1);
  
  
  # process all the subsequent operands
  
  for ($i=2; $i<count ($expr); $i++) {
  

    # set val2
  
    $opd2 = $expr[$i];
    $val2 = mawk_process_expression ($opd2);
  

    # perform the operation
  
    switch ($opr) {
  
      # binary ops
    
      ## arithmetic

      case    OP_ADD                      :   $val1 = ($val1 + $val2);      break;
      case    OP_SUB                      :   $val1 = ($val1 - $val2);      break;
      case    OP_MUL                      :   $val1 = ($val1 * $val2);      break;
      case    OP_DIV                      :   $val1 = ($val1 / $val2);      break;
      case    OP_MOD                      :   $val1 = ($val1 % $val2);      break;
      case    OP_POW                      :   $val1 = pow ($val1, $val2);   break;
    
      ## bitop
    
      case    OP_BIT_SHIFT_LEFT           :   $val1 = ($val1 << $val2);     break;
      case    OP_BIT_SHIFT_RIGHT          :   $val1 = ($val1 >> $val2);     break;
      case    OP_BIT_AND                  :   $val1 = ($val1 & $val2);      break;
      case    OP_BIT_OR                   :   $val1 = ($val1 | $val2);      break;
      case    OP_BIT_XOR                  :   $val1 = ($val1 ^ $val2);      break;
    
      ## comparison
    
      case    OP_EQUAL_TO                 :   $val1 = ($val1 == $val2 ? true : false);     break;
      case    OP_NOT_EQUAL_TO             :   $val1 = ($val1 != $val2 ? true : false);     break;
      case    OP_IDENTICAL_TO             :   $val1 = ($val1 === $val2 ? true : false);    break;
      case    OP_NOT_IDENTICAL_TO         :   $val1 = ($val1 !== $val2 ? true : false);    break;
      case    OP_LESS_THAN                :   $val1 = ($val1 <  $val2 ? true : false);     break;
      case    OP_LESS_THAN_OR_EQUAL_TO    :   $val1 = ($val1 <= $val2 ? true : false);     break;
      case    OP_GREATER_THAN             :   $val1 = ($val1 >  $val2 ? true : false);     break;
      case    OP_GREATER_THAN_OR_EQUAL_TO :   $val1 = ($val1 >= $val2 ? true : false);     break;

      ## logic
    
      case    OP_AND                      :   $val1 = ($val1 && $val2);     break;
      case    OP_OR                       :   $val1 = ($val1 || $val2);     break;
    
      ## string
    
      case    OP_CONCAT                   :   $val1 = ($val1 . $val2);      break;
      
      default   :   mawk_error ("unknown binary operator '$opr'");
    }
  }

  return  $val1;
}


function  mawk_process_assign_op (&$expr) {


  # set up variables

  $opr = $expr[0];
  $opd1 = $expr[1];
  $opd2 = $expr[2];
  
  $val = mawk_process_expression ($opd2);


  # get variable reference

  switch ($opd1[0]) {
  
  
    case    OP_RUN_VAR :
    
      $vars = &$GLOBALS['mawk_vars'];
      $var_name = $opd1[1];
    
      if (!array_key_exists ($var_name, $vars))
        $vars[$var_name] = null;
        
      $var_ref = &$vars[$var_name];
      $start = 2;
      break;
  
  
    case    OP_FIELD_VAR :
    
      $var_ref = &$GLOBALS['mawk_data'][$opd1[1]][$opd1[2]];
      $start = 3;
      break;
    

    case    OP_INDEX_VAR :

    
      # initiate parts of the reference
    
      $source_idx = $opd1[1];
      $index_idx = $opd1[2];
      
      $key = $opd1[3];
      
      if (is_array ($key))
        $key = mawk_process_expression ($key);

      $field_idx = $opd1[4];
      
      if (is_array ($field_idx))
        $field_idx = mawk_process_expression ($field_idx);
      
      
      # get index
      
      $index = &$GLOBALS['mawk_indexes'][$source_idx][$index_idx];
      
      
      # get line of data
      
      if (!(is_string ($key) || is_numeric ($key)))
        mawk_error ("index var key is not a string or a number");
      
      if (!array_key_exists ($key, $index)) {
        $index[$key] = array ();
      }
      
      $line = &$index[$key];
      
      
      # get field
      
      if (!is_integer ($field_idx)) {
      
        $field_name = $field_idx;
      
        if (!is_string ($field_name))
          mawk_error ("index var field index is not an integer or a string");
      
        $field_idx = @$GLOBALS['mawk_fields'][$source_idx][$field_name];

        if (!is_int ($field_idx))
          mawk_error ("index var field '$field_name' does not exist");
      }
      
      if (!array_key_exists ($field_idx, $line)) {
        $line[$field_idx] = null;
      }
      
      $var_ref = &$line[$field_idx];
      $start = 5;
      break;
  }
  
  
  # TODO: sub references
  
  
  
  
  # switch on the assignment type
    
  switch ($opr) {
  
    # assignment
  
    case    OP_ASSIGN                   :   $var_ref = $val;                    break;
    
    ## arithmetic assign
    
    case    OP_ADD_ASSIGN               :   $var_ref += $val;                   break;
    case    OP_SUB_ASSIGN               :   $var_ref -= $val;                   break;
    case    OP_MUL_ASSIGN               :   $var_ref *= $val;                   break;
    case    OP_DIV_ASSIGN               :   $var_ref /= $val;                   break;
    case    OP_MOD_ASSIGN               :   $var_ref %= $val;                   break;
    case    OP_POW_ASSIGN               :   $var_ref = pow ($var_ref, $val);    break;
      
    ## bitop assign
    
    case    OP_BIT_SHIFT_LEFT_ASSIGN    :   $var_ref <<= $val;                  break;
    case    OP_BIT_SHIFT_RIGHT_ASSIGN   :   $var_ref >>= $val;                  break;
    case    OP_BIT_AND_ASSIGN           :   $var_ref &= $val;                   break;
    case    OP_BIT_OR_ASSIGN            :   $var_ref |= $val;                   break;
    case    OP_BIT_XOR_ASSIGN           :   $var_ref ^= $val;                   break;

    ## logic assign
    
    case    OP_AND_ASSIGN               :   $var_ref = ($var_ref && $val);      break;
    case    OP_OR_ASSIGN                :   $var_ref = ($var_ref || $val);      break;
    
    ## string assign
    
    case    OP_CONCAT_ASSIGN            :   $var_ref .= $val;                   break;
    
    default   :   mawk_error ("unknown assignment operator '$opr'");
  }
}


function  mawk_process_list (&$expr, $start = 0) {

  $vals = array ();

  for ($i=$start; $i<count ($expr); $i++) {
    $vals[] = mawk_process_expression ($expr[$i]);
  }
  
  return  $vals;
}


function  mawk_process_func (&$expr) {

  $func_name = $expr[1];

  $args = mawk_process_list ($expr, 2);

  return  call_user_func_array ($func_name, $args);
}


function  mawk_process_if (&$expr) {

  $if       = $expr[1];
  $if_true  = @$expr[2];
  $if_false = @$expr[3];
  
  $if = mawk_process_expression ($if);
  
  if ($if) {
  
    if ($if_true){
      mawk_process_expression ($if_true);
    }
    
  } elseif ($if_false) {
    mawk_process_expression ($if_false);
  }
}


function  mawk_process_break (&$expr) {
  
  switch ($expr[0]) {

    case    OP_HALT :
    
      $halt = true;
      $discard = false;
      $stop = false;
      break;
  
    case    OP_DISCARD :
    
      $halt = true;
      $discard = true;
      $stop = false;
      break;

    case    OP_STOP : 
      
      $halt = true;
      $discard = true;
      $stop = true;
      break;
      
    case    OP_FINISH_AND_DISCARD : 
    
      $halt = false;
      $discard = true;
      $stop = false;
      break;
    
    case    OP_FINISH_AND_STOP : 

      $halt = false;
      $discard = false;
      $stop = true;
      break;
  }
  

  # set the options
  
  $GLOBALS['mawk_options']['stop'] = $stop;
  $GLOBALS['mawk_options']['discard'] = $discard;

  
  # break out if halting now
  
  if ($halt)
    throw new MawkHalt;
}
        

function  mawk_process_block (&$block, $start = 1) {

  for ($i=$start; $i<count($block); $i++) {
  
    $expr = $block[$i];
  
    mawk_process_expression ($expr);
  }
}
 
 
function  mawk_error ($msg) {

  throw new MawkError ("$msg");
}


class MawkError extends Exception {};
class MawkHalt extends Exception {};


?>