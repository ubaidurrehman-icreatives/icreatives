<html>
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>
<body>

<!DOCTYPE html>
     <style>
        #contactForm {
            position: relative;
        }

        #results {
            display: none;
            border: 1px solid #ccc;
            position: absolute;
            top: 100%;
            left: 50%; /* Position the results box at the center horizontally */
            transform: translateX(-50%); /* Center the results box */
            width: 600px; /* Set the width of the results box */
            max-height: 150px;
            overflow-y: auto;
            z-index: 1;
        }

        #results div {
            padding: 5px;
            cursor: pointer;
            text-align: center;
        }

        #results div:hover {
            background-color: #E9E9E0;
        }
    </style>
  <?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
<?php
ini_set('session.cookie_lifetime', 7776000); // 3 months in seconds

session_start();
$user = $_SESSION['recruiter_id'];

if(!isset($_SESSION['recruiter_id'])) {
  session_regenerate_id();
  $user = $_REQUEST['user'];
  $_SESSION['recruiter_id'] = $user;
 //  header("Location: /client-login");
 //  return;
}
?>

  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">



    <div style="text-align:center; padding-top:100px;">
        <form id="contactForm" action="/portal/manatal_client_portal_dashboard.php/?user=<?php echo $user; ?>&first=1" method="post">
            <div>
                <input type="hidden" name="user" value="<?php echo $user; ?>">
                <input type="hidden" name="identifier" id="identifier" value="">
                <input type="hidden" name="id" id="id" value="">
                <label for="contactName"><b>Enter Any Part of Contact Name or Email Address:</b></label>
                <div style="padding-bottom:20px;">(contact portal access must be turned on. it may take 3 minutes to propagate: refresh)</div>
                <input type="text" id="contactName" name="contactName" autocomplete="off" required autofocus>
                <input type="submit" value="Submit" id="submitButton">
                <div id="results"></div>
            </div>
        </form>
    </div>


    <?php
    // Replace these variables with your actual database credentials
	// $conn = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());
require_once  dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
require_once __DIR__ . '/../db/db.php';
$conn = db();   

    // Fetch all contacts with their names and email addresses
    $sql = "SELECT id, display_name, email FROM ic_contacts WHERE  icreativesportalaccess = 1 AND email <> 'missing_contact_email@blindemail.com'";
    $result = mysqli_query($conn,$sql);
	
    // Create an array of contact names and emails
    $contacts = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $contacts[] = [
                'display_name' => $row['display_name'],
                'email' => $row['email'],
                'id' => $row['id'],
            ];
        }
    }

    $conn->close();
    ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const contactNameInput = document.getElementById('contactName');
        const resultsContainer = document.getElementById('results');
        const identifierInput = document.getElementById('identifier');
        const id = document.getElementById('id');
        const contactForm = document.getElementById('contactForm');
        const submitButton = document.getElementById('submitButton');
        const contacts = <?php echo json_encode($contacts); ?>;

        contactNameInput.addEventListener('input', function () {
            const inputValue = contactNameInput.value.trim().toLowerCase();
            const matchingContacts = contacts.filter(contact =>
                contact.display_name.toLowerCase().includes(inputValue) ||
                contact.email.toLowerCase().includes(inputValue)
            );

            // Display the suggestions
            resultsContainer.innerHTML = '';
            matchingContacts.forEach(contact => {
                const div = document.createElement('div');
                div.textContent = contact.display_name + ' (' + contact.email + ')';
                div.addEventListener('click', function () {
                    contactNameInput.value = contact.display_name;
                    identifierInput.value = contact.email;
                    id.value = contact.id;
                    resultsContainer.style.display = 'none';
                    submitButton.focus(); // Focus on the submit button
                });
                resultsContainer.appendChild(div);
            });

            resultsContainer.style.display = matchingContacts.length > 0 ? 'block' : 'none';
        });

        contactForm.addEventListener('submit', function (event) {
            if (!identifierInput.value) {
                event.preventDefault();
                alert('Please select a valid contact name from the suggestions.');
            }
        });

        // Submit the form when Enter key is pressed
        contactNameInput.addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent default form submission behavior
                contactForm.submit();
            }
        });

        // Hide the results when clicking outside the input and results
        document.addEventListener('click', function (event) {
            if (!event.target.matches('#contactName') && !event.target.matches('#results div')) {
                resultsContainer.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>
