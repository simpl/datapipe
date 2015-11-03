<?php


function  http_request ($opts, $pipe = null, $cmd = __FUNCTION__) {


# set prefix

  $prefix = 'http';

  
  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'url');
  
  
  # get execute opt
 
  $execute = get_opt_config_value ($prefix, $opts, 'execute', true);

  if (!check_opt_set_type ($cmd, $execute, 'execute', 'boolean'))
    return  false;
    
    
  # check if we should execute or not
  
  if (!$execute)
    return;
  
  
  # get debug opt

  $debug = get_opt_config_value ($prefix, $opts, 'debug', false);
  
  if (!check_opt_set_type ($cmd, $debug, 'debug', 'boolean'))
    return  false;

  
  # get request method opt
  
  $request_method = get_opt_config_value ($prefix, $opts, 'request_method', 'get');
echo "$request_method\[1]\n";
  if (!check_opt_if_set_type ($cmd, $request_method, 'request_method', 'http_request_method'))
    return  false;
    
    
  # get post args opt
  
  $post_args = get_opt ($prefix, $opts, 'post_args');
  
  if (!check_opt_if_set_type ($cmd, $post_args, 'post_args', 'array'))
    return  false;
  
  
  # get post form opt
  
  $form_name = get_opt ($prefix, $opts, 'form_name');
  
  if (!check_opt_if_set_type ($cmd, $form_name, 'form_name', 'string'))
    return  false;
  
  
  # get response body opt
    
  $response_body = get_opt ($prefix, $opts, 'response_body');
  
  if (!check_opt_if_set_type ($cmd, $response_body, 'response_body', 'string'))
    return  false;
  
  
  # check to see if we're posting
  
  if ($post_args || $form_name)
    $request_method = 'post';
    
  if ($request_method == 'post' && $response_body) {
  
    debug_echo ($cmd, "performing a post request and there was a previous request, so will search for action, method and inputs");
    
    $opts = http_parse_form ($opts, $form_name, $cmd);
    
    if ($opts === false)
      return  false;
      
    $post_args = $opts['post_args'];
  }
