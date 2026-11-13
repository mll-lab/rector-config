<?php

namespace App;

use function Safe\preg_match;
function a(string $s) { return [preg_match("/a/", $s) !== 1, preg_match("/a/", $s) === 0]; }

?>
-----
<?php

namespace App;

use function Safe\preg_match;
function a(string $s) { return [!preg_match("/a/", $s), !preg_match("/a/", $s)]; }

?>
