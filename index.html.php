<div class="console">
    <?php
    if ( $this->editContents ): ?>
    <!-- Adicionar -->
    <a href="<?= $this->Url->create(); ?>" class="input-submit btn-green">Adicionar</a>

    <!-- Excluir -->
    <button id="btn-delete" name="btn-delete" class="input-submit btn-red">Excluir</button>

    <?php
    endif;
    ?>

    <div class="search" title="Pode usar parte do nome ou email">
        <form id="users-search-form" class="search-form" action="<?= $this->Url->index(); ?>">
            <div class="form-field">
                <input placeholder="Pesquisar Categorias" title="Pode-se pesquisar por descrição"
                       id="search" type="text" name="search" value="<?= \lsm\libs\Request::getInstance()->getInput( 'search', false ); ?>">
            </div>
            <input class="input-submit" type="submit" value="Buscar">
            <a href="<?= $this->Url->make( 'agenda/index' ) ?>">Limpar pesquisa</a>
        </form>
    </div>
</div>

<h2 id="area-header">Itens de Agenda</h2>

<?php if ( isset( $this->flashMsg[ 'success' ] ) ) : ?>
    <div class="flash success-msg">
        <?= $this->flashMsg[ 'success' ]; ?>
    </div>
<?php endif; ?>
<?php if ( isset( $this->flashMsg[ 'err' ] ) ) : ?>
    <div class="flash err-msg">
        <?= $this->flashMsg[ 'err' ]; ?>
    </div>
<?php endif; ?>

<?php if ( $this->objectList != null ): ?>
<table>
    <thead>
    <tr>
        <th><input id="toggle-all" type="checkbox" name="toggle-all" title="Selecionar todos"></th>
        <th><?= $this->makeOrderByLink( 'Descrição', 'description' ); ?></th>
        <th><?= $this->makeOrderByLink( 'Data', 'description' ); ?></th>
        <th>Horário</th>
        <th>Local</th>
        <th><?= $this->makeOrderByLink( 'Cidade', 'city' ); ?></th>
        <?php if ( $this->editContents ) : ?>
            <th>Editar</th>
            <th>Remover</th>
        <?php endif; ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ( $this->objectList as $agenda ) : ?>
        <tr>
            <td><input type="checkbox" class="list-item" name="li[]" value="<?= $agenda->id ?>"></td>
            <td><?= $agenda->description; ?></td>
            <td><?= $agenda->getFormattedDate(); ?></td>
            <td><?= $agenda->getFormattedTime(); ?></td>
            <td><?= $agenda->venue; ?></td>
            <td><?= $agenda->city; ?></td>
            <?php if ( $this->editContents ) : ?>
                <td>
                    <a class="input-submit btn-edit" href="<?= $this->Url->edit( $agenda->id ); ?>">Editar</a>
                </td>
                <td>
                    <a class="input-submit btn-delete" href="<?= $this->Url->delete( $agenda->id ); ?>">Excluir</a>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php else: ?>
    <p class="msg-notice">Não há itens de agenda cadastrados.</p>
<?php endif; ?>

<!-- Token field -->
<input id="token" type="hidden" name="token" value="<?= \lsm\libs\H::generateToken() ?>">
