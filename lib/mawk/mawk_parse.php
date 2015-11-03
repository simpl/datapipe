<?php


#### DEFINITIONS ####

$ops = array (

  # variables
  
  'RUN_VAR',
  'FIELD_VAR',
  'INDEX_VAR',
  
  # values
  
  'VALUE',
  'TRUE',
  'FALSE',
  'NULL',
  
  # flow
  
  'BLOCK',  
  'FUNC',
  'IF',
  
  # breaks
  
  'HALT',
  'DISCARD',
  'STOP',
  'FINISH_AND_DISCARD',
  'FINISH_AND_STOP',
  
  # arithmetic
  
  'ADD',
  'SUB',
  'MUL',
  'DIV',
  'POW',
  'MOD',
  'UNARY_MINUS',
  
  # bitop
  
  'BIT_SHIFT_LEFT',
  'BIT_SHIFT_RIGHT',
  'BIT_AND',
  'BIT_OR',
  'BIT_NOT',
  'BIT_XOR',
  
  # comparison
  
  'EQUAL_TO',
  'NOT_EQUAL_TO',
  'IDENTICAL_TO',
  'NOT_IDENTICAL_TO',
  'LESS_THAN',
  'LESS_THAN_OR_EQUAL_TO',
  'GREATER_THAN',
  'GREATER_THAN_OR_EQUAL_TO',
  
  # logic
  
  'AND',
  'OR',
  'NOT',
  
  # string
  
  'CONCAT',
  
  # assignment
  
  'ASSIGN',  
  
  # arithmetic assign
  
  'ADD_ASSIGN',
  'SUB_ASSIGN',
  'MUL_ASSIGN',
  'DIV_ASSIGN',
  'MOD_ASSIGN',
  'POW_ASSIGN',
  
  # bitop assign
  
  'BIT_SHIFT_LEFT_ASSIGN',
  'BIT_SHIFT_RIGHT_ASSIGN',
  'BIT_AND_ASSIGN',
  'BIT_OR_ASSIGN',
  'BIT_NOT_ASSIGN',
  'BIT_XOR_ASSIGN',

  # logic assign
  
  'AND_ASSIGN',
  'OR_ASSIGN',
  'NOT_ASSIGN',
  
  # string assign
  
  'CONCAT_ASSIGN',  
);


for ($i=0; $i<count($ops); $i++) {

  define ("OP_$ops[$i]", $i);
}


#### OPS LISTS ####

$assignment_ops = array (

  '='   => OP_ASSIGN,
  
  # arithmetic
  
  '+='  => OP_ADD_ASSIGN,
  '-='  => OP_SUB_ASSIGN,
  'x='  => OP_MUL_ASSIGN,
  '*='  => OP_MUL_ASSIGN,
  '/='  => OP_DIV_ASSIGN,
  '**=' => OP_POW_ASSIGN,
  'xx=' => OP_POW_ASSIGN,
  '%='  => OP_MOD_ASSIGN,
  
  # bit
  
  '<<=' => OP_BIT_SHIFT_LEFT_ASSIGN,
  '>>=' => OP_BIT_SHIFT_RIGHT_ASSIGN,
  '&='  => OP_BIT_AND_ASSIGN,
  '|='  => OP_BIT_OR_ASSIGN,
  '~='  => OP_BIT_XOR_ASSIGN,

  # logic
  
  '&&=' => OP_AND_ASSIGN,
  '||=' => OP_OR_ASSIGN,
  
  # string
  
  '.='  => OP_CONCAT_ASSIGN,
);


$unary_value_ops = array (

  '-'   => OP_UNARY_MINUS,
  '!'   => OP_NOT,
  '~'   => OP_BIT_NOT,
);


