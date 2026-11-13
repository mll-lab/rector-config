<?php

namespace App;

function a(string $s) { return \Safe\preg_match("/a/", $s, $m) !== 1; }

?>
-----
<?php

namespace App;

function a(string $s) { return !\Safe\preg_match("/a/", $s, $m); }

?>
