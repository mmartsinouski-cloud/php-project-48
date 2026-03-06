<?php

namespace Hexlet\Code\Formatters;

class Plain
{
    public static function format($ast)
    {
        return json_encode($ast);
    }
}