$binary_value_ops = array (
  
  # arithmetic

  '+'   =>  OP_ADD,
  '-'   =>  OP_SUB,
  'x'   =>  OP_MUL,
  '*'   =>  OP_MUL,
  '/'   =>  OP_DIV,
  '%'   =>  OP_MOD,
  '**'  =>  OP_POW,
  
  # bit
  
  '<<'  =>  OP_BIT_SHIFT_LEFT,
  '>>'  =>  OP_BIT_SHIFT_RIGHT,
  '&'   =>  OP_BIT_AND,
  '|'   =>  OP_BIT_OR,
  '^'   =>  OP_BIT_XOR,
  
  # comparison
  
  '='   =>  OP_EQUAL_TO,
  '!='  =>  OP_NOT_EQUAL_TO,
  '=='  =>  OP_IDENTICAL_TO,
  '!==' =>  OP_NOT_IDENTICAL_TO,
  '<'   =>  OP_LESS_THAN,
  '<='  =>  OP_LESS_THAN_OR_EQUAL_TO,
  '>'   =>  OP_GREATER_THAN,
  '>='  =>  OP_GREATER_THAN_OR_EQUAL_TO,

  # logic
  
  'and' =>  OP_AND,
  '&&'  =>  OP_AND,
  'or'  =>  OP_OR,
  '||'  =>  OP_OR,
  
  # string
  
  '.'   => OP_CONCAT,  
);


#### PARSER ####

function  mawk_parse_to_character (&$tokens, $character, $return_tokens_out_if_not_found = false) {

  $tokens_to_parse = array ();
  
  while (($token = array_shift ($tokens)) !== null) {
    
    if ($token == $character) {
      return  $tokens_to_parse;
    }
    
    $tokens_to_parse[] = $token;
  }
  
  if ($return_tokens_out_if_not_found)
    return  $tokens_to_parse;
  
  return  false;
}


function  mawk_parse_to_bracket_ends (&$tokens, $open_bracket, $close_bracket) {

  $tokens_to_parse = array ();
  
  $inner_count = 0;
  
  while (($token = array_shift ($tokens)) !== null) {
    
    if ($token == $open_bracket) {
    
      $inner_count++;
      
    } elseif ($token == $close_bracket) {
    
      if ($inner_count == 0) {
        return  $tokens_to_parse;
      }
      
      $inner_count--;
    }
    
    $tokens_to_parse[] = $token;
  }
  
  return  false;
}


function  mawk_parse_to_statement_ends (&$tokens) {

  $tokens_to_parse = array ();
  
  $inner_count = 0;
  
  while (($token = array_shift ($tokens)) !== null) {
    
    switch ($token) {
    
    case  '{'   :    
    
      $inner_count++;    
      break;
      
    case  '}'   :
    
      $inner_count--;
      
      if ($inner_count < 0)
        return  error ('parse_mawk', "too many '}' characters");
      
      break;
      
    case  ';'   :
    
      if ($inner_count == 0)
        return  $tokens_to_parse;
    }
    
    $tokens_to_parse[] = $token;
  }
  
  return  $tokens_to_parse;
}


function  mawk_parse_value (&$tokens, $value) {

  $token = array_shift ($tokens);
  
  return  array ($value);
}


function  mawk_parse_unary (&$tokens, &$fields_arr, &$indexes_arr) {

  $token = array_shift ($tokens);
  
  $op = $GLOBALS['unary_value_ops'][$token];

  $opd = mawk_parse_operand ($tokens, $fields_arr, $indexes_arr);
 
  if ($opd === false)
    return  false;
    
  return  array ($op, $opd);
}


function  mawk_parse_number (&$tokens) {

  $token = array_shift ($tokens);  
  
  if (preg_match ('/^[0-9]+$/', $token)) {
    $val = (int) $token;
  } else {
    $val = (float) $token;
  }
  
  return  array (OP_VALUE, $val);
}


