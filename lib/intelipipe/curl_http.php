<?php

# TO ADD: User-Agent (have a bunch of options)
# TO ADD: other headers

$req = <<<'END'
1)
Request Method:GET
Status Code:200 OK
Request Headersview source
Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
Accept-Encoding:gzip, deflate, sdch
Accept-Language:en-GB,en;q=0.8,en-US;q=0.6,tr;q=0.4,pt;q=0.2,fr;q=0.2,it;q=0.2
Cache-Control:max-age=0
Connection:keep-alive
Cookie:cmTPSet=Y; CoreID6=76955050680714285460643&ci=50530000|ALPHABRODER; 50530000|ALPHABRODER_clogin=v=1&l=1428546064&e=1428547864742; __utmt=1; __utma=60293117.2021488460.1428546065.1428546065.1428546065.1; __utmb=60293117.1.10.1428546065; __utmc=60293117; __utmz=60293117.1428546065.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); wp9385=CWBYDDDDDDKZTLJVCI-YILM-XYZM-BBVU-JZHHUCVLKTYKDXJLWWJLV-KUBA-XUXH-BWUX-ZIXVXWIVXYKKDHsioHIkhKLk_Jht
Host:www.alphabroder.com
User-Agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/40.0.2214.111 Chrome/40.0.2214.111 Safari/537.36
Response Headersview source
Connection:Keep-Alive
Content-Type:text/html
Date:Thu, 09 Apr 2015 02:21:08 GMT
Keep-Alive:timeout=10, max=93
Server:IBM_HTTP_Server
Transfer-Encoding:chunked

2)
Remote Address:66.101.206.23:443
Request URL:https://www.alphabroder.com/cgi-bin/online/webshr/shr-index.w
Request Method:POST
Status Code:302 Found
Request Headersview source
Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
Accept-Encoding:gzip, deflate
Accept-Language:en-GB,en;q=0.8,en-US;q=0.6,tr;q=0.4,pt;q=0.2,fr;q=0.2,it;q=0.2
Cache-Control:max-age=0
Connection:keep-alive
Content-Length:138
Content-Type:application/x-www-form-urlencoded
Cookie:cmTPSet=Y; CoreID6=76955050680714285460643&ci=50530000|ALPHABRODER; __utmt=1; __utma=60293117.2021488460.1428546065.1428546065.1428546065.1; __utmb=60293117.2.10.1428546065; __utmc=60293117; __utmz=60293117.1428546065.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); wp9385=CWBYDDDDDDKZTLJVCI-YILM-XYZM-BBVU-JZHHUCVLKTYKDIIUAUUYC-JMAK-XCCL-HYAI-KZBLZBUMKJCKDHsioHIkhKLk_Jht; 50530000|ALPHABRODER_clogin=v=1&l=1428546064&e=1428547898190
Host:www.alphabroder.com
Origin:https://www.alphabroder.com
Referer:https://www.alphabroder.com/
User-Agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/40.0.2214.111 Chrome/40.0.2214.111 Safari/537.36

Form Dataview sourceview URL encoded
btnLogin:GO
passLoc:webshr/shr-index.w
failLoc:webshr/shr-index.w
pasino:
pguid:
pcustpo:
userName:data_user
password:9SqHB$3ZKAC$
Response Headersview source

Connection:Keep-Alive
Content-Length:0
Content-Type:text/html
Date:Thu, 09 Apr 2015 02:21:30 GMT
Keep-Alive:timeout=10, max=99
Location:http://www.alphabroder.com/cgi-bin/online/webshr/embed-page.w?p=actoab_splash_animation.htm
Server:IBM_HTTP_Server
Set-Cookie:fdmweb=jcbqjcQpBimdHcai; path=/

