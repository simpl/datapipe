<?php


function  imap_get_mailbox_list ($opts, $pipe, $cmd = __FUNCTION__) {

  # LIST, then parse for list of mailboxes
}


function  imap_mailbox_request ($opts, $pipe, $cmd = __FUNCTION__) {
  

  # set up functions for curl to parse

  $req = array (
  
    'init_functions' => array (
    
      'imap_check_mailbox',
      'imap_set_request',
    ),
  );

  
  # merge the opts and pass to curl for processing
  
  $opts = merge_opts_for_output ($opts, $req);
  
  return  curl ($opts, $pipe, $cmd, 'imap');
}


function  imap_get_mailbox_email_count ($opts, $pipe, $cmd = __FUNCTION__) {
  

  # set up functions for curl to parse

  $req = array (
  
    'init_functions' => array (
    
      'imap_check_mailbox',
      'imap_set_select_mailbox',
    ),  

    'finalize_functions' => array (
    
      'imap_parse_email_count',
    ),
  );

  
  # merge the opts and pass to curl for processing
  
  $opts = merge_opts_for_output ($opts, $req);
  
  return  curl ($opts, $pipe, $cmd, 'imap');
}


function  imap_get_email_headers ($opts, $pipe, $cmd = __FUNCTION__) {


  # set up functions for curl to parse

  $req = array (
  
    'init_functions' => array (
    
      'imap_check_mailbox',
      'imap_check_email_id',
      'imap_set_fetch_email_headers',
    ),  

    'finalize_functions' => array (
    
      'imap_parse_email_headers',
    ),
  );

  
  # merge the opts and pass to curl for processing
  
  $opts = merge_opts_for_output ($opts, $req);
  
  return  curl ($opts, $pipe, $cmd, 'imap');
}


function  imap_get_email_part ($opts, $pipe, $cmd = __FUNCTION__) {
  

  # unset opts that might affect the request
  
  unset ($pipe['dir']);
  unset ($pipe['file']);
  unset ($pipe['path']);
  
  
  # merge opts

  $merged_opts = merge_opts ($opts, $pipe);
  
  
  # perform search if necessary
  
  if ($merged_opts['tests']) {

    $opts = imap_search_for_email ($opts, $pipe, $cmd);
    
    if ($opts === false)
      return  false;
  }
  
  
  # set up functions for curl to parse

  $req = array (
  
    'init_functions' => array (
    
      'imap_check_mailbox',
      'imap_check_email_id',
      'imap_check_email_part_id',
      'imap_set_fetch_email_body_part',
    ),
    
    'finalize_functions' => array (
    
      'imap_parse_email_body_part',
    ),
  );

  
  # merge the opts and pass to curl for processing
  
  $opts = merge_opts_for_output ($opts, $req);
  
  return  curl ($opts, $pipe, $cmd, 'imap');
}


function  imap_get_email_size ($opts, $pipe, $cmd = __FUNCTION__) {


  # set up functions for curl to parse

  $req = array (
  
    'init_functions' => array (
    
      'imap_check_mailbox',
      'imap_check_email_id',
      'imap_set_fetch_email_size',
    ),

    'finalize_functions' => array (
    
      'imap_parse_email_size',
    ),
  );

  
  # merge the opts and pass to curl for processing
  
  $opts = merge_opts_for_output ($opts, $req);
  
  return  curl ($opts, $pipe, $cmd, 'imap');
}


function  imap_get_email_body ($opts, $pipe, $cmd = __FUNCTION__) {


  # set up functions for curl to parse

  $req = array (
  
    'init_functions' => array (
    
      'imap_check_mailbox',
      'imap_check_email_id',
      'imap_set_fetch_email_body',
    ),  

    'finalize_functions' => array (
    
      'imap_parse_email_body',
      'imap_parse_email_body_parts',
    ),
  );

  
  # merge the opts and pass to curl for processing
  
  $opts = merge_opts_for_output ($opts, $req);
  
  return  curl ($opts, $pipe, $cmd, 'imap');
}


