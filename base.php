<?php

// use Illuminate\Database\Seeder;

class PatientAddressesTableSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::transaction(function () {

            $uniqueJson = "";
            $isAnInitLine = false;
            $isAnEndLine = false;
            $arrayCharacter = false;

            // DB::table('patient_addresses')->delete();

            $handle = fopen("./tratamiento.json", "r");
            if ($handle) {
                // while(($content = stream_get_contents($handle, self::STREAM_BUFFER)) !== false) {
                    // var_dump($content);
                // }
                $parser = new Parser($handle, new Lexer());
                // $lexer = new Lexer();
                // $content = stream_get_contents($handle, self::STREAM_BUFFER);
                // $parser->parse($content);
                // $parser->parse();
                for($i = 0; $i < 10; $i++) {
                    var_dump($parser->parse());
                }

                // var_dump($content);
                // for ($i=0; $i<600; $i++) {
                //     $lexer->resolveSymbol(substr($content, $i, 1));
                // }

/*                
                while (($line = fgets($handle)) !== false) {
                    if ((strpos($line, "[") !== false) || (strpos($line, "]") !== false)) {
                        $arrayCharacter = true;
                    }

                    if ((strpos($line, "[{") !== false) || (strpos($line, "{") !== false)) {
                        $uniqueJson = "{";
                        $isAnInitLine = true;
                    }

                    if ((strpos($line, "}]") !== false) || (strpos($line, "}") !== false) || (strpos($line, "},") !== false)) {
                        $uniqueJson = $uniqueJson . "}";
                        $isAnEndLine = true;
                    }

                    if (!$isAnInitLine && !$isAnEndLine && !$arrayCharacter) {
                        $uniqueJson = $uniqueJson . $line;
                    }

                    if ($isAnEndLine) {
                        $obj = json_decode($uniqueJson);

                        // DB::table('patient_addresses')->insert(
                        //     array (
                        //         'id' => $obj->id,
                        //         'person_uuid' => $obj->person_uuid,
                        //         'name' => $obj->name,
                        //         'address_line1' => $obj->address_line1,
                        //         'address_line2' => $obj->address_line2,
                        //         'city' => $obj->city,
                        //         'state' => $obj->state,
                        //         'country' => $obj->country,
                        //         'postal_code' => $obj->postal_code,
                        //         'created_at'=> $obj->created_at,
                        //         'updated_at'=> $obj->updated_at
                        //     )
                        // );
                    }

                    $isAnInitLine = false;
                    $isAnEndLine = false;
                    $arrayCharacter = false;
                }
*/
                fclose($handle);

            } else {
                echo "ERROR OPENING FILE!!!!!!";
            }

//        DB::table('patient_addresses')->delete();
//        $json = File::get(base_path()."/database/data/".env('SEED_FOLDER', 'test')."/patient_addresses.json");
//        $data = json_decode($json);
//        foreach ($data as $obj) {
//            DB::table('patient_addresses')->insert(
//                array (
//                    'id' => $obj->id,
//                    'person_uuid' => $obj->person_uuid,
//                    'name' => $obj->name,
//                    'address_line1' => $obj->address_line1,
//                    'address_line2' => $obj->address_line2,
//                    'city' => $obj->city,
//                    'state' => $obj->state,
//                    'country' => $obj->country,
//                    'postal_code' => $obj->postal_code,
//                    'created_at'=> $obj->created_at,
//                    'updated_at'=> $obj->updated_at
//                )
//            );
//        }
        // });
    }
}

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

class Parser
{
    const STREAM_BUFFER = 600;

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

    // public function parse($content)
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
                    // $obj = json_decode($this->uniqueJson);
                    // var_dump(json_encode($obj));
                    // var_dump($obj);
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

$test = new PatientAddressesTableSeeder();
$test->run();