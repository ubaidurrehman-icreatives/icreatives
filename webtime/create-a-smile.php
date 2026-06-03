<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ==================
// SETTINGS
// ==================
require_once dirname(__DIR__) . '/db/token.php';   // should define $token
// Example: $token = 'Token 92e3967b0...';  (make sure it includes "Token ")

// ==================
// AJAX HANDLER: lookup contact by email via Guzzle
// ==================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'lookup_contact') {
    header('Content-Type: application/json');

    $email = strtolower(trim($_POST['email'] ?? ''));

    if ($email === '') {
        echo json_encode(['error' => 'Please enter your email address.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => 'Please enter a valid email address.']);
        exit;
    }

    // Load Composer autoload (adjust path if needed)
    require_once dirname(__DIR__) . '/vendor/autoload.php';

    try {
        $client = new \GuzzleHttp\Client([
            'timeout' => 5,
        ]);

        // Manatal contacts lookup by email
		
		$response = $client->request('GET', 'https://api.manatal.com/open/v3/contacts/?email='.$email.'', [
		'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
		],
		]);
		
		
		
		
		/*
		
        $response = $client->request('GET', 'https://api.manatal.com/open/v3/contacts/', [
            'headers' => [
                'Authorization' => $token,        // e.g. "Token 92e3..."
                'accept'        => 'application/json',
            ],
            'query' => [
                'email' => $email,                // change to 'email_address' if required by API
            ],
        ]);
*/
    } catch (\Throwable $e) {
        echo json_encode(['error' => 'Error contacting Manatal: ' . $e->getMessage()]);
        exit;
    }

    $statusCode = $response->getStatusCode();
    if ($statusCode !== 200) {
        echo json_encode(['error' => 'Manatal API returned HTTP ' . $statusCode]);
        exit;
    }

    $responseStr = (string) $response->getBody();
    $results = json_decode($responseStr, true);

    // Uncomment this temporarily if you want to see what Manatal returns:
    // echo json_encode(['debug' => $results]); exit;

    // Manatal usually: { "results": [ { "id": ... }, ... ] }
    $first = null;
    if (isset($results['results']) && is_array($results['results']) && count($results['results']) > 0) {
        $first = $results['results'][0];
    } elseif (isset($results[0]) && is_array($results)) {
        $first = $results[0];
    }

    if (!$first) {
        echo json_encode(['error' => 'We couldn’t find a contact with that email.']);
        exit;
    }

    $id = $first['id'] ?? ($first['pk'] ?? null);

    if (!$id) {
        echo json_encode(['error' => 'Contact found but no ID field in response.']);
        exit;
    }

    echo json_encode(['id' => $id]);
    exit;
}

// ==================
// NORMAL PAGE RENDER (GET)
// ==================

$varib = $_REQUEST['varib'] ?? '';

