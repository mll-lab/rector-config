<?php

namespace App;

function a(string $s) { return preg_match_all("/a/", $s) === 1; }

?>
