<?php

namespace TimTegeler\Routerunner\Mock;

class Index
{

    public static function __construct(){

    }

    public static function get()
    {
        return "index->get";
    }

    public static function post()
    {
        return "index->post";
    }

}