if (strpos($varib, '-') !== false) {
    list($id, $first, $last) = explode('-', $varib, 3);
} else {
    $id    = $varib;
    $first = '';
    $last  = '';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create a Smile</title>

    <style>
body {
    margin: 0;
    padding: 0;
    background-color: #ffffff !important;   /* FULL PAGE WHITE */
    font-family: Arial, Helvetica, sans-serif;
    color: #333333;
}

        .page-wrapper {
            max-width: 1100px;
            margin: 40px auto 80px auto;
            padding: 40px 32px 48px 32px;
            background-color: #ffffff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            box-sizing: border-box;
        }

        .headline {
            text-align: left;
            color: #b22625;
            font-size: 28px;
            margin: 0 0 16px 0;
            font-weight: bold;
        }

        .intro-text {
            color: #b22625;
            font-size: 16px;
            line-height: 1.5;
            margin: 0 0 8px 0;
        }

        .intro-text + .intro-text {
            margin-top: 4px;
        }

        .underline-strong {
            font-weight: bold;
            text-decoration: underline;
        }

        .cta-note {
            color: #b22625;
            text-align: left;
            margin-top: 18px;
            font-size: 16px;
        }

        .feel-good {
            color: #b22625;
            margin-top: 6px;
            font-size: 16px;
        }

        .content-row {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            align-items: flex-start;
            margin-top: 30px;
        }

        .col-text,
        .col-media {
            flex: 1 1 320px;
            min-width: 280px;
        }

        .col-text {
            padding-right: 10px;
        }

        .col-media {
            text-align: center;
        }

        .email-label {
            display: block;
            margin-top: 22px;
            margin-bottom: 6px;
            font-size: 14px;
            color: #333333;
        }

        .email-input {
            width: 100%;
            max-width: 320px;
            padding: 8px 10px;
            font-size: 15px;
            border-radius: 4px;
            border: 1px solid #cccccc;
            box-sizing: border-box;
        }

        .red-button {
            background-color: #b22625;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 20px;
            font-family: Arial, sans-serif;
            cursor: pointer;
            width: 190px;
            height: 50px;
            text-align: center;
            display: inline-block;
            margin-top: 14px;
        }

        .red-button:hover {
            background-color: #8f1d1d;
        }

        .lookup-status {
            margin-top: 8px;
            font-size: 13px;
            min-height: 18px;
        }

        .lookup-status.error {
            color: #b22625;
        }

        .lookup-status.success {
            color: #008000;
        }

        .video-trigger {
            display: inline-block;
            cursor: pointer;
            text-decoration: none;
            position: relative;
        }

        .video-image {
            max-width: 100%;
            height: auto;
            display: block;
            border-radius: 4px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            position: relative;
            width: 80%;
            max-width: 800px;
            background: #000;
            aspect-ratio: 16 / 9;
        }

        .modal-content video,
        .modal-content iframe {
            width: 100%;
            height: 100%;
            display: block;
        }

        .modal-content iframe {
            border: 0;
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            color: white;
            cursor: pointer;
            z-index: 2000;
            background-color: black;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            justify-content: center;
            align-items: center;
            line-height: 0;
        }

        #greetingMessage {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            pointer-events: none;
        }

        .caption-text {
            margin-top: 10px;
            font-size: 14px;
            color: #000000;
            text-align: center;
        }

        @media (max-width: 768px) {
            .page-wrapper {
                margin: 20px auto 40px auto;
                padding: 24px 16px 32px 16px;
            }

            .headline {
                font-size: 24px;
                text-align: center;
            }

            .intro-text,
            .cta-note,
            .feel-good {
                text-align: left;
            }

            .col-text {
                padding-right: 0;
            }

            .content-row {
                gap: 24px;
            }
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", async function () {
            const modal           = document.getElementById("videoModal");
            const modalVideo      = document.getElementById("modalVideo");
            const modalYoutube    = document.getElementById("modalYoutube");
            const closeModal      = document.getElementById("closeModal");
            const trigger         = document.querySelector(".video-trigger");
            const greetingMessage = document.getElementById("greetingMessage");
            const dynamicButton   = document.getElementById("dynamic-button");
            const emailInput      = document.getElementById("email-input");
            const lookupStatus    = document.getElementById("lookup-status");

            // PHP → JS safely via json_encode
            let id    = <?php echo json_encode($id); ?>;
            let first = <?php echo json_encode($first); ?>;
            let last  = <?php echo json_encode($last); ?>;

            // Personalized video URL (per-client)
            const clientVideoUrl = `https://storage.googleapis.com/ic-port/Ukraine/clientid/${id}.mp4`;

            // Default YouTube embed URL
            const defaultYoutubeUrl = "https://www.youtube.com/embed/BnA-2MU-W_Q?rel=0&autoplay=1";

            async function checkVideoExists(url) {
                return new Promise((resolve) => {
                    const videoTest = document.createElement("video");
                    videoTest.src = url;

                    videoTest.onloadedmetadata = () => resolve(true);
                    videoTest.onerror = () => resolve(false);
                });
            }

            let videoExists = false;
            if (id) {
                videoExists = await checkVideoExists(clientVideoUrl);
            }

            // Greeting only when there is a personalized video + name
            if (videoExists && first && last && first !== "default" && last !== "default") {
                greetingMessage.textContent = `${first} ${last} wishes you a very Merry Christmas`;
            }

            function closeVideoModal() {
                modal.style.display = "none";

                if (modalVideo) {
                    modalVideo.pause();
                    modalVideo.currentTime = 0;
                    modalVideo.style.display = "none";
                    const videoSource = modalVideo.querySelector("source");
                    if (videoSource) {
                        videoSource.src = "";
                        modalVideo.load();
                    }
                }

                if (modalYoutube) {
                    modalYoutube.src = "";
                    modalYoutube.style.display = "none";
                }
            }

            if (trigger) {
                trigger.addEventListener("click", function (e) {
                    e.preventDefault();

                    if (videoExists) {
                        if (modalYoutube) {
                            modalYoutube.src = "";
                            modalYoutube.style.display = "none";
                        }
                        if (modalVideo) {
                            const videoSource = modalVideo.querySelector("source");
                            if (videoSource) {
                                videoSource.src = clientVideoUrl;
                                modalVideo.load();
                            }
                            modalVideo.style.display = "block";
                            modal.style.display = "flex";
                            modalVideo.play().catch(console.error);
                        }
                    } else {
                        if (modalVideo) {
                            modalVideo.pause();
                            modalVideo.currentTime = 0;
                            modalVideo.style.display = "none";
                        }
                        if (modalYoutube) {
                            modalYoutube.src = defaultYoutubeUrl;
                            modalYoutube.style.display = "block";
                            modal.style.display = "flex";
                        }
                    }
                });
            }

            if (closeModal) {
                closeModal.addEventListener("click", closeVideoModal);
            }

            document.addEventListener("keydown", function (e) {
                if (e.key === "Escape" && modal.style.display === "flex") {
                    closeVideoModal();
                }
            });

            // ==============
            // Button → lookup email in Manatal (via Guzzle), then redirect
            // ==============
            if (dynamicButton) {
                dynamicButton.onclick = async function () {
                    const email = (emailInput.value || "").trim().toLowerCase();

                    lookupStatus.classList.remove("error", "success");
                    lookupStatus.textContent = "";

                    if (!email) {
                        lookupStatus.textContent = "Please enter your email address.";
                        lookupStatus.classList.add("error");
                        return;
                    }

                    dynamicButton.disabled = true;
                    dynamicButton.value = "Please wait...";
                    lookupStatus.textContent = "Looking up your gift link...";
                    lookupStatus.classList.add("success");

                    try {
                        const formData = new URLSearchParams();
                        formData.append("action", "lookup_contact");
                        formData.append("email", email);

                        const response = await fetch(window.location.href, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded",
                            },
                            body: formData.toString(),
                        });

                        const data = await response.json();

                        if (data.id) {
                            const xurl = `https://www.icreatives.com/gift-catalog/?id=${encodeURIComponent(data.id)}`;

                            // Force redirect outside of iframe if needed
                            if (window.top && window.top !== window.self) {
                                window.top.location.href = xurl;
                            } else {
                                window.location.href = xurl;
                            }
                        } else {
                            lookupStatus.textContent = data.error || "We couldn’t find a gift link for that email.";
                            lookupStatus.classList.remove("success");
                            lookupStatus.classList.add("error");
                        }
                    } catch (err) {
                        console.error(err);
                        lookupStatus.textContent = "There was a problem looking up your email. Please try again.";
                        lookupStatus.classList.remove("success");
                        lookupStatus.classList.add("error");
                    } finally {
                        dynamicButton.disabled = false;
                        dynamicButton.value = "Choose A Toy";
                    }
                };
            }
        });
    </script>
