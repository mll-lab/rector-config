<?php

namespace App;

function a(string $s, $x) { return preg_match("/a/", $s) === 1 ?: $x; }

?>
