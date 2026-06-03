<?php
/*
return array(
    'authorizationRequestUrl' => 'https://appcenter.intuit.com/connect/oauth2',
    'tokenEndPointUrl' => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
    'client_id' => 'ABOePOWPoczYACr5vdWAyZHAQGuAh9zDbaletbRuw4a8ljZLdI',
    'client_secret' => 'ffzORm4aU5W19onmBcKNFShLdnYuXEWcfns9i8az',
    'oauth_scope' => 'com.intuit.quickbooks.accounting openid profile email phone address',
    'oauth_redirect_uri' => 'http://localhost:3000/callback.php',
)
*/
    // require_once(__DIR__ . '/vendor/autoload.php');
	require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
    use QuickBooksOnline\API\DataService\DataService;

    $config = include('config.php');

    session_start();

    $dataService = DataService::Configure(array(
        'auth_mode' => 'oauth2',
        'ClientID' => $config['ABOePOWPoczYACr5vdWAyZHAQGuAh9zDbaletbRuw4a8ljZLdI'],
        'ClientSecret' =>  $config['ffzORm4aU5W19onmBcKNFShLdnYuXEWcfns9i8az'],
        'RedirectURI' => $config['https://icreatives.com'],
        'scope' => $config['oauth_scope'],
        'baseUrl' => "development"
    ));

    $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
    $authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();

    // Store the url in PHP Session Object;
    $_SESSION['authUrl'] = $authUrl;

    //set the access token using the auth object
    if (isset($_SESSION['sessionAccessToken'])) {

        $accessToken = $_SESSION['sessionAccessToken'];
        $accessTokenJson = array('token_type' => 'bearer',
            'access_token' => $accessToken->getAccessToken(),
            'refresh_token' => $accessToken->getRefreshToken(),
            'x_refresh_token_expires_in' =>
    $accessToken->getRefreshTokenExpiresAt(),
            'expires_in' => $accessToken->getAccessTokenExpiresAt()
        );
        $dataService->updateOAuth2Token($accessToken);
        $oauthLoginHelper = $dataService -> getOAuth2LoginHelper();
        $CompanyInfo = $dataService->getCompanyInfo();
    }
?>