echo "$request_method\[2\]\n";
  
  # get base url from url|response_location|request opt
  
  $base_url_opts = array ('url', 'response_location', 'form_action', 'response_form_action', 'request_url');

  foreach ($base_url_opts as $opt) {
  
    $base_url = get_opt ($prefix, $opts, $opt);
    
    if (!check_opt_if_set_type ($cmd, $base_url, $opt, 'string'))
      return  false;
    
    if ($base_url)
      break;
  }
  
  if (!$base_url)
    return  opt_not_set_msg ($cmd, implode (',', $base_url_opts));
  
  
  # get get args opt
    
  $get_args = get_opt ($prefix, $opts, 'get_args');
  
  if (!check_opt_if_set_type ($cmd, $get_args, 'get_args', 'array,string'))
    return  false;
  

  # get headers opt
  
  $request_headers = get_opt ($prefix, $opts, 'headers');
    
  if (!check_opt_if_set_type ($cmd, $request_headers, 'headers', 'array_of_strings'))
    return  false;

    
  # get auto redirect opt
  
  $auto_redirect = get_opt ($prefix, $opts, 'auto_redirect', true);
    
  if (!check_opt_set_type ($cmd, $auto_redirect, 'auto_redirect', 'boolean'))
    return  false;
    

  # get authorization opt
  
  $authorization = get_opt ($prefix, $opts, 'authorization');
  
  if (!check_opt_if_set_type ($cmd, $authorization, 'authorization', 'array'))
    return  false;
  
  
  # set authorization if necessary
  
  if ($authorization) {

  
    # get authorization:usr opt
  
    $usr = get_opt ($prefix, $authorization, 'usr');
    
    if (!check_opt_set_type ($cmd, $usr, 'authorization:usr', 'string,integer'))
      return  false;
        
    $usr = (string) $usr;
        
        
    # get authorization:pwd opt
        
    $pwd = get_opt ($prefix, $authorization, 'pwd');
    
    if (!check_opt_set_type ($cmd, $pwd, 'authorization:pwd', 'string,integer'))
      return  false;
  
    $pwd = (string) $pwd;
  
  
    # get authorization:type opt
  
    $type = get_opt ($prefix, $authorization, 'type', 'Basic');
    
    if (!check_opt_set_type ($cmd, $type, 'authorization:type', 'string'))
      return  false;
      
    
    # add the authorization header to the list of headers
  
    $auth_header = "Authorization: $type " . base64_encode ("$usr:$pwd");
  
    if (!$request_headers)
      $request_headers = array();
      
    $request_headers[] = $auth_header;
  }
      
  
  # get request retries opt
    
  $request_retries = get_opt_config_value ($prefix, $opts, 'request_retries', 3);
  
  if (!check_opt_set_type ($cmd, $request_retries, 'request_retries', 'integer'))
    return  false;
  
  
  # get download msg opt
  
  $download_msg = get_opt ($prefix, $opts, 'download_msg');
  
  if (!check_opt_if_set_type ($cmd, $download_msg, 'download_msg', 'string'))
    return  false;
    
  
  # get use downloaded file opt

  $use_saved_file = get_opt_config_value ($prefix, $opts, 'use_saved_file', true);
  
  if (!check_opt_set_type ($cmd, $use_saved_file, 'use_saved_file', 'boolean'))
    return  false;

    
  # get backup old downloaded files

  $backup_old_saved_files = get_opt_config_value ($prefix, $opts, 'backup_old_saved_files', true);
  
  if (!check_opt_set_type ($cmd, $backup_old_saved_files, 'backup_old_saved_files', 'boolean'))
    return  false;
    

  # get checked downloaded file opt

  $checked_saved_file = get_config_value ('http_checked_saved_file');

  if (!check_opt_if_set_type ($cmd, $checked_saved_file, 'http_checked_saved_file', 'string'))
    return  false;
  
  
  # get download to file opt
  
  $save_to_file = get_opt ($prefix, $opts, 'save_to_file');
  
  if (!check_opt_if_set_type ($cmd, $save_to_file, 'save_to_file', 'string'))
    return  false;
  
  
  # get download progress update opt
  
  $download_progress_update = get_opt_config_value ($prefix, $opts, 'download_progress_update', true);
  
  if (!check_opt_set_type ($cmd, $download_progress_update, 'download_progress_update', 'boolean'))
    return  false;
  
  
  # get max response body size opt
  
  $max_response_body_size = get_opt_config_value ($prefix, $opts, 'max_response_body_size', 32*1024*1024);
  
  if (!check_opt_if_set_type ($cmd, $max_response_body_size, 'max_response_body_size', 'integer'))
    return  false;
    
  
  # get save request head to file opt
  
  $request_head_file = get_opt ($prefix, $opts, 'save_request_head_to_file');
  
  if (!check_opt_if_set_type ($cmd, $request_head_file, 'save_request_head_to_file', 'string'))
    return  false;

    
  # get save request body to file opt
  
  $request_body_file = get_opt ($prefix, $opts, 'save_request_body_to_file');
  
  if (!check_opt_if_set_type ($cmd, $request_body_file, 'save_request_body_to_file', 'string'))
    return  false;
    
  
  # get save head to file opt

  $response_head_file = get_opt ($prefix, $opts, 'save_head_to_file');
  
  if (!check_opt_if_set_type ($cmd, $response_head_file, 'save_head_to_file', 'string'))
    return  false;
    
    
  # get save body to file opt
  
  $response_body_file = get_opt ($prefix, $opts, 'save_body_to_file');
    
  if (!check_opt_if_set_type ($cmd, $response_body_file, 'save_body_to_file', 'string'))
    return  false;
    

  # get parse response form opt
  
  $parse_response_form = get_opt_config_value ($prefix, $opts, 'parse_response_form');
  
  if (!check_opt_if_set_type ($cmd, $parse_response_form, 'parse_response_form', 'string'))
    return  false;
    
    
  # get parse response hidden inputs opt
  
  $parse_response_form_args = get_opt_config_value ($prefix, $opts, 'parse_response_form_args', false);
  
  if (!check_opt_set_type ($cmd, $parse_response_form_args, 'parse_response_form_args', 'boolean'))
    return  false;
  
  
  # check to see if we should use a downloaded file
  
  if ($use_saved_file) {


    # check if response body file exists
  
    if (file_exists ($response_body_file)) {
    
      debug_echo ($cmd, "using saved response body file instead of executing the HTTP request : $response_body_file");

      $res['response_body_file']  = $response_body_file;
      $res['response_file']       = $response_body_file;
      $res['file']                = $response_body_file;
      
      return  $res;
    }
  
  
    # check if the save to file exists
  
    if (file_exists ($save_to_file)) {
    
      debug_echo ($cmd, "using saved file instead of executing the HTTP request : $save_to_file");

      $res['response_file'] = $save_to_file;
      $res['file']          = $save_to_file;
      
      return  $res;
    }
    

    # return immediately if there's a checked downloaded file

    if ($checked_saved_file) {
    
      $res['response_file'] = $checked_saved_file;
      $res['file']          = $checked_saved_file;
    
      return  $res;
    }
  }
  
  
  # initiate request and $res
    
  $c = curl_init();
  
  if (!$c)
    return  error ($cmd, "could not create HTTP request");
    
  curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
  curl_setopt ($c, CURLOPT_HEADER, true);
  curl_setopt ($c, CURLINFO_HEADER_OUT, true);
  
  $res = array (); 
  
  
  # set request url

  if (is_array ($get_args))
    $get_args = http_build_query ($get_args);
    
  if (is_string ($get_args)) {
  
    $url = "$base_url?$get_args";
  
  } else {
    $url = $base_url;
  }
  
  curl_setopt ($c, CURLOPT_URL, $url);
  
  if ($debug) {
    debug_echo ($cmd, "initiating HTTP request : $url");
  }
    
    
  # add headers to request

  if ($request_headers) {
  
    curl_setopt ($c, CURLOPT_HTTPHEADER, $request_headers);
    
    if ($debug) {
    
      debug_echo ($cmd, "setting HTTP request headers :");
      debug_dump_yaml ($request_headers, true);
    }
  }
    
    
  # add body to request
  
  $request_body = '';
     
  if ($post_args) {
    
    foreach ($post_args as $key => $value) {
      $request_body .= urlencode ($key) . '=' . urlencode ($value) . '&';
    }
    
    $request_body = rtrim ($request_body, '&');

    curl_setopt ($c, CURLOPT_POST, strlen ($request_body));
    curl_setopt ($c, CURLOPT_POSTFIELDS, $request_body);
        
    $request_method = 'post';
    
    if ($debug) {
    
      debug_echo ($cmd, "HTTP post args :");
      debug_dump_yaml ($post_args, true);
    }
    
  } else {
  
    switch ($request_method) {
    
      case  'post' :
        
        curl_setopt ($c, CURLOPT_POST, 1);
    }
  }
  
  
  # set up downloading to file if set
  
  if ($save_to_file) {
  
  
    # backup old response file if set and exists
  
    if (is_file ($save_to_file) && $backup_old_saved_files && !backup_file ($save_to_file, null, $cmd))
      return  error ($cmd, "could not backup previously saved file : $save_to_file");
      

    # create parent directory of response file
  
    if (!make_dir ($opts, dirname ($save_to_file), $cmd))
      return  false;
  
  
    # create file handler for response file
  
    $save_to_file_handle = @fopen ($save_to_file, "w");
  
    if (!$save_to_file_handle)
      return  error ($cmd, "could not open response file : $save_to_file");

      
    # add response file handler to request
      
    curl_setopt ($c, CURLOPT_FILE, $save_to_file_handle);
    
    
    # display message and add to $res
    
    debug_echo ($cmd, "saving response to file : $save_to_file");
    
    $res['response_file'] = $save_to_file;
    $res['file'] = $save_to_file;
  }
  
  
  # add download progress update function if set
  
  if ($download_progress_update) {

    curl_setopt ($c, CURLOPT_PROGRESSFUNCTION, 'curl_transfer_progress');
    curl_setopt ($c, CURLOPT_NOPROGRESS, false);
  }
  
  
  # display download message
  
  if (!$download_msg)
    $download_msg = "executing HTTP request : $url";
  
  debug_echo ($cmd, "$download_msg ...");
  
  
  # execute request
  
  for ($i=0; $i<$request_retries; $i++) {
  
    $r = curl_exec($c);

    if ($r)
      break;
  }
  
  if (!$r)
    return  error ($cmd, "could not execute HTTP request : $url");
  
  
  # split the response into header and body
  
  $request_head = curl_getinfo ($c, CURLINFO_HEADER_OUT);
  $response_head_size = curl_getinfo ($c, CURLINFO_HEADER_SIZE);
  
  if ($save_to_file) {
  
    $handle = fopen ($save_to_file, 'r');
    $response_head = trim (fread ($handle, $response_head_size));    
    
    $stat = fstat ($handle);
    $response_body_size = $stat['size'] - $response_head_size;
    
    if ($response_body_size > $max_response_body_size) {
    
      warning ($cmd, "response body size is greater than max, so is not set : $response_body_size > $max_response_body_size");
    
      $response_body = "[$save_to_file]";
      $no_reset_response_body_size = true;
      
    } else {
      
      $response_body = trim (fread ($handle, $response_body_size));
    }
    
    fclose ($handle);
  
  } else {
    
    $response_head = trim (substr ($r, 0, $response_head_size));
    $response_body = trim (substr ($r, $response_head_size));
  }
  
  
  $response_head_size = strlen ($response_head);
  
  if (!@$no_reset_response_body_size)
    $response_body_size = strlen ($response_body);
  
  
  # setup response header parsing
  
  $headers = explode ("\n", $response_head);
  $response_headers = array ();
  $response_cookies = array ();
  $pipe_headers = array ();
  
  
  # parse response headers
  
  foreach ($headers as $header) {
  
  
    # clean and add response header
  
    $header = trim ($header);
    
    if ($header != '')
      $response_headers[] = $header;
  
  
    # parse cookies
  
    if (substr ($header, 0, 11) == 'Set-Cookie:') {

    
      # add cookie to pipe headers
    
      $full_cookie = trim (substr ($header, 11));  
      
      $pipe_headers[] = "Cookie: $full_cookie";

      
      # add cookie to cookies
      
      $cookie = strstr ($full_cookie, ';', true);
      $name = urldecode (strstr ($cookie, '=', true));
      $val = urldecode (substr (strstr ($cookie, '='), 1));
      
      $response_cookies[$name] = $val;
    }
    
    
    # parse location
    
    if (substr ($header, 0, 9) == 'Location:') {
      $res['response_location'] = trim (substr ($header, 9));
    }
  }
  
  
  # add main request and response variables to $res

  $res['headers']             = $pipe_headers; 
  
  $res['request_url']         = $base_url;
  $res['request_get_args']    = $get_args;
  $res['request_full_url']    = $url;
  $res['request_method']      = $request_method;
  $res['request_post_args']   = $post_args;
  $res['request_headers']     = $request_headers;
  
  $res['response_headers']    = $response_headers;
  $res['response_cookies']    = $response_cookies;
  $res['response_head_size']  = $response_head_size;
  $res['response_body_size']  = $response_body_size;
  
  
  # parse response body for JSON
  
  $parsed_body = @json_decode ($response_body, true);
  
  if (!is_null ($parsed_body))
    $res['response_body_json_decoded'] = $parsed_body;
  
  
  # save data to files if set and save in $res
  
  $save_to_files = array (
    'request_head'   => array ($request_head_file,   $request_head),
    'request_body'   => array ($request_body_file,   $request_body),
    'response_head'  => array ($response_head_file,  $response_head),
    'response_body'  => array ($response_body_file,  $response_body),
  );
  
  foreach ($save_to_files as $name => $vars) {
  
    $file = $vars[0];
    $data = $vars[1];
    
    if ($file) {
  
      $report_name = str_replace ('_', ' ', $name);
  
      if (is_file ($file) && $backup_old_saved_files && !backup_file ($file, null, $cmd))
      return  error ($cmd, "could not backup previously saved $report_name file : $file");
    
      if (!file_put_contents ($file, $data))
        return  error ($cmd, "could not save $report_name to file : $file");
    
      debug_echo ($cmd, "$report_name saved to file : $file");
    
      $res["${name}_file"] = $file;
    }
    
    $res[$name] = $data;
  }
    
  
  # parse response hidden inputs if set
  
  if ($parse_response_form_args) {
  
    // we don't pass the $opts as it does not work properly, and $pipe needs to be second
  
    debug_echo ($cmd, "parsing response for post action url and hidden args");
  
    $res = http_parse_form ($parse_response_form, $res, $cmd, true);
  }

  

  # close handlers
  
  curl_close ($c);
  
  if (@$save_to_file_handle)
    fclose ($save_to_file_handle);

    
  # sort $res, display and return
  
  ksort ($res);
  
  if ($debug) {
    debug_echo ($cmd, "HTTP response:");
    @debug_dump_yaml ($res, true);
  }
    
    
  # check for automatic redirection
    
  $response_location = @$res['response_location'];
    
  if ($response_location && $auto_redirect) {
  
    debug_echo ($cmd, "automatically redirecting to url : $response_location");
  
    $res = merge_opts_for_output ($res, $opts);
    
    
    # avoid infinite loops because the url opt is checked before the response_location opt
    
    unset ($res['form_name']);
    unset ($res['post_args']);
    unset ($res['request_method']);
    unset ($res['url']);

    return  http_request ($res);
  }
  
    
  return  $res;
}





