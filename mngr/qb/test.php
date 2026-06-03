<?php
/*
Get client keys:
Obtain OAuth 2.0 client keys from your app's dashboard on developer.intuit.com. 
To locate the app's dashboard, sign in to developer.intuit.com and click My Apps. 
Find and open the app you want. From here, click the Keys tab. There are two versions of this key:
Development keys—use only in the sandbox environment.
Production keys—use only in the production environment.
App ID: 272226b2-f100-42fc-acfe-47df1a2eb030

Sandbox Company_US_1
Company ID: 4620816365273115330

Get OAuth 2.0 token from auth code
Authorization Code: AB11692136416tP9ZpNdsakD5E6cfzYBDqkWOgdpK6xI5wz4vq
Real ID: 4620816365273115330

Status: 200
Connection: keep-alive
Keep-Alive: timeout=5
Strict-Transport-Security: max-age=15552000
Cache-Control: no-cache, no-store
Content-Type: application/json;charset=utf-8
Server: nginx
{
 "refreshToken": "AB11700862657J6skVtCrwO8csyF3pAsPMyrcFKCueqmou
   6XFu",
 "accessToken": "eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0
   ..6XX0hpcQHlae26JhpuXtuw
   .sRbEq6TLyWCiZcXB1yMjfloL2fSncMw3IGjqk0MwTpiZYd
   -SjVKMBVRfZGyZPtdFiFWgZsjdsl9T5VgirSkyoZQBTtzcjZHYln4A_BcXtk1
   2f90qulgY2V6-U4IfjD2X-SyD04cEotxucVfafSPizgT9od
   -HfXxzUSw6cUXZSUF3dYDlB8g9bt3CdwjcYEg0ivDUuxFckgoFyQ-2S
   -oD0xXSWLunujUlugbakW3QRSh5F2_bGdaud8aYnVUcnX
   -93Us7Frc5HtFzYJmmX9p6zNDQ-lHySpLXVLQ8EZ
   -8LVJtxG9uS4TuyGBTSwcXfp3Yn8mo4LxefbpziGfwoRtWN_34ZN0_f_NQ0Cq
   _Vim2uykc6qINqwnG4jRBBH75BI_CHvZfue-x8_NGzLYla1
   -Ph6tHS56_TsynMCyZhAfmzfs9SUoxnADhU4C3h4QTSC0o10esxB6WZFnE4mO
   XUMelViv4KEIarVfoZlWiL_zxnNHfkHxhbYwfse8qMTBUAGj6zqWGMJcIMAzz
   qX7Zumx_iwnn8fFJZ-1U7Mqm8M9aXxrj-NuEmNCp0vE-yDk69f-Gzb5jtz4F
   -tKSX0iBg5SJVbrsSxw8ms1EfHUdcVafXYhcieVsqAnAnEad8gVgntDPjgIBm
   awf5a3z1JcRiKa0w4Kv4ovrKIWhJGROurmhGfQ6CjSdK4m7nz_Wz3VoYqHbTI
   fw6NWV7dHoPbUfRZWApQ4oMforpPxxysSI1HRcK5xB-Dfs2VYNYZz3bMNy
   -cBx5P7uWzWcOA0K5BwOMHCpvYzRgpVEK5BH74z50zl11pDaqzPVE1pnH44cr
   cm2C6ZlZBZ1KAsoFjuOEEqcRtCxhioGBSSXoifZw63h1JpS6QirH2TQwQDhwH
   P77g7_mNO1.uBJsdsNxyK_um_xICxLplg",
 "expires_in": 3600,
 "x_refresh_token_expires_in": 8726400,
 "idToken": "eyJraWQiOiJPUElDUFJEMDkxODIwMTQiLCJhbGciOiJSUzI1NiJ
   9.eyJzdWIiOiI1OWQ4MDVlOS0yMzRiLTQyMzAtYTMwNi05ZTU4NTgyNGQ4MDk
   iLCJhdWQiOlsiQUJPZVBPV1BvY3pZQUNyNXZkV0F5WkhBUUd1QWg5ekRiYWxl
   dGJSdXc0YThsalpMZEkiXSwicmVhbG1pZCI6IjQ2MjA4MTYzNjUyNzMxMTUzM
   zAiLCJhdXRoX3RpbWUiOjE2OTIxMzQyODAsImlzcyI6Imh0dHBzOlwvXC9vYX
   V0aC5wbGF0Zm9ybS5pbnR1aXQuY29tXC9vcFwvdjEiLCJleHAiOjE2OTIxMzk
   4NTcsImlhdCI6MTY5MjEzNjI1N30.V617xf6nS
   -CNA7mLUfS5K_beheiQlFkSGxmuvxEpv9q93KvT0epSlaG3i9kaPj3RboL2kC
   PDAsPrT59kwdVf3M1A4K6HbtqVVj6HtQokxBLDSoJXzF6rJ_VG5iiJcNzYN
   -LuPBsEUl2Czms-xiUY3cQaQjHZntTO_FiaTTP7lAI"
}

Refresh Token:
AB11700862657J6skVtCrwO8csyF3pAsPMyrcFKCueqmou6XFu
Access token
eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..6XX0hpcQHlae26JhpuXtuw.sRbEq6TLyWCiZcXB1yMjfloL2fSncMw3IGjqk0MwTpiZYd-SjVKMBVRfZGyZPtdFiFWgZsjdsl9T5VgirSkyoZQBTtzcjZHYln4A_BcXtk12f90qulgY2V6-U4IfjD2X-SyD04cEotxucVfafSPizgT9od-HfXxzUSw6cUXZSUF3dYDlB8g9bt3CdwjcYEg0ivDUuxFckgoFyQ-2S-oD0xXSWLunujUlugbakW3QRSh5F2_bGdaud8aYnVUcnX-93Us7Frc5HtFzYJmmX9p6zNDQ-lHySpLXVLQ8EZ-8LVJtxG9uS4TuyGBTSwcXfp3Yn8mo4LxefbpziGfwoRtWN_34ZN0_f_NQ0Cq_Vim2uykc6qINqwnG4jRBBH75BI_CHvZfue-x8_NGzLYla1-Ph6tHS56_TsynMCyZhAfmzfs9SUoxnADhU4C3h4QTSC0o10esxB6WZFnE4mOXUMelViv4KEIarVfoZlWiL_zxnNHfkHxhbYwfse8qMTBUAGj6zqWGMJcIMAzzqX7Zumx_iwnn8fFJZ-1U7Mqm8M9aXxrj-NuEmNCp0vE-yDk69f-Gzb5jtz4F-tKSX0iBg5SJVbrsSxw8ms1EfHUdcVafXYhcieVsqAnAnEad8gVgntDPjgIBmawf5a3z1JcRiKa0w4Kv4ovrKIWhJGROurmhGfQ6CjSdK4m7nz_Wz3VoYqHbTIfw6NWV7dHoPbUfRZWApQ4oMforpPxxysSI1HRcK5xB-Dfs2VYNYZz3bMNy-cBx5P7uWzWcOA0K5BwOMHCpvYzRgpVEK5BH74z50zl11pDaqzPVE1pnH44crcm2C6ZlZBZ1KAsoFjuOEEqcRtCxhioGBSSXoifZw63h1JpS6QirH2TQwQDhwHP77g7_mNO1.uBJsdsNxyK_um_xICxLplg


CLient ID: ABOePOWPoczYACr5vdWAyZHAQGuAh9zDbaletbRuw4a8ljZLdI

Client Secret: ffzORm4aU5W19onmBcKNFShLdnYuXEWcfns9i8az

redir: https://developer.intuit.com/v2/OAuth2Playground/RedirectUrl

*/

