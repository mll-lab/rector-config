<?php

namespace App;

function a(string $s, bool $b)
{
    if ($b) {
    } elseif (preg_match("/a/", $s) === 1) {
    }
    do {
    } while (preg_match("/b/", $s) === 1);
    $c = ! (preg_match("/c/", $s) === 1);
    $d = $b || preg_match("/d/", $s) === 1;
    $e = $b and preg_match("/e/", $s) === 1;
    $f = $b or preg_match("/f/", $s) === 1;
    $g = $b xor preg_match("/g/", $s) === 1;
}

?>
-----
<?php

namespace App;

function a(string $s, bool $b)
{
    if ($b) {
    } elseif (preg_match("/a/", $s)) {
    }
    do {
    } while (preg_match("/b/", $s));
    $c = ! (preg_match("/c/", $s));
    $d = $b || preg_match("/d/", $s);
    $e = $b and preg_match("/e/", $s);
    $f = $b or preg_match("/f/", $s);
    $g = $b xor preg_match("/g/", $s);
}

?>
