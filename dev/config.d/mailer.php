<?php

return [
    'mailer.transport'  => env('MAILER_TRANSPORT', 'smtp://mailhog:1025'),
    'mailer.from'       => env('MAILER_FROM', 'cultura@castro.pr.gov.br'),

    // se quiser redirecionar o envio de todos os emails para um mesmo endereço
    'mailer.alwaysTo'   => false,
];