</head>
<body>
<div style="padding-top:150px;"> </div>
    <div class="page-wrapper">

        <div class="col-text">

        </div>

        <div class="content-row">
            <div class="col-text">
			  <h1 class="headline">Let us donate a gift in your name.</h1>

            <p class="intro-text">
                This holiday season, join icreatives in spreading goodwill to the children of Jamaica affected by
                <strong>Hurricane Melissa</strong>.
            </p>
            <p class="intro-text">
                icreatives will fly to Jamaica to personally deliver a toy of your choice along with your thoughtful message of goodwill.
            </p>
                <p class="cta-note">
                    <span class="underline-strong">icreatives will cover all costs.</span>
                </p>
                <p class="feel-good">
                    Let us give you one more thing to feel good about this holiday season.
                </p>

                <label for="email-input" class="email-label">
                    Enter your email address to retrieve your gift link:
                </label>
                <input
                    id="email-input"
                    class="email-input"
                    type="email"
                    placeholder="your.email@company.com"
                />

                <input id="dynamic-button" class="red-button" type="button" value="Choose A Toy" />

                <div id="lookup-status" class="lookup-status"></div>
            </div>

            <div class="col-media">
                <p style="text-align: center; position: relative; display: inline-block;">
                    <a class="video-trigger">
                        <img
                            class="video-image"
                            src="https://www.icreatives.com/wp-content/uploads/2025/11/northcarolina-0.jpg"
                            alt="2024 North Carolina"
                        />
                    </a>
                    <span id="greetingMessage"></span>
                </p>

                <div id="videoModal" class="modal">
                    <div class="modal-content">
                        <span id="closeModal" class="close-button">×</span>

                        <!-- HTML5 video for personalized client clips -->
                        <video id="modalVideo" controls="controls" style="display:none;">
                            <source src="" type="video/mp4" />
                            Your browser does not support the video tag.
                        </video>

                        <!-- YouTube iframe for default video -->
                        <iframe
                            id="modalYoutube"
                            style="display:none;"
                            src=""
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                        ></iframe>
                    </div>
                </div>

                <div class="caption-text">
                    Last year, icreatives traveled to Hendersonville, North Carolina to personally deliver Christmas gifts to children affected by Hurricane Helene.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