function  http_parse_form (&$opts, $pipe, $cmd = __FUNCTION__, $response_form = false) {


  # set prefix

  $prefix = 'http';


  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'form_name');
  

  # get request_url opt
  
  $request_url = get_opt ($prefix, $opts, 'request_url');
  
  if (!check_opt_set_type ($cmd, $request_url, 'request_url', 'string'))
    return  false;
    
  
  # get response body opt
  
  $response_body = get_opt ($prefix, $opts, 'response_body');
  
  if (!check_opt_if_set_type ($cmd, $response_body, 'response_body', 'string'))
    return  false;
  
  
  # return if no response body is found
  
  if (!$response_body) {
  
    warning ("no response body to parse for hidden post args");
  
    return  $opts; 
  }
    
    
  # get post form opt
  
  $form_name = get_opt ($prefix, $opts, 'form_name', '');
  
  if (!check_opt_set_type ($cmd, $form_name, 'form_name', 'string'))
    return  false;
    
    
  # display message
  
  if ($form_name) {
  
    debug_echo ($cmd, "parsing response form '$form_name' for action, method and inputs");
  
  } else {
  
    debug_echo ($cmd, "parsing all forms (if any) for action, method and inputs");
  }
    
    
  # parse the response body for forms
  
  $form_parse_logic = "

    elements:
      - form:
          attributes:
            name: $form_name
            action:
            method:
              
          elements:
            - input:
                label: inputs
                attributes:
                  !type: hidden
                  name:
                  value:

            - button:
                label: inputs
                attributes:
                  name:
                  value:
  ";
        
  $form_parse = array (
  
    # TODO: add user-defined message
  
    'document'      =>  $response_body,
    'doc_type'      =>  'HTML',
    'result_type'   =>  'forms',
    'logic'         =>  $form_parse_logic,
    'merge_results' =>  true,
  );
    
  $form = xml_parse_document ($form_parse, null, $cmd);

  if (!$form) {
  
    if ($form_name) {
    
      warning ($cmd, "form '$form_name' not found in response body");
    
    } else {
    
      debug_echo ($cmd, "no forms found in the response body");
    }
    
    return  $opts;
  }
  
    
  # set the variables
  
  $form_action = $form['action'];
  $form_method = $form['method'];
  $form_name   = $form['name'];
  $form_inputs = $form['inputs'];
  
  
  # set the form method
  
  $opts['form_method'] = $form_method;
  
  if ($response_form) {
    $opts['response_form_method'] = $form_method;
  }
  
    
  # build the full form action
  
  if ($form_action) {
    
    
    # check for a full URL
    
    if (substr ($form_action, 0, 7) != 'http://' && substr ($form_action, 0, 8) != 'https://') {
    
      $r = preg_match ('/^([^?]*)(.*)/', $request_url, $matches);

      $url = $matches[1];
      $get_args = $matches[2];

      
      if ($form_action[0] == '/') {

        $r = preg_match ('/^(http[s]?:\/\/[^\/]*).*/', $url, $matches);
      
      } else {

        $r = preg_match ('/^(http[s]?:\/\/.*\/?)[^\/]*/', $url, $matches) . '/';
      }

      $form_action_base_url = $matches[1];
      
      $form_action = $form_action_base_url . $form_action;  #. $get_args;
    }
      
      
    # save the form action
    
    debug_echo ($cmd, "form action url found : $form_action");
      
    if ($response_form) {
      $opts['response_form_action'] = $form_action;
    }
      
    $opts['form_action'] = $form_action;
  }
      
  
  # build string for displaying info about hidden post args
  
  if ($form_name) {
  
    $form_name_str = "in form '$form_name'";
  
  } else {
  
    $form_name_str = "in all forms";
  }
  
  
  # add the hidden arguments to the hidden inputs and piped post args
  
  if (count ($form_inputs) == 0) {
  
    debug_echo ($cmd, "no hidden form inputs were found $form_name_str");
  
    return  $opts;
  }
  
  
  # build the hidden inputs
  
  $inputs = array ();
  $form_inputs_count = count ($form_inputs);
  
  for ($i=0; $i<$form_inputs_count; $i++) {
  
    $form_input = $form_inputs[$i];
    
    $name = $form_input['name'];
    $value = @$form_input['value'];
    $value = $value ? $value : '';
    
    $inputs[$name] = $value;
  }
  
  $form_inputs = $inputs;
  
  
  # display info about hidden args found
  
  debug_echo ($cmd, "the following hidden form inputs were found $form_name_str :");
  debug_dump_yaml ($form_inputs, true);
  
  
  # save the hidden inputs
  
  $opts['form_inputs'] = $form_inputs;
  
  if ($response_form) {
    $opts['response_form_inputs'] = $form_inputs;
  }
  
  
  # check for args
  
  $args_key = "${form_method}_args";
  
  
  # get appropriate args opt
      
  $args = get_opt ($cmd, $opts, $args_key);
  
  if (!check_opt_if_set_type ($cmd, $args, $args_key, 'array'))
    return  false;
  

  # merge the arts if necessary

  if ($args) {
    
    # check to see if is a string or an array
    
    if (is_string ($args)) {
    
      if (strlen ($args) == 0) {
    
        $args = $form_inputs;
        
      } else {
      
        $args .= '&' . http_build_query ($form_inputs);
      }
    
    } elseif (is_array ($args)) {
    
      $args = array_merge ($form_inputs, $args);

    } else {
      
      return  error ($cmd, "option '${form_method}_args' is not a string or array");
    }
    
  } else {
  
    $args = $form_inputs;
  }
    
    
  # save the args
    
  $opts[$args_key] = $args;  
  
  if ($response_form) {
    $opts["response_$args_key"] = $args;
  }
  

  # sort response and return
    
  ksort ($opts);

  return  $opts;
}


