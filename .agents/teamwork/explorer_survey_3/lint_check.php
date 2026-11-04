<?php

$dirs = [
    __DIR__ . '/../../../app/Http/Controllers',
    __DIR__ . '/../../../app/Models',
    __DIR__ . '/../../../app/Services',
    __DIR__ . '/../../../app/Imports',
    __DIR__ . '/../../../routes',
];

$errors = [];
$total = 0;

foreach ($dirs as $dir) {
    if (!is_dir($dir)) continue;
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $total++;
            $filepath = $file->getRealPath();
            $output = [];
            $returnVar = 0;
            exec("php -l " . escapeshellarg($filepath) . " 2>&1", $output, $returnVar);
            if ($returnVar !== 0) {
                $errors[] = [
                    'file' => $filepath,
                    'output' => implode("\n", $output)
                ];
            }
        }
    }
}

echo "Total PHP files linted: $total\n";
echo "Syntax errors found: " . count($errors) . "\n";
foreach ($errors as $e) {
    echo "=== ERROR IN: " . $e['file'] . " ===\n";
    echo $e['output'] . "\n\n";
}
