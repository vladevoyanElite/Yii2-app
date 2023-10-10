<?php

namespace app\components;
if(!function_exists("dumpe")){
    function dumpe($data = [])
    {
        $bt = debug_backtrace();
        $callLog = [];

        array_walk($bt, function ($item) use (&$callLog) {


            if (!isset($item['file'])) {
                if (isset($item['class'])) {
                    $callLog[count($callLog) - 1] .= " :: " . @$item['class'] . "::" . @$item['function'];
                }
            } else {
                $callLog[] = @$item['file'] . ":" . @$item['line'];
            }
        });


        $out = "<pre>";


        $out .= print_r($data, true) . "\n";

        $out .=
            "\nCallers\n" .
            implode("\n", $callLog) . "</pre>";
        exit ($out);
    }
}

