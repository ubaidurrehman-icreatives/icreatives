<!DOCTYPE html>
<html>
<head>
    <title>Open Form</title>
</head>
<body>
    <script>
        function openNewWindow() {
            var queryParams = new URLSearchParams(window.location.search);
            var matchId = queryParams.get("match_id");
            var formUrl = "https://www.icreatives.com/api/manatal/match.php?match_id=" + matchId;
            var width = 400;
            var height = 650;
            var left = (window.innerWidth - width) / 2;
            var top = (window.innerHeight - height) / 2;
            var features = `width=${width},height=${height},top=${top},left=${left},location=no,menubar=no,toolbar=no`;
			

            // Open the form window
            var formWindow = window.open(formUrl, "_blank", features);

            // Close the original window
            window.close();
        }

        // Call the function when the page loads
        openNewWindow();
    </script>
</body>
</html>
