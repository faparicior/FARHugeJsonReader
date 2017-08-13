<?php

class Lexer
{
    public function resolveSymbol($char)
    {
        switch ($char) {
        case '{':
            echo "OpenEntity";
            break;
        case '}':
            echo "CloseEntity";
            break;
        case '\"':
            echo "TextField(".$char.")";
            break;
        case ',':
            echo "NewValue";
            break;
        }
    }
}
