<?php

//  __DIR__ = /var/www/vhosts/icreatives.com/httpdocs/api/manatal

exit();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// 
    // Create a new Dompdf instance
  
	require_once '/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/dompdf/autoload.inc.php';
	require_once '/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/dompdf/vendor/autoload.php';
	require_once '/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/dompdf/vendor/composer/autoload_real.php';


	return ComposerAutoloaderInita93271dd13b797bc42be7fa85eb30ecb::getLoader();

	// require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";


	// use Dompdf\Dompdf;
	$pdf = new Dompdf();
	
    $url = isset($_POST['url']) ? $_POST['url'] : '';
	
    if ($url) {
        // Fetch the HTML content from the URL
        $htmlContent = file_get_contents($url);

        // Load HTML content into Dompdf
        $pdf->loadHtml($htmlContent);

        // Set paper size and rendering options
        $pdf->setPaper('A4', 'portrait');

        // Render the PDF
        $pdf->render();

        // Output the PDF for download
        $pdf->stream('web_page.pdf', ['Attachment' => 0]);
    } else {
        $error = 'Please provide a URL.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generate PDF from URL</title>
</head>
<body>
    <h1>Generate PDF from URL</h1>
    <form method="post">
        <label for="url">Enter URL:</label>
        <input type="url" name="url" required>
        <button type="submit">Generate PDF</button>
    </form>
    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
</body>
</html>

