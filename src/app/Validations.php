<?php

namespace Bankas;

use Bankas\Safe;
use Bankas\Messages as M;

class Validations
{

    public static function nameValid(string $name)
    {
        if ((strlen($name) < 4)) {
            M::add('Vardas ir pavardė turi būti ilgesni nei 3 simboliai!', 'alert');
            return 0;
        } else {
            return 1;
        }
    }
    public static function idValid(string $ak)
    {
        if (strlen($ak) !== 11) {
            M::add('Neteisingas asmens kodo  formatas', 'alert');
            return  0;
        }
        foreach (Safe::get()->showAll() as $key => $val) {
            if ($val['personId'] == $ak) {
                M::add('Vartotojas su tokiu asmens kodu jau yra sistemoje', 'alert');
                return  0;
            }
        }

        return 1;
    }
}
