<?php
function generateUsername($length = 6): string {
    // Prefix for clarity
    $prefix = 'user';
    // Random alphanumeric part
    $random = strtoupper(bin2hex(random_bytes($length / 2)));
    return $prefix . $random;
}

function generatePassword($length = 12): string {
    $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lower = 'abcdefghijklmnopqrstuvwxyz';
    $digits = '0123456789';
    $symbols = '!@#$%^&*()-_=+[]{}<>?';
    $all = $upper . $lower . $digits . $symbols;

    // Ensure password has at least one from each category
    $password = '';
    $password .= $upper[random_int(0, strlen($upper) - 1)];
    $password .= $lower[random_int(0, strlen($lower) - 1)];
    $password .= $digits[random_int(0, strlen($digits) - 1)];
    $password .= $symbols[random_int(0, strlen($symbols) - 1)];

    // Fill remaining length randomly
    for ($i = 4; $i < $length; $i++) {
        $password .= $all[random_int(0, strlen($all) - 1)];
    }

    // Shuffle to randomize order
    $pwArray = str_split($password);
    shuffle($pwArray);
    return implode('', $pwArray);
}

