<?php

class Api
{
    private $cmd;
    private $id;

    public function setCmd($cmd, $id)
    {
        $this -> cmd = $cmd;
        $this -> id = $id;
    }

    public function get()
    {
        switch ($this -> cmd){
            case "list" : return $this -> itemsList();
            case "item" : return $this -> item($this -> id);
        }
        return [];
    }

    private function itemsList(){
        $data = [];
        $dir = "..".DIRECTORY_SEPARATOR.Scrapper::OFFER_DIR;
        $d = dir($dir);
        while (false !== ($entry = $d->read())) {
            if ($entry == "." || $entry == "..") continue;
            $item = file_get_contents("..".DIRECTORY_SEPARATOR.Scrapper::OFFER_DIR.$entry);
            $json = json_decode($item,true);
            $item = [];
            if ($json["intro"] == ""){
                continue;
            }
            $item["title"] = $json["title"];
            $item["intro"] = $json["intro"];
            $item["id"] = $json["id"];
            $item["url"] = $json["url"];
            $data[] = $item;
        }
        $d->close();

        return $this -> json(["data" => $data]);
    }

    private function item($id){
        $item = file_get_contents("..".DIRECTORY_SEPARATOR.Scrapper::OFFER_DIR."ad".$id.".json");
        $data = json_decode($item,true);
        return $this -> json($data);
    }

    private function json(array $data)
    {
        return json_encode($data);
    }

}