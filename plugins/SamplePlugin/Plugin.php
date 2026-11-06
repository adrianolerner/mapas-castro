<?php
namespace SamplePlugin;

use MapasCulturais\App;

class Plugin extends \MapasCulturais\Plugin
{
    public function _init()
    {
        $app = App::i();

        // Registra a pasta do plugin imediatamente para resolução de assets (logo e imagens da Home)
        $app->view->addPath(__DIR__ . '/');

        // Em ambiente de desenvolvimento, mantém a rota /autenticacao/fake acessível mesmo usando MultipleLocalAuth
        if ($app->config['app.mode'] === 'development') {
            $app->hook('GET(auth.fake)', function () use ($app) {
                $searchQuery = trim($app->request->get('q', ''));
                if ($searchQuery !== '') {
                    $searchQuery = '%' . $searchQuery . '%';
                }

                $users = $app->em->getConnection()->fetchAll("
                    SELECT
                        u.id,
                        u.email,
                        a.name AS profile,
                        r.name AS role_name
                    FROM
                        usr u
                        LEFT JOIN agent a ON a.id = u.profile_id
                        LEFT JOIN role r ON r.usr_id = u.id
                    WHERE (? = '' OR u.email ILIKE ? OR a.name ILIKE ? OR r.name ILIKE ?)
                    ORDER BY profile, email, role_name
                    LIMIT 10", [
                    $searchQuery, $searchQuery, $searchQuery, $searchQuery
                ]);

                if ($app->request->isAjax()) {
                    $this->json($users);
                } else {
                    $this->render('fake-authentication', [
                        'users' => $users,
                        'form_action' => $app->createUrl('auth', 'fakeLogin'),
                        'new_user_form_action' => $app->createUrl('user')
                    ]);
                }
            });

            $app->hook('GET(auth.fakeLogin)', function () use ($app) {
                if (key_exists('fake_authentication_user_id', $_GET)) {
                    $user_id = (int) $_GET['fake_authentication_user_id'];
                    $_SESSION['auth.fakeAuthenticationUserId'] = $user_id;
                    $_SESSION['multipleLocalUserId'] = $user_id;
                    $user = $app->repo("User")->find($user_id);
                    if ($user && method_exists($app->auth, 'authenticateUser')) {
                        $app->auth->authenticateUser($user);
                    }
                    $app->applyHook('auth.successful');
                }
                if ($app->auth->isUserAuthenticated()) {
                    $is_ajax = $this->data["isAjax"] ?? $app->request->isAjax();
                    if ($is_ajax) {
                        $this->json($app->user);
                        return;
                    }
                    $url = $app->auth->getRedirectPath();
                    $app->redirect($url);
                } else {
                    $app->redirect($this->createUrl(''));
                }
            });
        }

        // Evita que o fetch da classe API use 'force-cache' no navegador e exiba listas desatualizadas após criar/editar entidades
        // e aplica ajustes visuais da logo e dos fundos/cards da Home de Castro
        $app->hook('template(<<*>>.head):begin', function () use ($app) {
            $fundo1Url = $app->view->asset('img/home/fundo1.jpg', false);
            $fundo4Url = $app->view->asset('img/home/fundo4.jpg', false);

            echo "<script>
                globalThis.addEventListener('beforeFetch', function (e) {
                    if (e.detail && e.detail.config && e.detail.config.cache === 'force-cache') {
                        e.detail.config.cache = 'no-cache';
                    }
                });
            </script>
            <style>
                .theme-logo {
                    grid-template-columns: auto auto !important;
                }
                .theme-logo__logo--img img {
                    max-height: 48px !important;
                    width: auto !important;
                    object-fit: contain;
                }
                /* Ajuste do banner de boas-vindas (home-header) */
                .home-header__background .img > img {
                    width: 100% !important;
                    height: 100% !important;
                    object-fit: cover !important;
                    object-position: center !important;
                }
                .home-header__content::before {
                    background: linear-gradient(90deg, rgba(0, 0, 0, 0.75) 0%, rgba(0, 0, 0, 0.45) 60%, rgba(0, 0, 0, 0.20) 100%) !important;
                }
                .home-header__main {
                    max-width: 760px !important;
                }
                .home-header__title {
                    max-width: 760px !important;
                    text-shadow: 0 2px 6px rgba(0, 0, 0, 0.65);
                }
                .home-header__description {
                    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.75);
                    font-size: 1.1rem !important;
                    line-height: 1.55 !important;
                }
                /* Ajuste das imagens dos cards de entidades na Home */
                .home-entities .card__left--img img {
                    width: 100% !important;
                    height: 100% !important;
                    object-fit: cover !important;
                    object-position: center !important;
                }
                /* Fundos adicionais personalizados nas seções da Home */
                .home-entities {
                    background-image: linear-gradient(rgba(245, 245, 245, 0.88), rgba(245, 245, 245, 0.92)), url('{$fundo1Url}') !important;
                    background-size: cover !important;
                    background-position: center !important;
                    background-attachment: fixed;
                }
                .home-developers {
                    background-image: linear-gradient(rgba(255, 238, 228, 0.88), rgba(255, 238, 228, 0.92)), url('{$fundo4Url}') !important;
                    background-size: cover !important;
                    background-position: center !important;
                }
            </style>";
        });
    }

    public function register()
    {
        // register metadata, taxonomies

    }
}