function  mawk_parse_string (&$tokens) {

  $string = array();

  $token = array_shift ($tokens);
  $token = substr ($token, 1);
  $token1 = true;
  $end = false;

  if ($token == '"')
    return  array (OP_VALUE, '');
  
    
  while ($token !== '') {

    if ($token == '"') {
    
      $string[] = '';
    
      if ($token1) {
        $token1 = false;
        $token = array_shift ($tokens);
        continue;
      }
      
      $end = true;
    }
  
    if (!$end) {
    
      $len = strlen ($token);
        
      if ($token[$len-1] == '"') {
      
        $r = preg_match ('/(\\\*)"$/', $token, $matches);
        
        if ($matches && strlen ($matches[1]) % 2 == 0) {
          
          $string[] = @substr ($token, 0, -1);
          $end = true;
        }
      }
    }
    
    if ($end) {

      $string = implode (' ', $string);
      
      $string = stripcslashes ($string);
      
      return  array (OP_VALUE, $string);
    }
    
    $string[] = $token;
    $token = array_shift ($tokens);
    $token1 = false;
  }

  return  error ($GLOBALS['mawk_cmd'], "string unterminated");
}


function  mawk_parse_field_var ($token, &$fields_arr, &$indexes_arr) {


  $op = array (OP_FIELD_VAR);
    
  
  # split the field into parts
  
  $r = preg_match ("/([@]+)(.+)/", $token, $matches);
  
  if (!$r)
    return  false;
  
  
  # add the field array index
  
  $fa_idx = strlen ($matches[1]) - 1;
  
  $op[] = $fa_idx;

  
  # add the field index
  
  $f_idx = @$fields_arr[$fa_idx][$matches[2]];
  
  if (!is_int ($f_idx))
    return  error ($GLOBALS['mawk_cmd'], "field '$token' does not exist");
    
    
  $op[] = $f_idx;
  
  return  $op;
}


function  mawk_parse_index_var ($token, &$fields_arr, &$indexes_arr) {


  $op = array (OP_INDEX_VAR);
    
    
  # split the var into the three parts
  
  $parts = explode ('|', $token);
  
  if (count ($parts) != 3)
    return  error ($GLOBALS['mawk_cmd'], "index vars must have three parts, like : %key|ref|field");
    
  
  # split the field into parts
  
  $key_field = $parts[0];
  
  $r = preg_match ("/([%]+)(.+)/", $key_field, $matches);
  
  if (!$r)
    return  false;
  
  
  # add the field array index
  
  $fa_idx = strlen ($matches[1]) - 1;
  
  $op[] = $fa_idx;

  
  # add the field index to the var
  
  $f_idx = @$fields_arr[$fa_idx][$matches[2]];
  
  if (!is_int ($f_idx))
    return  error ($GLOBALS['mawk_cmd'], "field '$key_field' does not exist");
    
  $op[] = $f_idx;
  
    
  # add the field index to the indexes
  
  $indexes_arr[$fa_idx][$f_idx] = true;
  
  
  # add the reference to the op
    
  $ref = mawk_parse_var_section ($parts[1], $fields_arr, $indexes_arr);
  
  if ($ref === false)
    return  false;
    
  $op[] = $ref;

  
  # add the field index
  
  $field_name = mawk_parse_var_section ($parts[2], $fields_arr, $indexes_arr);
  
  if (is_string ($field_name)) {
  
    $field_idx = @$fields_arr[$fa_idx][$field_name];
  
    if (!is_int ($field_idx))
      return  error ($GLOBALS['mawk_cmd'], "field '$field_name' does not exist");
      
    $op[] = $field_idx;
      
  } else {
  
    $op[] = $field_name;
  }
  
  return  $op;
}



function  mawk_parse_run_var ($token, &$fields_arr, &$indexes_arr) {

  return  array (OP_RUN_VAR, substr ($token, 1));
}


function  mawk_parse_var_section ($token, &$fields_arr, &$indexes_arr) {

  switch ($token[0]) {
  
    case  '$' :
    
      return  mawk_parse_run_var ($token, $fields_arr, $indexes_arr);
  
    case  '@' :
    
      return  mawk_parse_field_var ($token, $fields_arr, $indexes_arr);
      
    case  '%' :
    
      return  mawk_parse_index_var ($token, $fields_arr, $indexes_arr);
      
    default :
    
      return  $token;
  }
}