3)
Remote Address:66.101.206.23:80
Request URL:http://www.alphabroder.com/cgi-bin/online/webshr/embed-page.w?p=actoab_splash_animation.htm
Request Method:GET
Status Code:302 Found
Request Headersview source
Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
Accept-Encoding:gzip, deflate, sdch
Accept-Language:en-GB,en;q=0.8,en-US;q=0.6,tr;q=0.4,pt;q=0.2,fr;q=0.2,it;q=0.2
Cache-Control:max-age=0
Connection:keep-alive
Cookie:cmTPSet=Y; CoreID6=76955050680714285460643&ci=50530000|ALPHABRODER; __utmt=1; __utma=60293117.2021488460.1428546065.1428546065.1428546065.1; __utmb=60293117.2.10.1428546065; __utmc=60293117; __utmz=60293117.1428546065.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); wp9385=CWBYDDDDDDKZTLJVCI-YILM-XYZM-BBVU-JZHHUCVLKTYKDIIUAUUYC-JMAK-XCCL-HYAI-KZBLZBUMKJCKDHsioHIkhKLk_Jht; 50530000|ALPHABRODER_clogin=v=1&l=1428546064&e=1428547898429; fdmweb=jcbqjcQpBimdHcai
Host:www.alphabroder.com
User-Agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/40.0.2214.111 Chrome/40.0.2214.111 Safari/537.36
Query String Parametersview sourceview URL encoded
p:actoab_splash_animation.htm
Response Headersview source
Connection:Keep-Alive
Content-Length:0
Location:https://www.alphabroder.com/cgi-bin/online/webshr/embed-page.w?p=actoab_splash_animation.htm
Server:BigIP
END;


# TODO:
#
# Add headers like accept-content, referer
# set up authentication


# func ($prefix, $func_args, $cmd, $debug)

function  http_request ($opts, $pipe, $cmd = __FUNCTION__) {


  # set up functions for curl to parse

  $req = array (
  
    'init_functions' => array (
      
      'http_init_parse_headers',
      'http_init_parse_forms',
      'http_init_set_url',
      'http_init_set_authorization',
      'http_init_set_headers',
      'http_init_set_post_args',
      'http_init_set_other_options',
    ),
    
    'finalize_functions' => array (
    
      'http_finalize_set_response_body_json_decoded',
      'http_finalize_parse_forms',
    ),
    
    'allowed_protocols' => array (
    
      'http',
      'https',
    ),
    
    
  );

  
  # merge the opts and pass to curl for processing
  
  $opts = merge_opts_for_output ($opts, $req);
  
  
  return  curl ($opts, $pipe, $cmd, 'http');
}


function  http_init_parse_headers ($prefix, $func_args, $cmd, $debug) {
        

  # get opts and curlopts
  
  $opts = &$func_args['opts'];
  $curlopts = &$func_args['curlopts'];
  $res = &$func_args['res'];
    
    
  # get headers opt
    
  $headers = get_opt ($prefix, $opts, 'headers');

  if (!check_opt_if_set_type ($cmd, $headers, 'headers', 'array_of_strings'))
    return  false;
    
    
  # set an empty headers array for the other headers modules
  
  if (!is_array ($headers)) {
  
    $opts['headers'] = array ();
  }
    
    
  # get response body opt
    
  $response_head = get_opt ($prefix, $opts, 'response_head');
  
  if (!check_opt_if_set_type ($cmd, $response_head, 'response_head', 'string'))
    return  false;


  if ($response_head) {
  
    debug_echo ($cmd, "parsing previous response head for cookies and other headers");

    
    # set up header parsing
    
    $response_headers = explode ("\n", $response_head);
    $cookies_to_set = array ();
    $cookies = array ();
    
    
    # parse the response headers

    foreach ($response_headers as $header) {
    
    
      # clean and add response header
    
      $header = trim ($header);
    
    
      # parse for cookies
    
      if (substr ($header, 0, 11) == 'Set-Cookie:') {
      
        # parse the cookie
      
        $full_cookie = trim (substr ($header, 11));
        $cookie = strstr ($full_cookie, ';', true);
        
        $name = urldecode (strstr ($cookie, '=', true));
        $val = urldecode (substr (strstr ($cookie, '='), 1));
        

        # save the cookies for passing
        
        $cookies_to_set[] = urldecode ($cookie);
        $cookies[$name] = $val;
      }
    }
    
    
    # add the cookies to the headers
    
    if ($cookies) {
    
    
      # set the headers
      
      $opts['headers'][] = 'Cookie: ' . implode ('; ', $cookies_to_set);
    
    
      # record the cookies
    
      if ($debug) {
      
        debug_echo ($cmd, "the following cookies were found from the previous response head :");
        debug_dump_yaml ($cookies, true);
      }

      $res['request_cookies'] = $cookies;
    }
  }

  return  true;
}


