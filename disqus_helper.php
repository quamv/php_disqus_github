<?php

require __DIR__ . '/shared.php';
require __DIR__ . '/endpoints.php';


class disqus_helper {

    /*
    check_requireds_and_add_defaults
    takes:
        $endpoint: the endpoint definition
        $user_params: the key/val set of parameters from the user
    does:
        ensures required parameters have been set
        applies default values to parameters as appropriate
    returns:
        the updated set of user parameters after defaults have been applied (if any)
    */
    function check_requireds_and_add_defaults($endpoint, $user_params)
    {
        // loop through the endpoint's defined parameters and verify that any required
        // parameters have been passed in, and set any default parameters to parameters
        // that require them and have not been set by the user. user settings override if present.

        foreach($endpoint['params'] as $endpoint_param){

            // see if the user sent this parameter in
            $matches = array_filter($user_params, 
                function ($user_param) use($endpoint_param) {
                    return $user_param['name'] == $endpoint_param['name'];
                }
            );

            // if the user did NOT send this parameter in, 
            if (count($matches) === 0)
                // check if it is defined as 'required'.
                // if parameter is required but has not been passed in, that is a violation so throw exception.
                if ($endpoint_param['required'] === true)
                    shared::ex(EX_REQUIRED_PARAM_NOT_FOUND . ": " . $endpoint_param['name']);
                // otherwise (not required), check if this parameter has a default value associated with it.
                // if it does, we already know the user didn't set it, so set it here.
                // almost walked into a self-imposed bug here. chout if ur not using braces to denote blocks
                else if ($endpoint_param['default_val'] != NULL)
                    array_push(
                        $user_params
                        , array ('name' => $endpoint_param['name'], 'val' => $endpoint_param['default_val']));
        }

        return $user_params;
    }


    /*
    getUrl
    takes:
        a string representing an endpoint name (e.g. 'listposts')
    does:
        validates the $user_params set
        applies any required auto-parameters (api key, api secret)
    returns:
        a partial url and query string (forums/listPosts.json?forum=disqus&limit=10)
    */
    function getUrl($endpoint_id, $user_params) 
    {
        global $endpoints_master;

        // validate the endpoint id is valid
        if (!array_key_exists($endpoint_id, $endpoints_master))
            shared::ex(EX_INVALID_ENDPOINT_ID . ': ' . $endpoint_id);

        // get the endpoint definition including url and list of valid parameters
        $endpoint = 
            $endpoints_master[$endpoint_id];

        // are all the parameters passed in legit? bad ones will gunk up the api
        $invalid_params = 
            array_diff(
                shared::map_to_member($user_params, 'name'), // names of user-provided parameters
                shared::map_to_member($endpoint['params'], 'name')); // names of valid parameters for this endpoint
            
        // if we found any, throw exception
        if (count($invalid_params) > 0)
            shared::ex(EX_INVALID_PARAMETER_SUPPLIED);

        // verified params have the necessary defaults added and all required params
        $verified_user_params =
            $this->check_requireds_and_add_defaults($endpoint, $user_params);

        // add any required auto-parameters (api key, api secret)
        $final_params = 
            array_merge(
                $verified_user_params
                , auto_param($endpoint, 'requires_api_secret', KEY_API_SECRET, API_SECRET)
                , auto_param($endpoint, 'requires_api_key', KEY_API_KEY, API_KEY)
                , auto_param($endpoint, 'requires_access_token', KEY_ACCESS_TOKEN, ACCESS_TOKEN));


        // convert our format to the format required for http_build_query 
        // e.g.
        //      [ ('name'=>'limit','val'=>30), ('name' => 'forum', 'val'='disqus') ]
        //      becomes 
        //      [ ('limit' => 30), ('forum'=>'disqus') ]
        $transformed_params = 
            array_map(
                function($val) { return array($val['name'] => $val['val']); }
                , $final_params);


        // map each transformed parameter record to a url-encoded key=value string
        // e.g.
        //      [ ('limit' => 30), ('forum'=>'disqus') ]
        //      becomes
        //      [ "limit=30", "forum=disqus" ]
        $mapped = 
            array_map(
                function($v){ return http_build_query($v); }
                , $transformed_params);


        // implode the various key=value strings into key=value&key2=value2&...
        $qry_str = 
            implode('&', $mapped);


        // combine the endpoint url and the query string into full abc/def.ghi?param1=val1&param2=val2 string
        // the hostname and root of the url will be added elsewhere
        return $endpoint['url'] . '?' . $qry_str;
    }
}

?>


