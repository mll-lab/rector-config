<?php

namespace App;

function a(string $s) { return preg_match("/a/", $s, $m) !== 1; }

?>
-----
<?php

namespace App;

function a(string $s) { return !preg_match("/a/", $s, $m); }

?>
