<?php

use FpDbTest\Database;
use FpDbTest\DatabaseTest;

spl_autoload_register(function ($class) {
    $a = array_slice(explode('\\', $class), 1);
    if (!$a) {
        throw new Exception();
    }
    $filename = implode('/', [__DIR__, ...$a]) . '.php';
    require_once $filename;
});

$mysqli = @new mysqli(
    getenv('MYSQL_HOST'),
    getenv('MYSQL_USERNAME'),
    getenv('MYSQL_ROOT_PASSWORD'),
    getenv('MYSQL_DATABASE'),
    getenv('MYSQL_PORT')
);

if ($mysqli->connect_errno) {
    throw new Exception($mysqli->connect_error);
}

$db = new Database($mysqli);
$test = new DatabaseTest($db);
try {
    $test->testBuildQuery();
} catch (Exception $e) {
    exit($e->getMessage());
}

exit('OK');