function  imap_check_mailbox ($prefix, $func_args, $cmd, $debug) {


  # get opts and curlopts
  
  $opts = &$func_args['opts'];
  $curlopts = &$func_args['curlopts'];
  $res = &$func_args['res'];

  
  # get mailbox opt
  
  $mailbox = get_opt ($prefix, $opts, 'mailbox', 'INBOX');

  if (!check_opt_set_type ($cmd, $mailbox, 'mailbox', 'string'))
    return  false;
    
  unset ($opts['dir']);
  unset ($opts['path']);
  
  $opts['file'] = urlencode ($mailbox);
  $opts['mailbox'] = $mailbox;
  $res['mailbox'] = $mailbox;

  return  true;
}


function  imap_check_email_id ($prefix, $func_args, $cmd, $debug) {


  # get opts and curlopts
  
  $opts = &$func_args['opts'];
  $curlopts = &$func_args['curlopts'];
  

  # get id opt
  
  $email_id = get_opt ($prefix, $opts, 'email_id');

  if (!check_opt_set_type ($cmd, $email_id, 'email_id', 'integer'))
    return  false;
  
  
  # set the result email id
  
  $res['email_id'] = $email_id;
  

  return  true;
}


function  imap_check_email_part_id ($prefix, $func_args, $cmd, $debug) {


  # get opts and curlopts
  
  $opts = &$func_args['opts'];
  $curlopts = &$func_args['curlopts'];
  

  # get id opt
  
  $email_part_id = get_opt ($prefix, $opts, 'email_part_id');

  if (!check_opt_set_type ($cmd, $email_part_id, 'email_part_id', 'integer'))
    return  false;
  
  
  # set the result email part id
  
  $res['email_part_id'] = $email_part_id;
  

  return  true;
}


function  imap_set_request ($prefix, $func_args, $cmd, $debug) {

  
  $func_args['curlopts']['request'] = $func_args['opts']['request'];
  
  
  return  true;
}


function  imap_set_select_mailbox ($prefix, $func_args, $cmd, $debug) {


  $func_args['curlopts']['request'] = 'SELECT "' . $func_args['opts']['mailbox'] . '"';

  
  return  true;
}


function  imap_set_fetch_email_body ($prefix, $func_args, $cmd, $debug) {


  $func_args['curlopts']['request'] = 'FETCH ' . $func_args['opts']['email_id'] . ' BODY';

  
  return  true;
}


function  imap_set_fetch_email_body_part ($prefix, $func_args, $cmd, $debug) {


  $func_args['curlopts']['request'] = 'FETCH ' . $func_args['opts']['email_id'] . ' BODY[' . $func_args['opts']['email_part_id'] . ']';

  
  return  true;
}


function  imap_set_fetch_email_headers ($prefix, $func_args, $cmd, $debug) {


  $func_args['curlopts']['request'] = 'FETCH ' . $func_args['opts']['email_id'] . ' BODY[HEADER]';

  
  return  true;
}


function  imap_set_fetch_email_size ($prefix, $func_args, $cmd, $debug) {


  $func_args['curlopts']['request'] = 'FETCH ' . $func_args['opts']['email_id'] . ' RFC822.SIZE';

  
  return  true;
}


function  imap_parse_email_header ($prefix, $func_args, $cmd, $debug) {


  # get res
  
  $res = &$func_args['res'];
  $opts = &$func_args['opts'];
  
  
  # get tests
  
  $test         = $opts['header_parse_test'];
  $result_field = $opts['header_parse_result_field'];
  $result_type  = @$opts['header_parse_result_type'];
  $success_msg  = @$opts['header_parse_success_msg'];
  $failure_msg  = $opts['header_parse_failure_msg'];


  
  # parse the response body for lines
  
  $response_head = $res['response_head'];
  
  $response_head_lines = explode ("\n", $response_head);
  

  # parse the lines for values we want to keep
  
  foreach ($response_head_lines as $line) {
  
    # check for the email count
  
    $r = preg_match ($test, $line, $matches);
    
    if ($r) {
      $result = $matches[1];
      
      switch ($result_type) {
      
        case  'integer' :   $result = (int) $result;    break;
        case  'boolean' :   $result = (bool) $result;   break;
      }
      
      $res[$result_field] = $result;
      
      if ($success_msg) {
      
        $success_msg = str_replace ('$result', $result, $success_msg);
        
        debug_echo ($cmd, $success_msg);
      }
      
      return  true;
    }
  }


  return  error ($cmd, $failure_msg);
}


