<?php

namespace Faparicior\FARHugeJsonImport;

class Lexer
{
    const OPEN_ENTITY = '{';
    const CLOSE_ENTITY = '}';
    const VALUE_ASSIGN = ':';
    const TEXT_FIELD = '"';
    const NEW_VALUE = ',';
    const SPECIAL_CHARACTER = '\\';

    const OPEN_ENTITY_FLAG = 1;
    const CLOSE_ENTITY_FLAG = 2;
    const VALUE_ASSIGN_FLAG = 3;
    const TEXT_FIELD_FLAG = 4;
    const NEW_VALUE_FLAG = 5;
    const CHARACTER_FLAG = 6;
    const SPECIAL_CHARACTER_FLAG = 7;

    public function resolveSymbol($char)
    {
        switch ($char) {
        case self::OPEN_ENTITY:
            return self::OPEN_ENTITY_FLAG;
            break;
        case self::CLOSE_ENTITY:
            return self::CLOSE_ENTITY_FLAG;
            break;
        case self::VALUE_ASSIGN:
            return self::VALUE_ASSIGN_FLAG;
            break;
        case self::TEXT_FIELD:
            return self::TEXT_FIELD_FLAG;
            break;
        case self::NEW_VALUE:
            return self::NEW_VALUE_FLAG;
            break;
        case self::SPECIAL_CHARACTER:
        return self::SPECIAL_CHARACTER_FLAG;
            break;
        default:
            return self::CHARACTER_FLAG;
        }
    }
}
