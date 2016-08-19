<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous">{{ link_to("klasse", "Go Back") }}</li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h1>
        Edit klasse
    </h1>
</div>

{{ content() }}

{{ form("klasse/save", "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}

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
    <label for="fieldRndName" class="col-sm-2 control-label">Rnd Of Name</label>
    <div class="col-sm-10">
        {{ text_field("rnd_name", "size" : 30, "class" : "form-control", "id" : "fieldRndName") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldListeSchueler" class="col-sm-2 control-label">Liste Of Schueler</label>
    <div class="col-sm-10">
        {{ text_field("liste_schueler", "size" : 30, "class" : "form-control", "id" : "fieldListeSchueler") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldListeSchuelerAno" class="col-sm-2 control-label">Liste Of Schueler Of Ano</label>
    <div class="col-sm-10">
        {{ text_field("liste_schueler_ano", "size" : 30, "class" : "form-control", "id" : "fieldListeSchuelerAno") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldListeLehrer" class="col-sm-2 control-label">Liste Of Lehrer</label>
    <div class="col-sm-10">
        {{ text_field("liste_lehrer", "size" : 30, "class" : "form-control", "id" : "fieldListeLehrer") }}
    </div>
</div>

<div class="form-group">
    <label for="fieldListeLehrerAno" class="col-sm-2 control-label">Liste Of Lehrer Of Ano</label>
    <div class="col-sm-10">
        {{ text_field("liste_lehrer_ano", "size" : 30, "class" : "form-control", "id" : "fieldListeLehrerAno") }}
    </div>
</div>


{{ hidden_field("id") }}

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ submit_button('Send', 'class': 'btn btn-default') }}
    </div>
</div>

</form>
