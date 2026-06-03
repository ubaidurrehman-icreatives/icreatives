<?php
require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/pdf_autoload.php";

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
use setasign\Fpdi\PdfReader\PdfReader as PdfReaderAlias;
use setasign\Fpdi\PdfReader\StreamReader;

echo "<pre>✅ Starting FPDI + StreamReader test...\n";

// Load resume.pdf from same directory
$pdfPath = __DIR__ . '/resume.pdf';

if (!file_exists($pdfPath)) {
    die("❌ resume.pdf not found in current directory\n</pre>");
}

$pdfContent = file_get_contents($pdfPath);
if (!$pdfContent) {
    die("❌ Failed to read resume.pdf\n</pre>");
}

try {
    $pdf = new Fpdi();
    $stream = StreamReader::createByString($pdfContent);
    $pageCount = $pdf->setSourceFile($stream);

    $pdf->addPage();
    $templateId = $pdf->importPage(1);
    $pdf->useTemplate($templateId);

    ob_start();
    $pdf->Output('', 'I');
    ob_end_clean();

    echo "✅ Successfully read and processed resume.pdf in memory\n";
    echo "📄 Total pages in PDF: $pageCount\n";
} catch (Exception $e) {
    echo "❌ Error during PDF read: " . $e->getMessage() . "\n";
}
echo "</pre>";
