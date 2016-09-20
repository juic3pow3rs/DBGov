<div align="right">
    {{ link_to("klasse/new", "<i class='icon-plus-sign'></i> Klasse erstellen", "class": "btn btn-primary") }}
</div>

<div class="page-header">
    <h1>Klassenliste</h1>
</div>

{{ content() }}

{% if cnt == 1 %}

<div class="row">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Jahrgang</th>
                <th>PDF Zugangsdaten Schüler</th>
                <th>PDF Zugansdaten Lehrer & Schüler</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        {% if page.items is defined %}
        {% for klasse in page.items %}
            <tr>
                <td>{{ klasse.getName() }}</td>
                <td>{{ klasse.getJahrgang() }}</td>
                <td><a href="/klasse/download/{{ klasse.getListeSchueler() }}">Download</a></td>
                <td><a href="/klasse/download/{{ klasse.getListeLehrer() }}">Download</a></td>
                <td>{{ link_to("klasse/confirm/"~klasse.getId(), "Löschen") }}</td>
            </tr>
        {% endfor %}
        {% endif %}
        </tbody>
    </table>
</div>

<div class="row">
    <div class="col-sm-1">
        <p class="pagination" style="line-height: 1.42857;padding: 6px 12px;">
            Seite {{ page.current~"/"~page.total_pages }}
        </p>
    </div>
    <div class="col-sm-11">
        <nav>
            <ul class="pagination">
                <li>{{ link_to("klasse/search", "Erste") }}</li>
                <li>{{ link_to("klasse/search?page="~page.before, "Vorherige") }}</li>
                <li>{{ link_to("klasse/search?page="~page.next, "Nächste") }}</li>
                <li>{{ link_to("klasse/search?page="~page.last, "Letzte") }}</li>
            </ul>
        </nav>
    </div>
</div>

{% endif %}