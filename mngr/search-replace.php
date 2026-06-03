<?php
/*
    Simple PHP File Search & Replace Tool
    - Test Run: shows what would change
    - Full Run: actually updates files
    - Searches selected directory recursively
    - Skips itself
*/
/*
// we are protected by directory
$password = '';

if (!isset($_POST['password']) || $_POST['password'] !== $password) {
    ?>
    <form method="post">
        <h2>Login</h2>
        <input type="password" name="password" placeholder="Password">
        <button type="submit">Enter</button>
    </form>
    <?php
    exit;
}
*/
$baseDir = __DIR__; // Default starting point
$self = realpath(__FILE__);

$allowedExtensions = [
    'php', 'inc', 'html', 'htm', 'js', 'css', 'txt'
];

function scanAndReplace($dir, $search, $replace, $extensions, $dryRun = true, $selfFile = null) {
    $results = [];

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if (!$file->isFile()) {
            continue;
        }

        $filePath = $file->getRealPath();

        if ($filePath === $selfFile) {
            continue;
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if (!in_array($ext, $extensions)) {
            continue;
        }

        if (!is_readable($filePath) || (!$dryRun && !is_writable($filePath))) {
            $results[] = [
                'file' => $filePath,
                'matches' => 0,
                'status' => 'Skipped, not readable or writable'
            ];
            continue;
        }

        $content = file_get_contents($filePath);
        $count = substr_count($content, $search);

        if ($count > 0) {
            if (!$dryRun) {
                $newContent = str_replace($search, $replace, $content);
                file_put_contents($filePath, $newContent);
            }

            $results[] = [
                'file' => $filePath,
                'matches' => $count,
                'status' => $dryRun ? 'Test only, not changed' : 'Updated'
            ];
        }
    }

    return $results;
}

$results = [];
$ran = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run'])) {
    $selectedDir = realpath($_POST['directory'] ?? '');
    $search = $_POST['search'] ?? '';
    $replace = $_POST['replace'] ?? '';
    $dryRun = ($_POST['run'] === 'test');

    if (!$selectedDir || !is_dir($selectedDir)) {
        $error = 'Invalid directory.';
    } elseif ($search === '') {
        $error = 'Search text cannot be empty.';
    } else {
        $results = scanAndReplace(
            $selectedDir,
            $search,
            $replace,
            $allowedExtensions,
            $dryRun,
            $self
        );
        $ran = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Search and Replace Tool</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background: #f5f5f5;
        }

        .box {
            background: white;
            padding: 25px;
            max-width: 900px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 12px rgba(0,0,0,.1);
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0 18px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 18px;
            margin-right: 10px;
            cursor: pointer;
        }

        .test {
            background: #777;
            color: white;
            border: none;
        }

        .full {
            background: #b22625;
            color: white;
            border: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            font-size: 14px;
        }

        th {
            background: #eee;
        }

        .warning {
            color: #b22625;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>PHP File Search and Replace</h2>

    <p class="warning">
        Make a backup before running the full replacement.
    </p>

    <?php if (!empty($error)): ?>
        <p class="warning"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="password" value="<?php echo htmlspecialchars($_POST['password']); ?>">

        <label>Directory to search</label>
        <input type="text" name="directory" value="<?php echo htmlspecialchars($_POST['directory'] ?? $baseDir); ?>">

        <label>Search for</label>
        <textarea name="search" rows="4"><?php echo htmlspecialchars($_POST['search'] ?? ''); ?></textarea>

        <label>Replace with</label>
        <textarea name="replace" rows="4"><?php echo htmlspecialchars($_POST['replace'] ?? ''); ?></textarea>

        <button class="test" type="submit" name="run" value="test">
            Test Run
        </button>

        <button class="full" type="submit" name="run" value="full" onclick="return confirm('Are you sure? This will modify files. Make sure you have a backup first.');">
            Full Run
        </button>
    </form>

    <?php if ($ran): ?>
        <h3>Results</h3>

        <?php if (empty($results)): ?>
            <p>No matches found.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>File</th>
                    <th>Matches</th>
                    <th>Status</th>
                </tr>

                <?php foreach ($results as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['file']); ?></td>
                        <td><?php echo (int)$row['matches']; ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>