function  http_check_saved_file ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'http';


  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'file');

  
  # get use downloaded file opt
  
  $use_saved_file = get_opt_config_value ($prefix, $opts, 'use_saved_file', true);
  
  if (!check_opt_set_type ($cmd, $use_saved_file, 'use_saved_file', 'boolean'))
    return  false;
    
    
  # save the use download file opt and update
  
  $old_http_use_saved_file = get_config_value ('http_use_saved_file');
  
  set_config_value ('http_use_saved_file', $use_saved_file);
  set_config_value ('http_old_use_saved_file', $old_http_use_saved_file);
  
  
  # return if not using downloaded file
  
  if (!$use_saved_file) {
    return  array ('file' => null);
  }
  
  
  # get file opt
  
  $file = get_opt ($prefix, $opts, 'file');
  
  if (!check_opt_set_type ($cmd, $file, 'file', 'string'))
    return  false;
  
  
  # set the old http execute
  
  $old_http_execute = get_config_value ('http_execute');

  set_config_value ('http_old_execute', $old_http_execute);
  
  
  # check to see if the file exists already
  
  if (!file_exists ($file))
    return  array ('file' => null);
    
    
  # cancel http request execution
  
  debug_echo ($cmd, "using saved file '$file' instead of executing the HTTP request");
  
  set_config_value ('http_execute', false);
  
  
  # store file path and return
  
  set_config_value ('http_checked_saved_file', $file);
  
  return  array (
    'file' => $file,
    'response_file' => $file
  );
}


