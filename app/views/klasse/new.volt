<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous">{{ link_to("klasse", "Go Back") }}</li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h1>
        Create klasse
    </h1>
</div>

{{ content() }}

{{ form("klasse/create", "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}

<div class="form-group">
    <label for="fieldName" class="col-sm-2 control-label">Name</label>
    <div class="col-sm-10">
        {{ text_field("name", "size" : 30, "class" : "form-control", "id" : "fieldName") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldJahrgang" class="col-sm-2 control-label">Jahrgang</label>
    <div class="col-sm-10">
        {{ text_field("jahrgang", "size" : 30, "class" : "form-control", "id" : "fieldJahrgang") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldAnzUsr" class="col-sm-2 control-label">Anzahl Benutzer</label>
    <div class="col-sm-10">
        {{ text_field("anz_usr", "size" : 2, "class" : "form-control", "id" : "fieldAnzUsr") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldAnzDb" class="col-sm-2 control-label">Anzahl Dbs pro Benutzer</label>
    <div class="col-sm-10">
        {{ text_field("anz_db", "size" : 2, "class" : "form-control", "id" : "fieldAnzDb") }}
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ submit_button('Save', 'class': 'btn btn-default') }}
    </div>
</div>

</form>
