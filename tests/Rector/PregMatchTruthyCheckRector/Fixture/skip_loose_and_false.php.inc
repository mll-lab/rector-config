<?php

namespace App;

function a(string $s) { return [preg_match("/a/", $s) == 1, preg_match("/a/", $s) === false]; }

?>
