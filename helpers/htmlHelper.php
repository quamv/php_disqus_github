<?php namespace helpers;

class htmlHelper {
    // parse the desired JSON data into HTML for use on your site
    public static function interestingForums($results){
        $html = '';

        foreach($results["response"]["items"] as $summaryObj){
            $html = $html . "<p>" . $summaryObj["id"] . " - " . $summaryObj["reason"] . "</p>\n";
        }
        
        return $html;
    }
}

?>