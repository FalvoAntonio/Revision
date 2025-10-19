<?php

namespace App\Includes;

final class ErrorHandler
{
    public static function register(array $options = []): void
    {
        $env = $options['env'] ?? (getenv('APP_ENV') ?: 'prod');
        $display = $options['display'] ?? ($env === 'dev');

        error_reporting(E_ALL);
        ini_set('display_errors', $display ? '1' : '0');
        ini_set('log_errors', '1');

        if (!empty($options['log_file'])) {
            ini_set('error_log', $options['log_file']);
        }

        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleError(int $severity, string $message, string $file = '', int $line = 0): bool
    {
        if (!(error_reporting() & $severity)) {
            return false;
        }
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }

    public static function handleException(\Throwable $e): void
    {
        $id = bin2hex(random_bytes(6));
        $payload = sprintf(
            '[%s] %s: %s in %s:%d%s%s',
            $id,
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            PHP_EOL,
            $e->getTraceAsString()
        );

        error_log($payload);

        if (PHP_SAPI !== 'cli') {
            http_response_code(500);
            $display = filter_var(ini_get('display_errors'), FILTER_VALIDATE_BOOLEAN);
            if ($display) {
                echo '<pre>' . htmlspecialchars($payload, ENT_QUOTES, 'UTF-8') . '</pre>';
            } else {
                echo 'Une erreur interne est survenue. Code: ' . $id;
            }
        }
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
            self::handleException(new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']));
        }
    }
}