function  mawk_parse_var (&$tokens, &$fields_arr, &$indexes_arr) {


  # check that is a var

  $c1 = $tokens[0][0];
  
  if ($c1 != '@' && $c1 != '$' && $c1 != '%')
    return;
    
    
  # split var into parts (on '.')
    
  $token = array_shift ($tokens);
    
  $parts = explode ('.', $token);
  
  
  # parse the first part of the var
  
  $var = mawk_parse_var_section ($parts[0], $fields_arr, $indexes_arr);
  
  if ($var === false)
    return  false;
    
    
  # parse the other parts of the var (if there are any)
    
  $parts_count = count ($parts);
    
  for ($i=1; $i<$parts_count; $i++) {
  
    $r = mawk_parse_var_section ($parts[$i], $fields_arr, $indexes_arr);
    
    if ($r === false)
      return  false;
  
    $var[] = $r;
  }

  return  $var;
}


function  mawk_parse_operand (&$tokens, &$fields_arr, &$indexes_arr) {


  $token1 = $tokens[0];  

  switch ($token1) {
  
    case  'true'    :   return    mawk_parse_value ($tokens, OP_TRUE);
    case  'false'   :   return    mawk_parse_value ($tokens, OP_FALSE);
    case  'null'    :   return    mawk_parse_value ($tokens, OP_NULL);
    case  '('       :   return    mawk_parse_enclosed_expression ($tokens, $fields_arr, $indexes_arr);
  }
  
  
  if (array_key_exists ($token1, $GLOBALS['unary_value_ops']))
    return  mawk_parse_unary ($tokens, $fields_arr, $indexes_arr);
      
  $r = mawk_parse_var ($tokens, $fields_arr, $indexes_arr);
  if ($r === false)
    return  false;
    
  if ($r !== null)
    return  $r;
    
  if ($token1[0] == '"')
    return  mawk_parse_string ($tokens, $fields_arr, $indexes_arr);
    
  if (preg_match ('/^[\-\+]?[0-9]*(\.[0-9]+)?$/', $token1))
    return  mawk_parse_number ($tokens);
    
  if (preg_match ('/^[_a-zA-Z][_a-zA-Z0-9]*$/', $token1))
    return  mawk_parse_function ($tokens, $fields_arr, $indexes_arr);
    
  # TODO: string


  return  error ($GLOBALS['mawk_cmd'], "unknown operand '$token1'");
}


function  mawk_parse_expression (&$tokens, &$fields_arr, &$indexes_arr, $in_list = false) {


#var_dump ($tokens);


  # check for empty expressions

  if (count ($tokens) == 0)
    return  error ($GLOBALS['mawk_cmd'], "empty expression");

    
  # get first operand
  
  $opd1 = mawk_parse_operand ($tokens, $fields_arr, $indexes_arr);
  
  if ($opd1 === false)
    return  false;
    
    
  # check for subsequent tokens
  
  if (count ($tokens) == 0)
    return  $opd1;
    
    
  # build the operation
    
  $opn = $opd1;
  $main_opr = null;
  
  while ($opr_token = array_shift ($tokens)) {
    
    
    # check for being in a list (e.g. in a function)
    
    if ($in_list && ($opr_token == ',' || $opr_token == ')')) {
      array_unshift ($tokens, $opr_token);
      return  $opn;
    }
    
    
    # get operator
    
    $opr = @$GLOBALS['binary_value_ops'][$opr_token];
    
    if ($opr === NULL)
      return  error ($GLOBALS['mawk_cmd'], "operator '$opr_token' not valid (at least not here)");
      
      
    # check to see if it's the same as the main one
    
    if ($main_opr !== null) {
    
      if ($opr != $main_opr)
        return  error ($GLOBALS['mawk_cmd'], "operator '$opr_token' is not the same as the main operator, and operators in the same group must be of the same type");
    
    } else {
    
      $main_opr = $opr;
      $opn = array ($main_opr, $opd1);
    }
    

    # check for more tokens
    
    if (count ($tokens) == 0)
      return  error ($GLOBALS['mawk_cmd'], "final operand required after operator operation");
    
    
    # get second operand
    
    $opd = mawk_parse_operand ($tokens, $fields_arr, $indexes_arr);
    
    if ($opd === false)
      return  false;
    
    $opn[] = $opd;
  }
  
    
  return  $opn;
}


