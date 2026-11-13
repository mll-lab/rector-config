<?php

namespace App;

function a(string $s) { return [preg_match("/a/", $s) === 0, preg_match("/a/", $s) !== 0]; }

?>