function  imap_parse_email_count ($prefix, $func_args, $cmd, $debug) {

  $opts = &$func_args['opts'];

  $opts['header_parse_test'] = '/^\* +([0-9]+) +EXISTS.*/';
  $opts['header_parse_result_field'] = 'email_count';
  $opts['header_parse_result_type'] = 'integer';
  $opts['header_parse_success_msg'] = "\$result emails found in mailbox $opts[mailbox]";
  $opts['header_parse_failure_msg'] = "could not find the email count in mailbox $opts[mailbox]";
  
  return  imap_parse_email_header ($prefix, $func_args, $cmd, $debug);
}


function  imap_parse_email_body ($prefix, $func_args, $cmd, $debug) {

  $opts = &$func_args['opts'];

  $opts['header_parse_test'] = '/^.*BODY *\((.*)\)\).*$/';
  $opts['header_parse_result_field'] = 'email_body';
  $opts['header_parse_failure_msg'] = "email body could not be found";
  
  return  imap_parse_email_header ($prefix, $func_args, $cmd, $debug);
}


function  imap_parse_email_body_parts ($prefix, $func_args, $cmd, $debug) {

  $res = &$func_args['res'];
  
  $body = $res['email_body'];

  $body_parts = imap_parse_body_parts ($body, $cmd);
  
  if ($body_parts === false)
    return  false;
    
  $body_parts_analysis = imap_analyze_body_parts ($body_parts, $cmd);
  
  if ($body_parts_analysis === false)
    return  false;
    
  $res['email_body'] = $body;
  $res['email_body_parsed'] = $body_parts;
  $res['email_body_parts'] = $body_parts_analysis;
  
  return  true;
}


function  imap_parse_body_parts ($body, $cmd, $debug = false) {


  # set up body parsing

  $parts = array ();
  $parts_stack = array ();
  $parts_stack[0] = &$parts;
  $in_string = false;
  $escaped = false;
    
    
  # check for multi-part
    
  if ($body[0] == '(') {
  
    $multipart = true;

  } else {
  
    $multipart = false;
    $body = "($body)";
  }
  

  # parse the body
  
  debug_echo ($cmd, "parsing email body for parts");
  
  while ($body) {
  
    unset ($part);
    $part = &$parts_stack[count($parts_stack)-1];
  
    #echo "$body\n";
  
    $c = $body[0];
    
    switch ($body[0]) {
    
      case  ' ' :
      
        $body = substr ($body, 1);
        break;
    
    
      case  '(' ;
      
        unset ($new_part);
        $new_part = array ();
        $part[] = &$new_part;
        $parts_stack[] = &$new_part;
        
        $body = substr ($body, 1);
        
        if ($debug) {
          echo "(\n";
        }
        break;
        
        
      case  ')' :
      
        array_pop ($parts_stack);
        
        $body = substr ($body, 1);
        
        if ($debug) {
          echo ")\n";
        }
        break;
        
        
      case  '"' :
    
        $r = preg_match ('/^"([^"\\\\]*(?:\\.[^"\\\\]*)*)"/', $body, $matches);
        
        if (!$r)
          return  error ($cmd, "could not parse IMAP body parts, failure: $body");
        
        $phrase = $matches[1];
        
        $part[] = $phrase;
        
        $body = substr ($body, strlen ($phrase) + 2);
        
        if ($debug) {
          echo "$phrase\n";
        }
        break;
      
      
      default :
      
        $r = preg_match ('/^([^ ()]+)/', $body, $matches);
        
        if (!$r)
          return  error ($cmd, "could not parse IMAP body parts, failure: $body");
          
        $phrase = $matches[1];
        
        $part[] = $phrase;
        
        $body = substr ($body, strlen ($phrase));
        
        if ($debug) {
          echo "$phrase\n";
        }
        break;
    }
  }
  
  
  $body_parts = array (
    'multipart' => $multipart,
    'parts'     => $parts,
  );

  return  $body_parts;
}