function  mawk_parse_enclosed_expression (&$tokens, &$fields_arr, &$indexes_arr) {

  
  # remove opening bracket

  $opening_bracket = array_shift ($tokens);
  
  if ($opening_bracket != '(')
    return  error ($GLOBALS['mawk_cmd'], "test expression without an opening '('");
    
  
  # get the expression body tokens
  
  $expression_body_tokens = mawk_parse_to_bracket_ends ($tokens, '(', ')');
  
  if ($expression_body_tokens === false)
    return  error ($GLOBALS['mawk_cmd'], "expression with opening '(' not terminated correctly");

    
  # get expression body
  
  return  mawk_parse_expression ($expression_body_tokens, $fields_arr, $indexes_arr);
}


function  mawk_parse_assignment (&$tokens, &$fields_arr, &$indexes_arr) {


  # get the field to assign to
  
  $field = mawk_parse_var ($tokens, $fields_arr, $indexes_arr);

  if ($field === false)
    return  false;

  
  # get how to set the field
  
  $token = array_shift ($tokens);
  
  $assignment = @$GLOBALS['assignment_ops'][$token];
  
  if ($assignment === null)
    return  error ($GLOBALS['mawk_cmd'], "invalid assignment token '$token'");
  
  
  # get value

  $value = mawk_parse_expression ($tokens, $fields_arr, $indexes_arr);

  if ($value === false)
    return  false;
    
    
  # build op
  
  $op = array ($assignment, $field, $value);
  
  return  $op;
}


function  mawk_parse_function (&$tokens, &$fields_arr, &$indexes_arr) {


  $op = array (OP_FUNC);
  
  
  # get the name of the function
  
  $name = array_shift ($tokens);
  
  $alt_names = array (
    'echo'      => 'shell_echo',
    'error'     => 'mawk_error',
    'lowercase' => 'strtolower',
    'uppercase' => 'strtoupper',
  );
  
  $alt_name = @$alt_names[$name];
  
  $name = $alt_name ? $alt_name : $name;
  
  
  # check function exists
  
  if (!function_exists ($name))
    return  error ($GLOBALS['mawk_cmd'], "function '$name' does not exist");
    
  $op[] = $name;
  
  
  # check if is a func or a series of strings
  
  if (count ($tokens) == 0)
    return  error ($GLOBALS['mawk_cmd'], "no opening bracket '(' for function call");
    
  
  # check for empty execution
  
  $token = array_shift ($tokens);
  
  if ($token == '()')
    return  $op;
    
  if ($token != '(')
    return  error ($GLOBALS['mawk_cmd'], "no opening bracket '(' for function call");
    
  if (@$tokens[0] == ')') {
    array_shift ($tokens);
    return  $op;
  }
  
    
  # get first arg
  
  $arg = mawk_parse_expression ($tokens, $fields_arr, $indexes_arr, true);
  
  if ($arg == false)
    return  false;
    
  $op[] = $arg;
  
  
  # loop through subsequent args
    
  while (count($tokens) > 0) {
  
    $token1 = $tokens[0];
  
    if ($token1 == ')') {
      array_shift ($tokens);
      return  $op;
    }
  
    if ($token1 == ',')
      array_shift ($tokens);
      
    $arg = mawk_parse_expression ($tokens, $fields_arr, $indexes_arr, true);
    
    if ($arg === false)
      return  false;
      
    $op[] = $arg;
  }
  
  return  error ($GLOBALS['mawk_cmd'], "function not terminated correctly");
}


