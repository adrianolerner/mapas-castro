<?php
/**
 * @var \MapasCulturais\Themes\BaseV2\Theme $this
 * @var \MapasCulturais\App $app
 * 
 */

use MapasCulturais\i;

$this->import('
    mc-card
');
?>

<div class="confirm-email">
    <mc-card class="no-title">
        <template #content>
            <div class="grid-12">
                <?php if (!empty($msg)): ?>
                    <div class="col-12 header">
                        <label class="header__title"> <?= i::__('Validação de conta') ?> </label>
                        <mc-icon name="circle-x" class="header__icon"
                            style="color: var(--mc-error-color, #d50200);"></mc-icon>
                        <label class="header__label"> <?= htmlspecialchars($msg) ?> </label>
                    </div>
                <?php else: ?>
                    <div class="col-12 header">
                        <label class="header__title"> <?= i::__('E-mail confirmado com sucesso!') ?> </label>
                        <mc-icon name="circle-checked" class="header__icon"></mc-icon>
                        <label class="header__label">
                            <?= i::__('Sua conta foi validada e ativada. Agora você já pode acessar a plataforma.') ?>
                        </label>
                    </div>
                <?php endif; ?>

                <a class="col-12 button button--primary button--large button--md" href="<?= $app->createUrl('auth') ?>"
                    type="submit"> <?= i::__('Entrar na minha conta') ?> </a>
            </div>
        </template>
    </mc-card>
</div>