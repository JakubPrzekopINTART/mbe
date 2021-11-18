<?php

class Page
{
    public function index($data){
        $html = file_get_contents("template/index.html");;
        foreach ($data as $k=>$v){
            $html = str_replace("{".$k."}",$v,$html);
        }
        echo $html;
    }
}