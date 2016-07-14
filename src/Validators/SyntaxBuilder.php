<?php

namespace Laralib\L5scaffold\Validators;

class SyntaxBuilder
{

    /**
     * Create the PHP syntax for the given schema.
     *
     * @param  array $schema
     * @param  array $meta
     * @param  string $type
     * @param  bool $illuminate
     * @return string
     * @throws GeneratorException
     * @throws \Exception
     */
    public function create($schema)
    {
        $fieldsc = $this->createSchemaForValidation($schema);
        return $fieldsc;
    }

    private function createSchemaForValidation($schema)
    {
        $validator = '';
        foreach($schema as $s){
            $validator .= "'" . $s['name'] . "' => '";
            foreach($s['arguments'] as $k => $a) {
                if($a != null) {
                    $validator .= $k . ":" . str_replace(".", ",", str_replace("/", ":", $a));
                }elseif($a == null){
                    $validator .= str_replace(".", ",", str_replace("/", ":", $k));
                }
                if((count($s['arguments'])-1) != $k){
                    $validator .= "|";
                }
            }
            $validator .= "',\n\t\t\t";
        }
        return $validator;
    }
}