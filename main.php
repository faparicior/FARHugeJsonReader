<?php
require "vendor/autoload.php";

use Faparicior\HugeJsonImport\Parser;
use Faparicior\HugeJsonImport\Lexer;

$handle = fopen("./test.json", "r");
if ($handle) {
    $parser = new Parser($handle, new Lexer());
    while($item = $parser->parse())
    {
        //   var_dump($item);
    }
    fclose($handle);

} else {
    echo "ERROR OPENING FILE!!!!!!";
}
