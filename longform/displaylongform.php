<?php
// self_eval_combined.php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../db/db.php';
$link = db();

$CID = $_GET['CID'] ?? '';

if ($CID === '') {
  $error = 'Missing CID.';
} else {
  $html = '';
  $stmt = $link->prepare('SELECT html FROM ic_candidate_self_eval WHERE id = ? LIMIT 1');
  if ($stmt) {
    $stmt->bind_param('s', $CID);
    $stmt->execute();
    $stmt->bind_result($html);
    $found = $stmt->fetch();
    $stmt->close();

    if (!$found) {
      $error = 'No record found for that CID.';
    }
  } else {
    $error = 'Database error preparing statement.';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Resume / Self Evaluation</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    html, body { height: 100%; }
    body {
      margin: 0;
      display: flex;
      justify-content: center;
      background-color: #f7f7f7;
      font-family: Arial, Helvetica, sans-serif;
      color: #222;
    }

    .main-container {
      width: 100%;
      max-width: 800px;
      background: #fff;
      padding: 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      box-sizing: border-box;
    }

    .main-container img { max-width: 100%; height: auto; }
    .main-container iframe { max-width: 100%; }

    .notice {
      padding: 16px;
      border: 1px solid #e0b4b4;
      background: #fff6f6;
      color: #9f3a38;
      border-radius: 4px;
    }
  </style>
</head>
<body>
  <div class="main-container">
    <?php if (isset($error)): ?>
      <div class="notice"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php else: ?>
      <?php echo $html; ?>
    <?php endif; ?>
  </div>
</body>
</html>
