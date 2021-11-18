<?php

class Scrapper
{
    const DATA_DIR = "data".DIRECTORY_SEPARATOR;
    const OFFER_DIR = "offers".DIRECTORY_SEPARATOR;

    private $url;

    function get_web_page($url )
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_USERAGENT      => "spider", // who am i
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }

    public function setUrl($string)
    {
        $this -> url = $string;
    }

    public function parse()
    {
        if ($this -> isTodayReaded()){
            Logger::log("Today data readed");
            return false;
        }
        try {
            $page = $this->get_web_page(str_replace("{page}", "1", $this->url));
            $json = json_decode($page["content"], true);
            $total = $json["total"];
            $perpage = sizeof($json["ads"]);
            $pages = ceil($total / $perpage);
            Logger::log("save page: 1");
            $this->savePage($json,1);
            for ($i = 2; $i <= $pages; $i++) {
                Logger::log("Read page: ".$i);
                $page = $this->get_web_page(str_replace("{page}", $i, $this->url));
                $json = json_decode($page["content"], true);
                Logger::log("save page: ".$i);
                $this->savePage($json,$i);
            }
        }catch(\Exception $e){
            Logger::log("Exception: ".$e->getMessage()." on line: ".$e->getLine());
            return false;
        }
        return true;
    }

    private function isTodayReaded(){
        $dir = self::DATA_DIR.date("Y-m-d");
        return is_dir($dir || count(scandir($dir)) !== 2);
    }

    private function savePage($json,$i)
    {
        if (!is_dir(self::DATA_DIR)){
            mkdir(self::DATA_DIR);
        }

        $dir = self::DATA_DIR.date("Y-m-d");
        if (!is_dir($dir)){
            mkdir($dir);
        }
        file_put_contents($dir.DIRECTORY_SEPARATOR."page".$i.".json",json_encode($json));
        $this -> parseOffers($json);
    }

    private function parseOffers($json)
    {
        if (!is_dir(self::OFFER_DIR)){
            mkdir(self::OFFER_DIR);
        }
        foreach($json["ads"] as $ad){
            file_put_contents(self::OFFER_DIR."ad".$ad["id"].".json",json_encode($ad));
        }
    }
}