function  http_init_parse_forms ($prefix, $func_args, $cmd, $debug) {


  # get opts and curlopts
  
  $opts = &$func_args['opts'];
  $curlopts = &$func_args['curlopts'];
  $res = &$func_args['res'];
  

  # get request method opt
  
  $request_method = get_opt_config_value ($prefix, $opts, 'request_method', 'get');

  if (!check_opt_set_type ($cmd, $request_method, 'request_method', 'http_request_method'))
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
    
    $r = http_parse_form ($prefix, $opts, $res, $form_name, $cmd, $debug);

    if ($r === false)
      return  false;
  }
  
  $opts['request_method'] = $request_method;
  
  return  true;
}


function  http_init_set_url ($prefix, $func_args, $cmd, $debug) {


  # get opts and curlopts
  
  $opts = &$func_args['opts'];
  $curlopts = &$func_args['curlopts'];
  

  # get base url from url|response_location|request opt
  
  $base_url_opts = array ('url', 'response_location', 'form_action', 'response_form_action', 'request_url');

  foreach ($base_url_opts as $opt) {
  
    $base_url = get_opt ($prefix, $opts, $opt);
    
    if (!check_opt_if_set_type ($cmd, $base_url, $opt, 'string'))
      return  false;
    
    if ($base_url) {
    
      if ($debug) {
    
        debug_echo ($cmd, "using the value from option '$opt' for the base url : $base_url");
      }
      
      break;
    }
  }
  
  if (!$base_url)
    return  opt_not_set_msg ($cmd, implode (',', $base_url_opts));
  
  
  # get get args opt
    
  $get_args = get_opt ($prefix, $opts, 'get_args');
  
  if (!check_opt_if_set_type ($cmd, $get_args, 'get_args', 'array,string'))
    return  false;

    
  # build url

  if (is_array ($get_args))
    $get_args = http_build_query ($get_args);
    
  if (is_string ($get_args)) {
  
    $url = "$base_url?$get_args";
  
  } else {
    $url = $base_url;
  }
  

  # set url
  
  $opts['curlopts']['url'] = $url;
  
  return  true;
}


function  http_init_set_authorization ($prefix, $func_args, $cmd, $debug) {


  # get the opts from the func args

  $opts = &$func_args['opts'];
  

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
  
    $opts['headers'][] = "Authorization: $type " . base64_encode ("$usr:$pwd");
  }


  return  true;
}
    

function  http_init_set_headers ($prefix, $func_args, $cmd, $debug) {
  

  # get opts and curlopts
  
  $opts = &$func_args['opts'];
  $curlopts = &$func_args['curlopts'];
  $res = &$func_args['res'];
    
    
  # get headers opt

  $headers = get_opt ($prefix, $opts, 'headers');

  if (!check_opt_set_type ($cmd, $headers, 'headers', 'array_of_strings'))
    return  false;

    
  # get request url opt
  
  $request_url = get_opt_config_value ($prefix, $opts, 'request_url');

  if (!check_opt_if_set_type ($cmd, $request_url, 'request_url', 'string'))
    return  false;
    
    
  # set referer header
    
  if ($request_url)
    $headers[] = "Referer: $request_url";
    
    
  # set the curlopt
  
  if ($headers)
    $curlopts['headers'] = $headers;
    
    
  return  true;
}


function  http_init_set_post_args ($prefix, $func_args, $cmd, $debug) {

  
  # get opts and curlopts
  
  $opts = &$func_args['opts'];
  $curlopts = &$func_args['curlopts'];
  $res = &$func_args['res'];
  

  # get post args opt
    
  $post_args = get_opt ($prefix, $opts, 'post_args');
  
  if (!check_opt_if_set_type ($cmd, $post_args, 'post_args', 'string,array'))
    return  false;
    
  
  if ($post_args) {
        
    if (is_array ($post_args)) {
    
      # build post args (note: we don't use http_build_query() because we don't want to url-encode them yet)
    
      $request_body = http_build_query ($post_args);
    
    } else {
    
      $request_body = $post_args;
      $post_args = parse_str ($post_args);
    }
    
    if ($debug) {
    
      debug_echo ($cmd, "HTTP post args :");
      debug_dump_yaml ($post_args, true);
    }
    
    $res['request_body'] = $request_body;
    $res['request_method'] = 'post';
    $res['request_post_args'] = $post_args;
    $curlopts['data'] = $request_body;  
}
  


  return  true;
}


function  http_init_set_other_options ($prefix, $func_args, $cmd, $debug) {

  
  # get opts and curlopts
  
  $opts = &$func_args['opts'];
  $curlopts = &$func_args['curlopts'];
  $res = &$func_args['res'];

  
  # get auto redirect opt
  
  $auto_redirect = get_opt ($prefix, $opts, 'auto_redirect', true);

  if (!check_opt_set_type ($cmd, $auto_redirect, 'auto_redirect', 'boolean'))
    return  false;
    
  $curlopts['location'] = $auto_redirect;
  
  
  return  true;
}


