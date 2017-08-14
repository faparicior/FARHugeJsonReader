<?php

namespace Faparicior\HugeJsonImport;

class Parser
{
    const STREAM_BUFFER = 60000;

    private $handle;
    private $lexer;
    private $uniqueJson;
    private $isString;
    private $buffer;

    public function __construct($handle, Lexer $lexer)
    {
        $this->handle = $handle;
        $this->lexer = $lexer;
    }

    public function parse()
    {
        $this->buffer = $this->buffer.stream_get_contents(
                $this->handle,
                self::STREAM_BUFFER - strlen($this->buffer)
            );

        $len = strlen($this->buffer);
        
        for ($i=0; $i<$len; $i++) {
            $character = substr($this->buffer, $i, 1);
            $characterType = $this->lexer->resolveSymbol($character);

            switch ($characterType) {
                case Lexer::OPEN_ENTITY_FLAG:
                    if ($this->isString) {
                        $this->uniqueJson = $this->uniqueJson.$character;
                        break;
                    }
                    $this->uniqueJson = Lexer::OPEN_ENTITY;
                    break;
                case Lexer::CLOSE_ENTITY_FLAG:
                    $this->uniqueJson = $this->uniqueJson.$character;
                    if ($this->isString) {
                        break;
                    }
                    $this->buffer = substr($this->buffer, $i+1);
                    return json_decode($this->uniqueJson);
                    break;
                case Lexer::VALUE_ASSIGN_FLAG:
                    $this->uniqueJson = $this->uniqueJson.Lexer::VALUE_ASSIGN;
                    break;
                case Lexer::TEXT_FIELD_FLAG:
                    $this->isString = !$this->isString;
                    $this->uniqueJson = $this->uniqueJson.$character;
                    break;
                case Lexer::NEW_VALUE_FLAG:
                    $this->uniqueJson = $this->uniqueJson.$character;
                    break;
                default:
                    $this->uniqueJson = $this->uniqueJson.$character;
                    break;
            }
        }
    }
}