function  imap_analyze_body_parts ($body_parts, $cmd, $debug = false) {

  $multipart = $body_parts['multipart'];
  $parts_orig = $body_parts['parts'];
  $parts = array ();
  

  foreach ($parts_orig as $n => $part_orig) {
  
    if (!is_array ($part_orig))
      continue;
      
    $part = array ();
    
    $part['type'] = strtolower ($part_orig[0]);
    $part['subtype'] = strtolower ($part_orig[1]);
    
    $meta = $part_orig[2];
    
    if (is_array ($meta)) {
    
      for ($i=0; $i<count($meta); $i+=2) {
      
        $name = strtolower ($meta[$i]);
        $value = strtolower ($meta[$i+1]);
        
        $part[$name] = $value;
      }
    
    } else {
    
      return  error ($cmd, "cannot analyze section 3 of email body part $n");
    }
      
    $part['encoding'] = strtolower ($part_orig[5]);
    $part['size'] = (int) $part_orig[6];
      
    if (isset ($part_orig[7]))
      $part['padding'] = (int) $part_orig[7]; # ???
      
    $parts[] = $part;
  }
  
  return  $parts;
}


function  imap_parse_email_body_part ($prefix, $func_args, $cmd, $debug) {


  # get res
  
  $res = &$func_args['res'];
  $opts = &$func_args['opts'];
  
  
  # parse the response header
  
  $response_head = $res['response_head'];
  $response_body_check = trim ($res['response_body']);
  
  $response_head_lines = explode ("\n", $response_head);
  
  
  # set up building response body
  
  $response_body = array ();
  $add_line = false;
  $last_line = count ($response_head_lines) - 2;
  
  
  # loop through lines
  
  foreach ($response_head_lines as $i => $line) {
  
    
  
    if ($i == $last_line)
      break;
  
    if ($add_line) {
    
      $response_body[] = $line;
    
    } elseif (trim ($line) == $response_body_check) {
    
      $add_line = true;
    }
  }
  
  
  $last_idx = count ($response_body) - 1;
  $last_line = trim ($response_body[$last_idx]);
  $new_len = strlen ($last_line) - 1;
  $response_body[$last_idx] = substr ($last_line, 0, $new_len);
  
  
  # check how to merge the data
  
  $type = $opts['email_type'];
  $encoding = $opts['email_encoding'];
  
  
  # merge the data
  
  if ($encoding == 'base64') {
  
    $response_body = base64_decode (str_replace ("\n", '', implode ('', $response_body)));
    
  } else {
    
    $response_body = implode ('', $response_body);
  }
  
  
  # store as the response body
  
  $res['response_body'] = $response_body;
  $res['response_body_size'] = strlen ($response_body);
  
  
  # re-save to file if necessary
  
  $response_body_file = @$res['response_body_file'];
  
  if ($response_body_file) {
    file_put_contents ($response_body_file, $response_body);
  }
  
  return  true;
}


function  imap_parse_email_size ($prefix, $func_args, $cmd, $debug) {

  $opts = &$func_args['opts'];

  $opts['header_parse_test'] = '/.*RFC822.SIZE +([0-9]+).*/';
  $opts['header_parse_result_field'] = 'email_size';
  $opts['header_parse_result_type'] = 'integer';
  $opts['header_parse_success_msg'] = "email size: \$result";
  $opts['header_parse_failure_msg'] = "email size could not be found";
  
  return  imap_parse_email_header ($prefix, $func_args, $cmd, $debug);
}


function  imap_parse_email_headers ($prefix, $func_args, $cmd, $debug) {


  # get res
  
  $res = &$func_args['res'];

  
  # parse the response body for lines
  
  $response_head = $res['response_head'];
  
  $response_head_lines = explode ("\n", $response_head);
  
  
  # parse the lines for values we want to keep
  
  $name = '';
  $value = '';
  
  $email_headers = array ();

  foreach ($response_head_lines as $line) {
  
    if (strlen ($line) == 0)
      continue;
  
    if ($line[0] == '*')
      continue;
  
    if ($line[0] == ' ') {

      $value .= rtrim ($line);
      $email_headers[$name] = $value;
      
    } else {
    
      # check for the email count
  
      $r = preg_match ('/^([^:]+): *(.*)/', $line, $matches);
      
      if ($r) {
      
        # parse the header name and value (or beginning thereof)
      
        $name = $matches[1];
        $value = rtrim ($matches[2]);
        
        $email_headers[$name] = $value;
        
      } else {
      
        $name = '';
        $value = '';
      }
    }
  }
  

  # save headers
  
  $res['email_headers'] = $email_headers;

  return  true;
}


