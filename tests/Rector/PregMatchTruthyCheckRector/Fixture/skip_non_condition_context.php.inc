<?php declare(strict_types=1);

namespace App;

function a(string $s): bool { return preg_match("/a/", $s) === 1; }

function b(string $s): array { return [\Safe\preg_match("/a/", $s) !== 0]; }

?>
