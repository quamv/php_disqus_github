<?php namespace helpers;

require_once __DIR__ . '/../shared.php';

class curl_helper {
    private const CH_DEFAULT = NULL;
    public $ch = curl_helper::CH_DEFAULT;

    public function common_curl_init() 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $this->ch = $ch;
    }

    public function fetch2($endpoint, $decode_bool) 
    {
        if ($this->ch == curl_helper::CH_DEFAULT)
            $this->common_curl_init();

        curl_setopt($this->ch, CURLOPT_URL, $endpoint);
        
        $data = curl_exec($this->ch);
        $results = json_decode($data, $decode_bool);

        if ($results === NULL)
            \shared::fatal('Error getting API results');

        return $results;
    }

    public function fetch($endpoint) 
    {
        return fetch2($endpoint, false); 
    }
}

?>
