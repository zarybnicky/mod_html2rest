<?php
spl_autoload_register(
    function ($class) {
        $prefix = 'Unifor\\Import\\Html2Rest';
        $base_dir = __DIR__ . '/src/';

        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }

        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        if (file_exists($file)) {
            include $file;
        }
    }
);

if (!$argc) {
    return '';
}

define('GLOBALCOREURL', 'http://localhost/');

require 'Html2RestConverter.php';
$conv = new Html2RestConverter();
echo $conv->process(file_get_contents($argv[1]));