function  http_finalize_set_response_body_json_decoded ($prefix, $func_args, $cmd, $debug) {


  # set prefix
  
  $prefix = 'http_finalize_set_response_body_json_decoded';

  
  # get opts and curlopts
  
  $opts = &$func_args['opts'];
  $res = &$func_args['res'];

  
  # get response body
  
  $response_body = get_opt ($prefix, $res, 'response_body');

  if (!check_opt_if_set_type ($cmd, $response_body, 'response_body', 'string'))
    return  false;
  

  # build the json-decoded response bdoy
  
  if ($response_body) {
  
    $response_body_json_decoded = @json_decode ($response_body, true);
    
    if ($response_body_json_decoded)
      $res['response_body_json_decoded'] = $response_body_json_decoded;
  }
  
  
  return  true;
}
  
  

function  http_finalize_parse_forms ($prefix, $func_args, $cmd, $debug) {

  
  # get opts and curlopts
  
  $opts = &$func_args['opts'];
  $res = &$func_args['res'];


  # get parse response hidden inputs opt
  
  $parse_response_form = get_opt_config_value ($prefix, $opts, 'parse_response_form', false);
  
  if (!check_opt_set_type ($cmd, $parse_response_form, 'parse_response_form', 'boolean'))
    return  false;
    
  
  # get response form_name opt
  
  $response_form_name = get_opt_config_value ($prefix, $opts, 'response_form_name');
  
  if (!check_opt_if_set_type ($cmd, $response_form_name, 'response_form_name', 'string'))
    return  false;
    
    
  if ($parse_response_form) {
  
    debug_echo ($cmd, "parsing the response body for form data");
    
    return  http_parse_form ($prefix, $opts, $res, $response_form_name, $cmd, $debug);    
  }
  
  return  true;
}
  

function  http_parse_form ($prefix, &$opts, &$res, $form_name, $cmd, $debug, $response_form = false) {
  
  # TODO: put debug info around the debug_echo's
  

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
  
  if ($response_form) {
  
    $res['response_form_method'] = $form_method;
    
  } else {
  
    $opts['form_method'] = $form_method;
    $res['form_method'] = $form_method;
  }
  
    
  # build the full form action
  
  if ($form_action) {
    
    
    # check for a full URL
    
    if (substr ($form_action, 0, 7) != 'http://' && substr ($form_action, 0, 8) != 'https://') {
    
    
      # split the url into parts
    
      $r = preg_match ('/^(https?:\/\/[^\/\?]+)((\/[^\/\?]*)*)?(\?(.*))?$/', $request_url, $matches);

      $host = $matches[1];
      $path = $matches[2];
      $last_dir = $matches[3];
      $get_args = @$matches[4];
      
      
      # build the form action based on the found parts and how it's defined
      
      if ($form_action[0] == '/') {

        $form_action = "${host}${form_action}";
      
      } else {

        $dir_path = substr ($path, 0, -(strlen ($last_dir)) );
      
        $form_action = "${host}${dir_path}/$form_action";
      }
    }
      
      
    # save the form action
    
    debug_echo ($cmd, "form action url found : $form_action");
      
    if ($response_form) {
    
      $res['response_form_action'] = $form_action;
    
    } else {
      
      $opts['form_action'] = $form_action;
      $res['form_action'] = $form_action;
    }
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
  
    return  true;
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
  
  if ($response_form) {
  
    $res['response_form_inputs'] = $form_inputs;
  
  } else {
  
    $opts['form_inputs'] = $form_inputs;
    $res['form_inputs'] = $form_inputs;
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
  
  if ($response_form) {
  
    $opts["response_$args_key"] = $args;
    $res[$args_key] = $args;
    
  } else {
  
    $opts[$args_key] = $args;
  }
  

  # sort response and return
    
  ksort ($opts);
  ksort ($res);
  
  return  true;
}


function  http_check_saved_file ($opts, $pipe, $cmd = __FUNCTION__) {

  return  curl_check_saved_file ($opts, $pipe, $cmd, 'http');
}


function  http_use_checked_saved_file ($opts, $pipe, $cmd = __FUNCTION__) {

  return  curl_use_checked_saved_file ($opts, $pipe, $cmd, 'http');
}


?>