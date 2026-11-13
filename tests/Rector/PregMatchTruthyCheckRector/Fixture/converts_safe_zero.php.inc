<?php

namespace App;

function a(string $s) { return [\Safe\preg_match("/a/", $s) === 0, 0 !== \Safe\preg_match("/a/", $s) ? 1 : 2, \Safe\preg_match("/a/", $s) !== 0 && true]; }

?>
-----
<?php

namespace App;

function a(string $s) { return [!\Safe\preg_match("/a/", $s), \Safe\preg_match("/a/", $s) ? 1 : 2, \Safe\preg_match("/a/", $s) && true]; }

?>
