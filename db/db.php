<?php
// Put this in a shared file, e.g. includes/db_singleton.php, and require_once it everywhere.

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // throw on errors

// Single-per-request mysqli connection stored globally
$GLOBALS['__DB_CONN'] = null;

function db(): mysqli {
    if ($GLOBALS['__DB_CONN'] instanceof mysqli) return $GLOBALS['__DB_CONN'];

    $host = "localhost";
    $db   = "portal";
    $user = "ibis";
    $pw   = 'pZCD@4ZCSgA$$E!';

    $attempts = 0;
    while ($attempts < 3) {
        $attempts++;
        try {
            $conn = new mysqli($host, $user, $pw, $db);
            // Best-effort session tweaks (may be ignored on shared hosting)
            $conn->set_charset('utf8mb4');
            @$conn->query("SET SESSION wait_timeout=10");
            @$conn->query("SET SESSION interactive_timeout=10");

            $GLOBALS['__DB_CONN'] = $conn;

            // Ensure auto-close at end of request (even on die/exit/fatal)
            static $shutdownRegistered = false;
            if (!$shutdownRegistered) {
                register_shutdown_function(function () {
                    db_close();
                });
                $shutdownRegistered = true;
            }
            return $GLOBALS['__DB_CONN'];
        } catch (mysqli_sql_exception $e) {
            if ((int)$e->getCode() === 1203) { // ER_TOO_MANY_USER_CONNECTIONS
                usleep(250_000); // 250ms backoff
                continue;
            }
            throw $e;
        }
    }

    http_response_code(503);
    echo "Temporarily busy. Please try again.";
    exit;
}

function db_close(): void {
    if ($GLOBALS['__DB_CONN'] instanceof mysqli) {
        // Close any pending statements/results as needed in your code before calling this
        @$GLOBALS['__DB_CONN']->close();
        $GLOBALS['__DB_CONN'] = null;
    }
}


/**
 * Redirect helper:
 * - releases PHP session lock (so the next request doesn’t block),
 * - closes DB immediately (freeing one connection slot),
 * - performs a Location redirect safely.
 */
function safe_redirect(string $url, ?int $status = null): void {
    // Choose a good default if caller didn’t specify
    if ($status === null) {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $status = in_array($method, ['POST','PUT','PATCH','DELETE'], true) ? 303 : 302;
    }

    if (session_status() === PHP_SESSION_ACTIVE) session_write_close();
    if (function_exists('db_close')) db_close();   // your singleton closer

    while (ob_get_level() > 0) { ob_end_clean(); }

    if (!headers_sent()) {
        header("Location: $url", true, $status);
        exit;
    } else {
        echo '<script>location.href=' . json_encode($url) . ';</script>';
        exit;
    }
}
?>