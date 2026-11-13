<?php

namespace App;

/** @return 1 */
function one(): int { return 1; }

function a(string $s) { if (preg_match("/a/", $s) !== ($e = 1)) { return $e; } }

function b(string $s) { if (preg_match("/a/", $s) === one()) { return; } }

?>
