<?php
// Formulario separado para crear una actividad
?>
<form class="form-horizontal" method="POST" action="" id="SubirActividad">

    <input type="hidden" name="form_action" value="create" />

    <div class="form-group">
        <label for="type" class="col-sm-2 control-label">Tipo</label>
        <div class="col-sm-10">
            <select id="type" class="form-control" name="type">
                <option value="">-- Seleccionar --</option>
                <option value="spinning">Spinning</option>
                <option value="bodypump">BodyPump</option>
                <option value="pilates">Pilates</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="monitor" class="col-sm-2 control-label">Monitor</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="monitor" id="monitor" placeholder="" value="">
        </div>
    </div>
    <div class="form-group">
        <label for="place" class="col-sm-2 control-label">Lugar</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="place" id="place" placeholder="" value="">
        </div>
    </div>
    <div class="form-group">
        <label for="date" class="col-sm-2 control-label">Fecha</label>
        <div class="col-sm-10">
            <input type="datetime-local" class="form-control" name="date" id="date" placeholder="" value="">
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">Insert</button>
        </div>
    </div>
</form>
