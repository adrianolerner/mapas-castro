<?php

// Utiliza o provedor real MultipleLocalAuth (habilita /autenticacao/ e /autenticacao/register/ para cadastro público).
// Em ambiente dev, o login rápido (Fake) também permanece acessível em /autenticacao/fake
return [
    'auth.provider' => env('DEV_AUTH_PROVIDER', '\MultipleLocalAuth\Provider'),
    'auth.config' => [
        'salt' => env('AUTH_SALT', 'DEV_SALT_MAPAS_CASTRO'),
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
    ],
];
