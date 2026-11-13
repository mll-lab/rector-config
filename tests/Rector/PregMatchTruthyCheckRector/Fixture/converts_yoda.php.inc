<?php

namespace App;

function a(string $s) { while (1 === preg_match("/a/", $s)) { } }

?>
-----
<?php

namespace App;

function a(string $s) { while (preg_match("/a/", $s)) { } }

?>
