<?php

namespace App\Helpers;

use NumberFormatter;

class NumberHelper
{
   public static function inFrenchWords(float $number): string
{
    $formatter = new \NumberFormatter('fr', \NumberFormatter::SPELLOUT);

    $entier = floor($number);
    $decimal = round(($number - $entier) * 100);

    $wordsEntier = str_replace('-', ' ', $formatter->format($entier));
    $texte = ucfirst($wordsEntier) . ' FCFA';

    if ($decimal > 0) {
        $wordsDecimal = str_replace('-', ' ', $formatter->format($decimal));
        $texte .= ' et ' . $wordsDecimal . ' centimes';
    }

    return $texte;
}

}
