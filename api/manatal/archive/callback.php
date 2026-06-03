<?php
    // require_once(__DIR__ . '/vendor/autoload.php');
	require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";

    use QuickBooksOnline\API\DataService\DataService;

    session_start();

    function processCode()
    {
        // Create SDK instance
       //  $config = include('config.php');
		$dataService = DataService::Configure(array(
        'auth_mode' => 'oauth2',
        'ClientID' => $config['ABOePOWPoczYACr5vdWAyZHAQGuAh9zDbaletbRuw4a8ljZLdI'],
        'ClientSecret' =>  $config['ffzORm4aU5W19onmBcKNFShLdnYuXEWcfns9i8az'],
        'RedirectURI' => $config['https://icreatives.com'],
        'scope' => $config['oauth_scope'],
        'baseUrl' => "development"
		));
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $parseUrl = parseAuthRedirectUrl($_SERVER['QUERY_STRING']);

        /*
            * Update the OAuth2Token
            */
        $accessToken =
        $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($parseUrl['code'],
        $parseUrl['realmId']);
        $dataService->updateOAuth2Token($accessToken);

        /*
            * Setting the accessToken for session variable
            */
        $_SESSION['sessionAccessToken'] = $accessToken;
    }
    function parseAuthRedirectUrl($url)
    {
        parse_str($url,$qsArray);
        return array(
            'code' => $qsArray['code'],
            'realmId' => $qsArray['realmId']
        );
    }

    $result = processCode();

?>