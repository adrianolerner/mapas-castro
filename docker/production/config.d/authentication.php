<?php
// creating base url
$prot_part = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) || env('MAPAS_HTTPS', false) ? 'https://' : 'http://';
// added @ for HTTP_HOST undefined in Tests
$host_part = @$_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
if (substr($host_part, -1) !== '/') {
    $host_part .= '/';
}
$_APP_BASE_URL = env('BASE_URL', $prot_part . $host_part);
if (substr($_APP_BASE_URL, -1) !== '/') {
    $_APP_BASE_URL .= '/';
}

return [
    'auth.provider' => '\MultipleLocalAuth\Provider',
    'auth.config' => [
        'salt' => env('AUTH_SALT', 'CHANGE_THIS_SALT_IN_PRODUCTION_MAPAS_CASTRO'),
        'timeout' => '24 hours',
        'loginOnRegister' => (bool) env('AUTH_LOGIN_ON_REGISTER', true),
        'enableLoginByCPF' => (bool) env('AUTH_ENABLE_CPF', true),
        'requireCpf' => (bool) env('AUTH_REQUIRED_CPF', true),
        'metadataFieldCPF' => 'documento',
        'userMustConfirmEmailToUseTheSystem' => (bool) env('AUTH_CONFIRM_EMAIL', false),
        'passwordMustHaveCapitalLetters' => true,
        'passwordMustHaveLowercaseLetters' => true,
        'passwordMustHaveSpecialCharacters' => true,
        'passwordMustHaveNumbers' => true,
        'minimumPasswordLength' => 6,
        'google-recaptcha-sitekey' => env('GOOGLE_RECAPTCHA_SITEKEY', null),
        'google-recaptcha-secret' => env('GOOGLE_RECAPTCHA_SECRET', null),
        'sessionTime' => (int) env('SESSION_TIMEOUT', 7200),
        'numberloginAttemp' => '5',
        'timeBlockedloginAttemp' => '900',
        'strategies' => [
            'Facebook' => [
                'app_id' => env('AUTH_FACEBOOK_APP_ID', null),
                'app_secret' => env('AUTH_FACEBOOK_APP_SECRET', null),
                'scope' => env('AUTH_FACEBOOK_SCOPE', 'email'),
            ],
            'LinkedIn' => [
                'api_key' => env('AUTH_LINKEDIN_API_KEY', null),
                'secret_key' => env('AUTH_LINKEDIN_SECRET_KEY', null),
                'redirect_uri' => $_APP_BASE_URL . 'autenticacao/linkedin/oauth2callback',
                'scope' => env('AUTH_LINKEDIN_SCOPE', 'r_emailaddress'),
            ],
            'Google' => [
                'client_id' => env('AUTH_GOOGLE_CLIENT_ID', null),
                'client_secret' => env('AUTH_GOOGLE_CLIENT_SECRET', null),
                'redirect_uri' => $_APP_BASE_URL . 'autenticacao/google/oauth2callback',
                'scope' => env('AUTH_GOOGLE_SCOPE', 'email'),
            ],
            'Twitter' => [
                'app_id' => env('AUTH_TWITTER_APP_ID', null),
                'app_secret' => env('AUTH_TWITTER_APP_SECRET', null),
            ],
            'GovBr' => [
                'visible' => (bool) env('AUTH_GOV_BR_VISIBLE', false),
                'response_type' => env('AUTH_GOV_BR_RESPONSE_TYPE', 'code'),
                'client_id' => env('AUTH_GOV_BR_CLIENT_ID', null),
                'client_secret' => env('AUTH_GOV_BR_SECRET', null),
                'redirect_uri' => env('AUTH_GOV_BR_REDIRECT_URI', $_APP_BASE_URL . 'autenticacao/govbr/callback'),
                'auth_endpoint' => env('AUTH_GOV_BR_ENDPOINT', 'https://sso.staging.acesso.gov.br/authorize'),
                'token_endpoint' => env('AUTH_GOV_BR_TOKEN_ENDPOINT', 'https://sso.staging.acesso.gov.br/token'),
                'scope' => env('AUTH_GOV_BR_SCOPE', 'openid email phone profile govbr_confiabilidades'),
                'jwk_path' => env('AUTH_GOV_BR_JWK_PATH', 'https://sso.staging.acesso.gov.br/jwk'),
                'cpf_API_endpoint' => env('AUTH_GOV_BR_CPF_ENDPOINT', 'https://api.staging.acesso.gov.br/'),
                'url_logout' => env('AUTH_GOV_BR_URL_LOGOUT', 'https://sso.staging.acesso.gov.br/logout'),
                'post_logout_redirect_uri' => env('AUTH_GOV_BR_POST_LOGOUT_REDIRECT_URI', $_APP_BASE_URL),
                'applySealId' => env('AUTH_GOV_BR_SEAL_ID', null),
                'dic_agent_fields_update' => json_decode(env('AUTH_GOV_BR_DICT_AGENT_FIELDS_UPDATE', '{}'), true),
            ],
        ],
    ],
];
