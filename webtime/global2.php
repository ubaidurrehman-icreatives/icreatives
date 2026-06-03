<?php
foreach ($_POST as $key => $value) {
    // Sanitize key and value for HTML output
    $safeKey = htmlspecialchars($key, ENT_QUOTES, 'UTF-8');
    $safeValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

    // If the value is numeric, output it without quotes
    if (is_numeric($value)) {
        echo "<input type='hidden' name={$safeKey} value={$safeValue}>\r\n";
    } else {
        echo "<input type='hidden' name='{$safeKey}' value='{$safeValue}'>\r\n";
    }
}
?>