function  mawk_parse_if (&$tokens, &$fields_arr, &$indexes_arr) {


  # initiate operation

  $op = array (OP_IF);
  
  
  # get test tokens
  
  array_shift ($tokens);
  
  $tokens_to_parse = mawk_parse_to_block_begins ($tokens);

  if ($tokens_to_parse === false)
    return  false;
  
  
  # parse the test

  $test = mawk_parse_expression ($tokens_to_parse, $fields_arr, $indexes_arr);
  
  if ($test === false)
    return  false;
  
  $op[] = $test;
  
  
  # parse block

  $block = mawk_parse_block_body ($tokens, $fields_arr, $indexes_arr);
    
  if ($block === false)
    return  false;
    
  $op[] = $block;
  
  
  # if no more tokens return
  
  if (count ($tokens) == 0)
    return  $op;
  

  # parse else/elseif
  
  switch ($tokens[0]) {
  
  case  'else'    :   $last = mawk_parse_else  ($tokens, $fields_arr, $indexes_arr);  break;
  case  'elseif'  :   $last = mawk_parse_if    ($tokens, $fields_arr, $indexes_arr);  break;
  default         :
    return  error ($GLOBALS['mawk_cmd'], "[else|elseif] must come after [if]");
  }
  
  if ($last === false)
    return  false;
  
  
  # add last to op and return
  
  $op[] = $last;
  
  return  $op;
}


function  mawk_parse_else (&$tokens, &$fields_arr, &$indexes_arr) {

  $else = mawk_parse_block ($tokens, $fields_arr, $indexes_arr, 1);
  
  if ($else === false)
    return  false;
    
  if (count ($tokens) > 0)
    return  error ($GLOBALS['mawk_cmd'], "no ops can appear after [else] in the same statement");
    
  return  $else;
}


function  mawk_parse_op ($op) {

  return  array ($op);
}

function  mawk_parse_statement (&$tokens, &$fields_arr, &$indexes_arr) {


  $token1 = $tokens[0];

  switch ($token1) {
  
    # breaks
  
    case    'halt'                  :   return  mawk_parse_op (OP_HALT);
    case    'discard'               :   return  mawk_parse_op (OP_DISCARD);
    case    'stop'                  :   return  mawk_parse_op (OP_STOP);
    case    'finish_and_discard'    :   return  mawk_parse_op (OP_FINISH_AND_DISCARD);
    case    'finish_and_stop'       :   return  mawk_parse_op (OP_FINISH_AND_STOP);
    
    # other keywords
    
    case    'if'                  :   return  mawk_parse_if ($tokens, $fields_arr, $indexes_arr);

  }
  
  switch ($token1[0]) {
  
    case  '@' :
    case  '$' :
    case  '%' :

      return  mawk_parse_assignment  ($tokens, $fields_arr, $indexes_arr);
  }
    
      
  return  mawk_parse_function ($tokens, $fields_arr, $indexes_arr);
}


function  mawk_parse_block_statements (&$tokens, &$fields_arr, &$indexes_arr) {

  $op = array (OP_BLOCK);

  do {

  
    # get statement tokens
  
    $tokens_to_parse = mawk_parse_to_statement_ends ($tokens);
    
    if (count ($tokens_to_parse) == 0) {
      continue;
    }
    
      
    # parse statement

    $statement = mawk_parse_statement ($tokens_to_parse, $fields_arr, $indexes_arr);

    if ($statement === false)
      return  false;
      
    $op[] = $statement;
 
 
  } while (count ($tokens));
  
  return  $op;
}


function  mawk_parse_to_block_begins (&$tokens) {

  return  mawk_parse_to_character ($tokens, '{');
}


function  mawk_parse_to_block_ends (&$tokens) {

  return  mawk_parse_to_bracket_ends ($tokens, '{', '}');
}


