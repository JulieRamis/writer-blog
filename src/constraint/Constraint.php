<?php


namespace App\src\constraint;


class Constraint
{
    public function notBlank($name, $value)
    {
        if(empty($value) || preg_match("#^[[:blank:]\n]+$#", $value)){
                return 'Le champs ' . $name . ' est vide.';
            }
    }
    public function minLength($name, $value, $minSize)
    {
        if(strlen($value)<$minSize){
            return 'Le champ '.$name.' doit contenir au moins '.$minSize.' caractères.';
        }
    }
    public function maxLength($name, $value, $maxSize)
    {
        if(strlen($value) > $maxSize){
            return 'Le champ '.$name.' doit contenir au maximum '.$maxSize.' caractères.';
        }
    }
}