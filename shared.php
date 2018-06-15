<?php
require __DIR__ . '/private.php';

// property dictionary keys
const KEY_API_SECRET = 'api_secret';
const KEY_API_KEY = 'api_key';
const KEY_ACCESS_TOKEN = 'access_token';
const LIMIT_PARAMKEY = 'limit';
const FORUM_PARAMKEY = 'forum';
const USER_PARAMKEY = 'user';
const CACHE_FILENAME = 'dsq-listpopular-cache.txt';
const URI_BASE = 'https://disqus.com/api/3.0/';
const DEFAULT_LIMIT = 10;

// endpoints
const FORUMS = 'forums/';
const ID_FORUMS_INTERESTINGFORUMS = FORUMS . 'interestingForums';
const EP_FORUMS_INTERESTINGFORUMS = URI_BASE . ID_FORUMS_INTERESTINGFORUMS . '.json';
const ID_FORUMS_LISTPOSTS = FORUMS . 'listPosts';
const EP_FORUMS_LISTPOSTS = URI_BASE . ID_FORUMS_LISTPOSTS . '.json';
const ID_FORUMS_LISTTHREADS = FORUMS . 'listThreads';
const EP_FORUMS_LISTTHREADS = URI_BASE . ID_FORUMS_LISTTHREADS . '.json';

const USERS = 'users/';
const ID_USERS_DETAILS = USERS . 'details';
const EP_USERS_DETAILS = URI_BASE . ID_USERS_DETAILS . '.json';

const CATEGORIES = "categories/";
const ID_CATEGORIES_LIST = CATEGORIES . "list";
const EP_CATEGORIES_LIST = URI_BASE . ID_CATEGORIES_LIST . '.json';

// exceptions
const EX_INVALID_PARAMETER_SUPPLIED = "invalid parameter supplied";
const EX_REQUIRED_PARAM_NOT_FOUND = 'required parameter not found';
const EX_INVALID_ENDPOINT_ID = 'invalid endpoint id';

class shared {
    public static function ex ($msg){ throw new Exception($msg); }
    public static function fatal ($msg){ die($msg); }
    public static function secret() { return urlencode(\shared::API_SECRET); }

    /*
    map_to_member
    takes:
        $records: a set of records
        $propname: the name of a property on those records
    returns:
        a simple array of values (the specified property value from each record)
        e.g. 
            map_to_member(('name' => 'limit', 'name' => 'forum'), 'name')
        returns
            ('limit', 'forum')
    */
    public function map_to_member($records, $propname)
    {
        return array_map(
            function($val) use($propname)  { return $val[$propname]; }
            , $records
        );
    }

}

?>