function  mawk_parse_block_body (&$tokens, &$fields_arr, &$indexes_arr) {

  # get block body tokens

  $tokens_to_parse = mawk_parse_to_block_ends ($tokens);
  
  if ($tokens_to_parse === false)
    return  false;
    
    
  # parse block statements

  return  mawk_parse_block_statements ($tokens_to_parse, $fields_arr, $indexes_arr);
}


function  mawk_parse_block (&$tokens, &$fields_arr, &$indexes_arr, $shift = 0) {


  # shift tokens from front

  if ($shift) {
    for ($i=0; $i<$shift; $i++)
      array_shift ($tokens);
  }

  
  # check for initial opening
  
  $opening_bracket = array_shift ($tokens);
  
  if ($opening_bracket != '{')
    return  error ($GLOBALS['mawk_cmd'], "statement blocks must begin with a '{'");
  

  # parse block body
  
  return  mawk_parse_block_body ($tokens, $fields_arr, $indexes_arr);
}


function  mawk_parse_code ($code, $fields_arr,  $cmd = __FUNCTION__) {


  # set up command
  
  $GLOBALS['mawk_cmd'] = $cmd;
    

  # flip fields
  
  foreach ($fields_arr as $i => $fields) {
    
    $fields_arr[$i] = array_flip ($fields);
  }

  
  # set up indexes
  
  $indexes_arr = array ();
  
  foreach ($fields_arr as $i) {
  
    $indexes_arr[] = array ();
  }
  
  
  # split the code into lines
  
  $code = explode ("\n", $code);
  $parsed_code = array ();
  

  # build the tokens

  $tokens = array ();
  $code_count = count ($code);
  $last_line_continues = false;
  
  $binary_value_ops = $GLOBALS['binary_value_ops'];
  
  for ($i=0; $i<$code_count; $i++) {
  
    $line = trim ($code[$i]);
    
    if ($line == '' || $line[0] == '#')
      continue;
  

    # build the line tokens

    $line_tokens = explode (' ', $line);
    
    if ($i != 0 && !$last_line_continues && $line_tokens[0] != 'elseif' && $line_tokens[0] != 'else') {
      $tokens[] = ';';
    }
    
    foreach ($line_tokens as $token) {
    
      $token = trim ($token);
      
      if (strlen ($token) > 0)
        $tokens[] = $token;
    }
    
    
    # check if the line continues
    
    $last_line_token = $line_tokens[count($line_tokens)-1];
    
    if (@$binary_value_ops[$last_line_token]) {
      $last_line_continues = true;
    } elseif ($last_line_token == ',') {
      $last_line_continues = true;
    } else {
      $last_line_continues = false;
    }
  }
  

  
  # parse the code
  
  $parsed_line = mawk_parse_block_statements ($tokens, $fields_arr, $indexes_arr);
  
  if ($parsed_line === false)
    return  1;    # TODO: fix
  
  $parsed_code[] = $parsed_line;
  
  
  # remove empty indexes
  
  for ($i=0; $i<count($indexes_arr); $i++) {
  
    if (! $indexes_arr[$i])
      unset ($indexes_arr[$i]);
  }
  
  
  # build response
  
  $res = array (
    'code'    => $parsed_code,
    'indexes' => $indexes_arr,
  );
  
  #dump_yaml ($res); exit;
  
  return  $res;
}


function  mawk_clean_code ($mawk) {

  $lines = explode ("\n", $mawk);
  $new_lines = array ();
  
  for ($i=0; $i<count ($lines); $i++) {
  
    $line = $lines[$i];
  
    $trim_line = trim ($line);
    
    if (strlen ($trim_line) == 0 || $trim_line[0] == '#')
      continue;
    
    if (!@$off_set) {
      
      for ($j=0; $j<strlen ($line); $j++) {
        if ($line[$j] != ' ')
          break;
      }
      
      $off = $j;
      $off_set = true;
    }
    
    $new_lines[] = substr ($line, $off);
  }
  
  return  implode ("\n", $new_lines);
}


?>