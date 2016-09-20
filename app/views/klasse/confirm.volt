<div class="page-header">
    <h1>
        Klasse löschen
    </h1>
</div>

<h5>Klasse {{ klasse.getName() }} {{ klasse.getJahrgang() }} wirklich löschen?</h5>

<ul class="pager">
    <li class="previous">{{ link_to("klasse/delete/"~klasse.getId(), "Ja ") }}</li>
    <li class="previous">{{ link_to("klasse/list", "Nein ") }}</li>
</ul>