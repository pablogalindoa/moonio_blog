<?php
// A read-only CI endpoint verifies Apache, PHP, CI autoloading and the Docker DB.
$context = stream_context_create(['http' => ['timeout' => 4]]);
$body = @file_get_contents('http://127.0.0.1/blog/index.php/api/users', false, $context);
exit($body !== false && is_array(json_decode($body, true)) && json_last_error() === JSON_ERROR_NONE ? 0 : 1);
