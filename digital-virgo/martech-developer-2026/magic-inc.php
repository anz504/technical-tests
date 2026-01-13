<?php

function magic_inc($value, $direction) {
    if (!is_numeric($value) || !is_finite($value) || $value == 0) {
        return 0;
    }

    $sign = $value < 0 ? -1 : 1;
    $abs = abs($value);

    $exp = floor(log10($abs));
    $step = pow(10, $exp);

    $normalized = ceil($abs / $step) * $step;

    // badly formatted → normalization is the step
    if ($normalized != $abs) {
        return $sign * $normalized;
    }

    if ($direction === 'inc') {
        return $sign * ($abs + $step);
    }

    if ($direction === 'dec') {
        $res = $abs - $step;
        return $res <= 0 ? 0 : $sign * $res;
    }

    return 0;
}

// Test cases
echo "Rango 0-1:\n";
echo "magic_inc(0.5, 'dec') => " . magic_inc(0.5, 'dec') . "\n"; // esperado: 0.4
echo "magic_inc(0.1, 'dec') => " . magic_inc(0.1, 'dec') . "\n"; // esperado: 0.09
echo "magic_inc(0.5, 'inc') => " . magic_inc(0.5, 'inc') . "\n"; // esperado: 0.6