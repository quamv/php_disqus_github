<?php

require_once __DIR__ . '/shared.php';


/*
create an endpoint definition record.
endpoint definition records include a set of properties and an array of parameter definitions. 
*/
function get_endpoint_def($id, $url, $requires_api_secret, $requires_api_key, $requires_access_token, $params){
    return array(
        'id' => $id,
        'url' => $url,
        'requires_api_secret'=>$requires_api_secret, 
        'requires_api_key'=>$requires_api_key,
        'requires_access_token'=>$requires_access_token,
        'params' => $params
    );
}

/*
constructor for endpoint parameter DEFINITIONS.
endpoint parameter definitions define properites for a valid parameter for that endpoint
and each endpoint record will have an array of endpoint parameter definitions
*/
function get_endpoint_param_def($name, $required = false, $defaultval = NULL){
        return array(
        'name' => $name, // e.g. 'limit'
        'required'=>$required, // e.g. false
        'default_val'=>$defaultval // e.g. 20
    );
}

/*
get a record holding a name and a value (for use as a http query string parameter)
e.g. ( 'name' => 'limit', 'val' => 40 )
*/
function get_name_value_pair($name, $val){
    return array(
        'name' => $name,
        'val' => $val
    );
}


// if needed, add an auto-parameter
function auto_param($endpoint, $name, $key, $val) {
    return $endpoint[$name] ? array(get_name_value_pair($key, $val)) : array();
}



/*
the master list of supported endpoint definitions
see 'get_endpoint' above for definition of endpoint structure
*/
$endpoints_master = array(
    ID_FORUMS_LISTPOSTS => get_endpoint_def(ID_FORUMS_LISTPOSTS, EP_FORUMS_LISTPOSTS, true, false, false, array(
        get_endpoint_param_def('forum', true, NULL),
        get_endpoint_param_def('since'),
        get_endpoint_param_def('related'),
        get_endpoint_param_def('cursor'),
        get_endpoint_param_def('limit', false, 10),
        get_endpoint_param_def('filters'),
        get_endpoint_param_def('query'),
        get_endpoint_param_def('include'),
        get_endpoint_param_def('order')
    )),
    ID_FORUMS_INTERESTINGFORUMS => get_endpoint_def(ID_FORUMS_INTERESTINGFORUMS, EP_FORUMS_INTERESTINGFORUMS, true, false, false, array(
        get_endpoint_param_def('limit', false, 10)
    )),
    ID_CATEGORIES_LIST => get_endpoint_def(ID_CATEGORIES_LIST, EP_CATEGORIES_LIST, true, false, false, array(
        get_endpoint_param_def('forum', true, NULL),
        get_endpoint_param_def('since_id'),
        get_endpoint_param_def('cursor'),
        get_endpoint_param_def('limit'),
        get_endpoint_param_def('order'),
    )),
    ID_FORUMS_LISTTHREADS => get_endpoint_def(ID_FORUMS_LISTTHREADS, EP_FORUMS_LISTTHREADS, true, false, false, array(
        get_endpoint_param_def('forum', true, NULL),
        get_endpoint_param_def('thread'),
        get_endpoint_param_def('since'),
        get_endpoint_param_def('related'),
        get_endpoint_param_def('cursor'),
        get_endpoint_param_def('limit', false, 10),
        get_endpoint_param_def('include'),
        get_endpoint_param_def('order'),
    )),
    ID_USERS_DETAILS => get_endpoint_def(ID_USERS_DETAILS, EP_USERS_DETAILS, false, true, true, array(
        get_endpoint_param_def('user')
    ))
);


?>