function  imap_search_for_email ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix
  
  $prefix = 'imap_search_for_email';

  
  # unset opts that might affect the request
  
  unset ($pipe['dir']);
  unset ($pipe['file']);
  unset ($pipe['path']);
  
  
  # merge opts

  $merged_opts = merge_opts ($opts, $pipe);
  
  
  # get tests opt
  
  $tests = get_opt ($prefix, $merged_opts, 'tests');

  if (!check_opt_set_type ($cmd, $tests, 'tests', 'array'))
    return  false;


  # perform serach of mailbox

  $r = imap_get_mailbox_email_count ($opts, $pipe, $cmd);
  
  if ($r === false)
    return  false;

  
  # get the count
  
  $email_count = $r['email_count'];
  
  
  # split the tests
  
  $header_tests = @$tests['headers'];
  $body_tests = @$tests['body'];
  
  
  # loop over the emails to get the headers
    
  $found = false;
    
  for ($i=$email_count; $i>=1; $i--) {
  
  
    # set the email id
  
    $email_id = $i;
    $part_id = false;
    $email_headers_res = null;
    $email_body_res = null;
    
    $opts['email_id'] = $i;
    
  
    # get the eamil headers if required
  
    if ($header_tests) {
  
      $email_headers_res = imap_get_email_headers ($opts, $pipe, $cmd);
      
      if ($email_headers_res === false)
        return  false;
        
        
      # get the returned headers and set as lower-case
        
      $email_headers = $email_headers_res['email_headers'];
      
      foreach ($email_headers as $name => $value) {
      
        $email_headers[strtolower ($name)] = $value;
      }
        
        
      # test the headers

      $found_headers = imap_test_values ($header_tests, $email_headers);

      if (!$found_headers)
        continue;
    }
      
      
    if ($body_tests) {

      $email_body_res = imap_get_email_body ($opts, $pipe, $cmd);
      
      if ($email_body_res === false)
        return  false;
        
        
      # get the returned headers and set as lower-case
        
      $email_body_parts = $email_body_res['email_body_parts'];
      

      # run through the parts 
      
      foreach ($email_body_parts as $part_idx => $part) {
      
        $found_part = imap_test_values ($body_tests, $part);
        
        if ($found_part) {
          $part_id = $part_idx + 1;
          break;
        }
      }
      
      if (!$found_part)
        continue;
    }
    
    $found = true;
    break;
  }
    
    
  # check if found or not
    
  if (!$found)
    return  error ($cmd, "email not found with the search criteria");
  
  
  # display success message
  
  $msg = "email found with id $email_id";
  
  if ($part_id)
    $msg .= " part $part_id";
  
  debug_echo ($cmd, $msg);
    
    
  # create res
  
  $res = array (
    'email_id' => $email_id,
  );
  
  if ($part_id) {
 
    $res['email_part_id'] = $part_id;
 
    foreach ($part as $name => $value) {
    
      $res["email_$name"] = $value;
    }
  }
  
    
  # merge res with headers and body
    
  $res = merge_opts_for_output ($opts, $res);
  
  if ($email_headers_res)
      $res = merge_opts_for_output ($res, $email_headers_res);
  
  if ($email_body_res)
      $res = merge_opts_for_output ($res, $email_body_res);
  
  ksort ($res);
  
  return  $res;
}


function  imap_test_values ($tests, $values) {


  foreach ($tests as $name => $test_list) {
  

    # get the value of the header returned
  
    $name = strtolower ($name);
    
    $value = @$values[$name];

    if ($value === null)
      return  false;
    
    
    # if single test, then make an array
    
    if (!is_array ($test_list))
      $test_list = array ($test_list);
    
    
    # perform the tests
    
    $found = false;
    
    foreach ($test_list as $test) {

    
      # do a regex check
    
      if (preg_match ("/^\/.*\/[eimsuxADJSUX]*$/", $test)) {
      
        $r = preg_match ($test, $value);
        
        if ($r) {

          $found = true;
          break;
        }
        
        
      # do a simple comparison test
        
      } elseif ($value === $test) {
      
        $found = true;
        break;
      }
    }

    if (!$found)
      return  false;
  }

  return  true;
}





?>