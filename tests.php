 <?php

require_once __DIR__ . '/disqus_helper.php';
require_once __DIR__ . '/helpers/curl_helper.php';
require_once __DIR__ . '/helpers/htmlHelper.php';
require_once __DIR__ . '/shared.php';
require_once __DIR__ . '/assert.php';

class tests {
    // i think it's easier to understand if one is spelled out
    function should_get_interestingForums_url_with_custom_limit(){
        $limit = 30;
        $helper = new disqus_helper();
        $params = array(get_name_value_pair('limit', $limit));
        $forum_name = ID_FORUMS_INTERESTINGFORUMS; // 'forums/interestingForums';
        $url = $helper->getUrl($forum_name, $params);
        $expected = EP_FORUMS_INTERESTINGFORUMS . "?limit=$limit&api_secret=" . API_SECRET;

        assert::are_equal($expected, $url);
    }
    // and then the rest can be terse as a mafk
    function should_get_interestingForums_url_with_default_limit_param(){ 
        $url = (new disqus_helper())->getUrl(ID_FORUMS_INTERESTINGFORUMS, array());
        // we did not provide a value for the limit parameter, but the endpoint uses a default
        // so the resulting url should include the parameter with the default value
        $expected = EP_FORUMS_INTERESTINGFORUMS . "?limit=" . DEFAULT_LIMIT . "&api_secret=" . API_SECRET;

        assert::are_equal($expected, $url);
    }
    function should_throw_invalid_param(){
        $ex_received = NULL;
        try{
            $url = (new disqus_helper())->getUrl(ID_FORUMS_INTERESTINGFORUMS, array(get_name_value_pair('XXXlimitXXX', 30)));
        }catch (Exception $ex){
            $ex_received = $ex;
        }

        if ($ex_received === NULL)
            assert::fail ("failed to throw expected exception: '" . EX_INVALID_PARAMETER_SUPPLIED . "'");
        else
            assert::is_expected_exception($ex, EX_INVALID_PARAMETER_SUPPLIED);
    }
    function should_get_default_number_of_listposts(){ 
        $url = (new disqus_helper())->getUrl(ID_FORUMS_LISTPOSTS, array(get_name_value_pair('forum', 'disqus')));
        $val = strpos($url, 'limit=' . DEFAULT_LIMIT) != NULL;
        assert::is_true(strpos($url, 'limit=' . DEFAULT_LIMIT) !== false);
        
        $ch = new \helpers\curl_helper();
        $results = $ch->fetch2($url, true);
        $summaryObjects = $results["response"];

        assert::are_equal(DEFAULT_LIMIT, count($summaryObjects));
    }
    function should_add_default_parameter_value() {
        $url = (new disqus_helper())->getUrl(ID_FORUMS_LISTPOSTS, array(get_name_value_pair('forum', 'disqus'))); 

        $val = strpos($url, 'limit=' . DEFAULT_LIMIT) != NULL;
        assert::is_true(strpos($url, 'limit=' . DEFAULT_LIMIT) !== false);
    }
    function should_throw_missing_required_parameter(){ 
        try{
            $url = (new disqus_helper())->getUrl(ID_FORUMS_LISTPOSTS, array());
        }catch(Exception $ex){
            assert::is_expected_exception($ex, EX_REQUIRED_PARAM_NOT_FOUND);
            return;
        }
        assert::fail ("failed to throw expected exception: '" . EX_REQUIRED_PARAM_NOT_FOUND . "'");
    }
    function should_successfully_get_interestingForums (){
        $url = (new disqus_helper())->getUrl(ID_FORUMS_INTERESTINGFORUMS, array());
        $ch = new \helpers\curl_helper();
        $results = $ch->fetch2($url, true);

        if ($results['code'] !== 0){
            assert::fail("error on download of $url\n");
            return;
        }

        $summaryObjects = $results["response"]["items"];

        assert::are_equal(DEFAULT_LIMIT, count($summaryObjects));
    }
    function should_successfully_get_user_details($params)
    {
        $url = (new disqus_helper())->getUrl(ID_USERS_DETAILS, $params);
        $ch = new \helpers\curl_helper();
        $results = $ch->fetch2($url, true);

        if ($results['code'] !== 0){
            assert::fail("error on download of $url\n");
            return;
        }

        $response = $results["response"];
        assert::is_true(array_key_exists('profileUrl', $response));
        assert::not_null($response['profileUrl']);
    }
    function core1($endpoint_id, $params){
        $url = (new disqus_helper())->getUrl($endpoint_id, $params);
        return (new \helpers\curl_helper())->fetch2($url, true);
    }
    function getResponse($endpoint_id, $params){
         $results = $this->core1($endpoint_id, $params);
         assert::is_true($results['code'] == 0);
         assert::not_null($results["response"]);
         return $results["response"];
    }
    function should_get_categories_list_default_limit(){
        assert::not_null($this->getResponse(ID_CATEGORIES_LIST, array(get_name_value_pair('forum', 'movies'))));
    }



    public static function run() {
        echo "Beginning test run..\n";
        $obj = new tests();
        $obj->should_throw_invalid_param();
        $obj->should_get_interestingForums_url_with_custom_limit();
        $obj->should_get_interestingForums_url_with_default_limit_param();
        $obj->should_successfully_get_interestingForums ();
        $obj->should_get_default_number_of_listposts ();
        $obj->should_throw_missing_required_parameter();
        $obj->should_add_default_parameter_value();
        $obj->should_successfully_get_user_details(array());
        $obj->should_successfully_get_user_details(array(get_name_value_pair(USER_PARAMKEY, 2)));
        $obj->should_get_categories_list_default_limit();
        echo assert::$failures . " tests failed\n" . ((assert::$failures == 0) ? "Nice.\n" : "") ;
    }
}

echo "\n";
tests::run();

?>