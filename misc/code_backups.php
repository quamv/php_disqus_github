<?php        

        // alternate, functional approach that is kludgy because of the need to get names and repackage, etc..
        // // check for missing required parameters
        // // get just the names of the required parameters
        // $required_params = array_filter($endpoint['params'], function($val){ return $val['required'];});
        // $required_param_names = shared::get_names($required_params);
        // // diff the list of user-provided parameter names with the required parameter names to find missing
        // $missing_requireds = array_diff($required_param_names, $user_param_names);
        // if (count($missing_requireds) > 0)
        //     throw new Exception(EX_REQUIRED_PARAM_NOT_FOUND . ":" . var_export($missing_requireds));

        // // check for parameters with default values that have not been set by user
        // $params_with_defaults = array_filter($endpoint['params'], function($val){ return $val['default_val'] != NULL;});
        // $param_names_with_defaults = shared::get_names($params_with_defaults);
        // $param_names_to_auto_assign = array_diff($param_names_with_defaults, $user_param_names);
        // foreach($param_names_to_auto_assign as $param_name_to_assign){
        //     $param_objs = array_filter($params_with_defaults, 
        //         function($v) use($param_name_to_assign) 
        //         {
        //             return $v['name']==$param_name_to_assign;
        //         }
        //     );

        //     $param_obj = $param_objs[0];
        //     $user_params[$param_obj['name']] = $param_obj['default_val'];
        // }



        // /*
// the master associative array of available endpoints
// key is a const string (the endpoint id)
// value is an endpoint object containing:
//     the same id used for the key (maybe redundant, maybe not as it's easier to pass the object around if it has the id embedded)
//     an associative array of properties for the endpoint including:
//         requires_api_secret: boolean optional (adds the api_secret parameter to query string)
//         requires_api_key: boolean optional (adds the api_key parameter to query string)
//         params: an associative array of parameters associated with that endpoint. each parameter has the following properties:
//             id: string
//             required: bool
//             default: object

//             if the parameter is required and not provided, an exception is thrown
//             if the parameter has a default and no specific value provided, the default will be used
//             if the parameter has a default and a specific value is provided, the specific value provided will be used

// so you end up with
// [
//     'listposts' => endpoint { requires_api_secret=>true, params=>[endpoint_param { name=>'forum',  required=>true, default_val=NULL }, endpoint_param{'limit',false,10} ]},
//     ...,
//     'details' => endpoint { requires_api_key=>true, params=>[endpoint_param { name=>'user',  required=>false, default_val=NULL } ]}
// ]

// perhaps the custom classes (endpoint and endpoint_param) should be scrapped in favor of associative arrays but i'm not quite sure how to enforce
// rules in array contents. you could also argue such rules are unnecessary. just make sure it's correct.
// anyways, then you would be able to use strictly array functions and lookup based on keys instead of custom search functions using the 'name' property
// of the custom classes.
// */
// $endpoints_master = array(
//     LISTPOSTS_ID => new endpoint(LISTPOSTS_ID, (object)[
//         'requires_api_secret' => true, 
//         'params' => array(
//             new endpoint_param(KEY_FORUM, true, NULL),
//             new endpoint_param(KEY_LIMIT, false, 10))]),
//     INTERESTINGFORUMS_ID => new endpoint(INTERESTINGFORUMS_ID, (object)[
//         'requires_api_secret'=> true, 
//         'params' => array(
//             new endpoint_param('limit', false, 10))]),
//     DETAILS_ID => new endpoint(DETAILS_ID, (object)[
//         'requires_api_key' => true, 
//         'params' => array(
//             new endpoint_param('user', false, NULL))])
// );

class endpoint_param {
        public $name = "";
        public $required = false;
        public $default_val = NULL;
        public function __construct($name, $required, $default_val) { 
            $this->name = $name; 
            $this->required = $required;
            $this->default_val = $default_val;
        }
    }
    
    class endpoint {
        public $url = "";
        public $requires_api_key = false;
        public $requires_api_secret = false;
        public $params = array ();
    
        public function __construct($url, $initializer){
            $this->url = $url;
            foreach ($initializer as $key => $value){
                switch($key){
                    case 'requires_api_key': $this->requires_api_key = $value; break;
                    case 'requires_api_secret': $this->requires_api_secret = $value; break;
                    case 'params': $this->params = $value; break;
                    default: throw new Exception("unknown argument: $key");
                }
            }
    
        }
    
    }
    


        
?>