function  http_use_checked_saved_file ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'http';
  

  # merge opts

  $opts = merge_opts ($opts, $pipe, 'use_saved_file');
  
  
  # get use downloaded file opt
  
  $use_saved_file = get_opt_config_value ($prefix, $opts, 'use_saved_file', true);
  
  if (!check_opt_set_type ($cmd, $use_saved_file, 'use_saved_file', 'boolean'))
    return  false;
  
  
  # reset use download file opt
  
  $old_use_saved_file = get_config_value ('http_old_use_saved_file');
  
  set_config_value ('http_use_saved_file', $old_use_saved_file);
  
  
  # check to see if should use downloaded file or not
  
  if (!$use_saved_file)
    return  $pipe;
  

  # restore http request execution config
  
  $http_execute = get_config_value ('http_old_execute');
  
  set_config_value ('http_execute', $http_execute);
  
  
  # get checked downloaded file opt
  
  $file = get_config_value ('http_checked_saved_file');
    
  if (!check_opt_if_set_type ($cmd, $file, 'http_checked_saved_file', 'string'))
    return  false;
    
    
  # if file does not exist just pipe
    
  if (!$file)
    return  $pipe;
    
  
  # reset config and return
  
  set_config_value ('http_checked_saved_file', null);
  
  
  return  array (
    'file' => $file,
    'response_file' => $file,
  );
}



?>