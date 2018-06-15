<?php

require_once __DIR__ . '/disqus_helper.php';
require_once __DIR__ . '/helpers/curl_helper.php';
require_once __DIR__ . '/helpers/htmlHelper.php';
require_once __DIR__ . '/shared.php';

class assert {

    public static $failures = 0;

    // public assertions
    public static function fail ($msg){ 
        assert::$failures = assert::$failures + 1;
        echo ("assertion failure: $msg\n");}
    public static function is_null($val){ assert::are_equal(NULL, $val); }
    public static function not_null($val){ assert::not_equal(NULL, $val); }
    public static function is_true($val){ assert::are_equal(TRUE, $val); }
    public static function is_false($val){ assert::are_equal(FALSE, $val); }
    public static function are_equal($expected, $actual){
        if ($expected !== $actual)
            assert::fail("\nexpected: $expected\nactual: $actual");
    }
    public static function not_equal($expected, $actual){
        if ($expected === $actual)
            assert::fail("\nexpected: $expected\nactual: $actual");
    }

    public static function is_expected_exception($ex, $expected_str){
        $msg = $ex->getMessage();

        if (strpos($msg, $expected_str) === FALSE)
            assert::fail ("unexpected exception received\nexpected: $expected_str\nreceived: $msg");
    }
}

?>