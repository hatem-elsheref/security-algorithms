<?php

function modInverseWithExtendedAlgorithm($a , $m)
{
    return modInverse($a , $m);
}


function modInverse($a, $m)
{
    $x = 0;
    $y = 0;
    $g = gcdExtended($a, $m, $x, $y);
    if ($g != 1)
        echo "Inverse doesn't exist";
    else
    {
        // m is added to handle negative x
       return ($x % $m + $m) % $m;

    }
}


function gcdExtended($a, $b, &$x, &$y)
{
    // Base Case
    if ($a == 0)
    {
        $x = 0;
        $y = 1;
        return $b;
    }


    $gcd = gcdExtended($b%$a, $a, $x1, $y1);

    // Update x and y using results of
    // recursive call
    $x = $y1 - (int)($b/$a) * $x1;
    $y = $x1;

    return $gcd;
}