require_once(__DIR__ . '/vendor/autoload.php');
require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";

use QuickBooksOnline\API\DataService\DataService;

session_start();

function refreshToken()
{

    // Create SDK instance
    $config = include('config.php');
     /*
     * Retrieve the accessToken value from session variable
     */
    $accessToken = $_SESSION['sessionAccessTokeneyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..6XX0hpcQHlae26JhpuXtuw.sRbEq6TLyWCiZcXB1yMjfloL2fSncMw3IGjqk0MwTpiZYd-SjVKMBVRfZGyZPtdFiFWgZsjdsl9T5VgirSkyoZQBTtzcjZHYln4A_BcXtk12f90qulgY2V6-U4IfjD2X-SyD04cEotxucVfafSPizgT9od-HfXxzUSw6cUXZSUF3dYDlB8g9bt3CdwjcYEg0ivDUuxFckgoFyQ-2S-oD0xXSWLunujUlugbakW3QRSh5F2_bGdaud8aYnVUcnX-93Us7Frc5HtFzYJmmX9p6zNDQ-lHySpLXVLQ8EZ-8LVJtxG9uS4TuyGBTSwcXfp3Yn8mo4LxefbpziGfwoRtWN_34ZN0_f_NQ0Cq_Vim2uykc6qINqwnG4jRBBH75BI_CHvZfue-x8_NGzLYla1-Ph6tHS56_TsynMCyZhAfmzfs9SUoxnADhU4C3h4QTSC0o10esxB6WZFnE4mOXUMelViv4KEIarVfoZlWiL_zxnNHfkHxhbYwfse8qMTBUAGj6zqWGMJcIMAzzqX7Zumx_iwnn8fFJZ-1U7Mqm8M9aXxrj-NuEmNCp0vE-yDk69f-Gzb5jtz4F-tKSX0iBg5SJVbrsSxw8ms1EfHUdcVafXYhcieVsqAnAnEad8gVgntDPjgIBmawf5a3z1JcRiKa0w4Kv4ovrKIWhJGROurmhGfQ6CjSdK4m7nz_Wz3VoYqHbTIfw6NWV7dHoPbUfRZWApQ4oMforpPxxysSI1HRcK5xB-Dfs2VYNYZz3bMNy-cBx5P7uWzWcOA0K5BwOMHCpvYzRgpVEK5BH74z50zl11pDaqzPVE1pnH44crcm2C6ZlZBZ1KAsoFjuOEEqcRtCxhioGBSSXoifZw63h1JpS6QirH2TQwQDhwHP77g7_mNO1.uBJsdsNxyK_um_xICxLplg'];
    $dataService = DataService::Configure(array(
        'auth_mode' => 'oauth2',
        'ClientID' => $config['4620816365273115330'],
        'ClientSecret' =>  $config['client_secret'],
        'RedirectURI' => $config['oauth_redirect_uri'],
        'baseUrl' => "development",
        'refreshTokenKey' => $accessToken->getRefreshToken(),
        'QBORealmID' => "The Company ID which the app wants to access",
    ));

    /*
     * Update the OAuth2Token of the dataService object
     */
    $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
    $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshToken();
    $dataService->updateOAuth2Token($refreshedAccessTokenObj);

    $_SESSION['sessionAccessToken'] = $refreshedAccessTokenObj;

    print_r($refreshedAccessTokenObj);
    return $refreshedAccessTokenObj;
}

$result = refreshToken();

?>