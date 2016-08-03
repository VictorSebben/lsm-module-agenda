<h2><?= ( $this->object->id ) ? "Editar" : "Criar" ?> Item de Agenda</h2>

<?php if ( $this->flashMsg ) : ?>
    <div class="flash <?= $this->flashMsgClass; ?>">
        <?= $this->flashMsg; ?>
    </div>
<?php endif; ?>

<div class="form-h">
    <form action="<?= ( $this->object->id ) ? $this->Url->update() : $this->Url->insert() ?>" method="post">
        <div class="form-field">
            <label for="description">Descrição</label>
            <div class="input-field">
                <input id="description" type="text" name="description" maxlength="400"
                       value="<?= $this->object->description; ?>">
            </div>
        </div>
        <div class="form-field">
            <label for="date">Data</label>
            <div class="input-field input-date">
                <input id="date" type="text" name="date" value="<?= $this->object->date; ?>">
            </div>
        </div>
        <div class="form-field">
            <label for="time">Horário</label>
            <div class="input-field input-time">
                <input id="time" type="text" name="time" value="<?= $this->object->time; ?>">
            </div>
        </div>
        <div class="form-field">
            <label for="venue">Local</label>
            <div class="input-field">
                <input id="venue" type="text" name="venue" value="<?= $this->object->venue; ?>">
            </div>
        </div>
        <div class="form-field">
            <label for="city">Cidade</label>
            <div class="input-field">
                <input id="city" type="text" name="city" value="<?= $this->object->city; ?>">
            </div>
        </div>

        <!-- Token field -->
        <input type="hidden" name="token" value="<?= \lsm\libs\H::generateToken() ?>">
        <!-- Id field -->
        <input type="hidden" name="id" value="<?= $this->object->id; ?>">

        <div class="form-field"><input type="submit" class="input-submit" name="submit" value="Enviar"></div>
    </form>
</div>
<div class="go-back">
    <a id="go-back" class="go-back" href="#">Voltar</a>
</div>
