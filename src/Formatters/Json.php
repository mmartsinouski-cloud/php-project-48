<?php

namespace Hexlet\Code\Formatters;

class Json
{
    public static function format($ast)
    {
        return json_encode($ast);
    }
}
