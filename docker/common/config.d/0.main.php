<?php

return [
    'app.siteName' => env('SITE_NAME', 'Mapa Cultural de Castro'),
    'app.siteDescription' => env(
        'SITE_DESCRIPTION',
        'O Mapa Cultural de Castro é a plataforma colaborativa da Prefeitura Municipal de Castro (PR) que reúne informações sobre agentes, espaços, eventos, projetos culturais e oportunidades.'
    ),

    // Define o tema ativo no site principal. Deve ser informado o namespace do tema e neste deve existir uma classe Theme.
    'themes.active' => env('ACTIVE_THEME', 'MapasCulturais\Themes\BaseV2'),

    // Logo e textos configuráveis do tema BaseV2
    'logo.image' => env('LOGO_IMAGE', 'img/logo.png'),
    'logo.hideLabel' => (bool) env('LOGO_HIDELABEL', false),
    'logo.title' => env('LOGO_TITLE', 'Mapa Cultural'),
    'logo.subtitle' => env('LOGO_SUBTITLE', 'Castro - PR'),

    // Textos de boas-vindas da Home (componente home-header)
    'text:home-header.title' => env('HOME_HEADER_TITLE', 'Boas vindas ao Mapa Cultural de Castro - PR!'),
    'text:home-header.description' => env(
        'HOME_HEADER_DESCRIPTION',
        'A PLATAFORMA DE MAPAS CULTURAIS DE CASTRO - PR é um instrumento para a promoção da política cultural de Castro - PR, é uma plataforma colaborativa de gestão pública, permitindo aos gestores, agentes culturais e a todos os cidadãos conhecer, compartilhar e participar da produção e ações que integram a política cultural de Castro - PR. Participe, Navegue, se informe, contribua e interaja com a gente!'
    ),

    // Imagens dos itens e fundos da Home
    'homeHeader.background' => env('HOME_HEADER_BACKGROUND', 'img/home/fundo2.jpg'),
    'module.home' => [
        'home-header' => env('HOME_HEADER_BACKGROUND', 'img/home/fundo2.jpg'),
        'home-agents' => env('HOME_AGENTS_IMAGE', 'img/home/agentes.jpg'),
        'home-events' => env('HOME_EVENTS_IMAGE', 'img/home/eventos.jpg'),
        'home-opportunities' => env('HOME_OPPORTUNITIES_IMAGE', 'img/home/oportunidades.jpg'),
        'home-projects' => env('HOME_PROJECTS_IMAGE', 'img/home/projetos.jpg'),
        'home-spaces' => env('HOME_SPACES_IMAGE', 'img/home/espacos.jpg'),
        'home-register' => env('HOME_REGISTER_BACKGROUND', 'img/home/fundo3.jpg'),
    ],

    // Ids dos selos verificadores. Para utilizar múltiplos selos informe os ids separados por vírgula.
    'app.verifiedSealsIds' => env('VERIFIED_SEALS', '1'),

    // Idioma e país padrão
    'app.lcode' => env('APP_LCODE', 'pt_BR'),
    'app.defaultCountry' => env('APP_DEFAULT_COUNTRY', 'BR'),

    // Recria o cache de permissões (pcache) imediatamente ao criar/editar entidades
    'app.recreateCacheImmediately' => (bool) env('APP_RECREATE_CACHE_IMMEDIATELY', true),

    // Filtros dos carrosséis da Home (por padrão exibe todas as entidades publicadas, sem exigir selo verificador manual)
    'home.opportunities.filter' => json_decode(env('HOME_OPPORTUNITIES_FILTER_JSON', '{}')),
    'home.featured.filter' => json_decode(env('HOME_FEATURED_FILTER_JSON', '{}')),
];
