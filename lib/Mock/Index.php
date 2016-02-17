<?php

namespace TimTegeler\Routerunner\Mock;

class Index
{

    public function __construct(){

    }

    public static function get()
    {
        return "index->get";
    }

    public static function post()
    {
        return "index->post";
    }

    public static function login(){
        return "index->login";
    }

}