<div class="page-header">
    <h1>
        Impressum anpassen
    </h1>
</div>

{{ content() }}

{{ form("impressum/edit", "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}

<div class="form-group">
    <label for="fieldName" class="col-sm-2 control-label">Name</label>
    <div class="col-sm-10">
        {{ text_field("name", "size" : 30, "class" : "form-control", "id" : "fieldName") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldStrassenr" class="col-sm-2 control-label">Strasse, Hausnummer</label>
    <div class="col-sm-10">
        {{ text_field("strassenr", "size" : 30, "class" : "form-control", "id" : "fieldStrassenr") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldOrt" class="col-sm-2 control-label">Postleitzahl, Ort</label>
    <div class="col-sm-10">
        {{ text_field("ort", "size" : 2, "class" : "form-control", "id" : "fieldOrt") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldTelefon" class="col-sm-2 control-label">Telefonnummer</label>
    <div class="col-sm-10">
        {{ text_field("telefon", "size" : 2, "class" : "form-control", "id" : "fieldTelefon") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldEmail" class="col-sm-2 control-label">E-Mail</label>
    <div class="col-sm-10">
        {{ text_field("mail", "size" : 2, "class" : "form-control", "id" : "fieldEmail") }}
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ submit_button('Speichern', 'class': 'btn btn-default') }}
    </div>
</div>

</form>
