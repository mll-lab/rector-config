<?php

namespace App;

function a(string $s) { if (preg_match("/a/", $s, $m) === 1) { return $m; } }

?>
-----
<?php

namespace App;

function a(string $s) { if (preg_match("/a/", $s, $m)) { return $m; } }

?>
