<?php

/**
 * Loads key=value pairs from a .env file into $_ENV.
 * Skips blank lines and lines starting with #.
 * Does not override variables already set in the environment.
 */
function loadEnv(string $filePath): void
{
    if (!is_file($filePath) || !is_readable($filePath)) {
        throw new RuntimeException(".env file not found or unreadable: {$filePath}");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        [$key, $value] = array_map('trim', explode('=', $line, 2)) + [1 => ''];

        if ($key === '') {
            continue;
        }

        // Strip optional surrounding quotes from the value
        if (preg_match('/^(["\'])(.*)(\1)$/', $value, $m)) {
            $value = $m[2];
        }

        // Only set if not already defined in the real environment
        if (!isset($_ENV[$key]) && !getenv($key)) {
            $_ENV[$key]  = $value;
            putenv("{$key}={$value}");
        }
    }
}
