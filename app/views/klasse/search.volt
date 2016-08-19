<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous">{{ link_to("klasse/index", "Go Back") }}</li>
            <li class="next">{{ link_to("klasse/new", "Create ") }}</li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h1>Search result</h1>
</div>

{{ content() }}

<div class="row">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Id</th>
            <th>Name</th>
            <th>Jahrgang</th>
            <th>Rnd Of Name</th>
            <th>Liste Of Schueler</th>
            <th>Liste Of Schueler Of Ano</th>
            <th>Liste Of Lehrer</th>
            <th>Liste Of Lehrer Of Ano</th>

                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        {% if page.items is defined %}
        {% for klasse in page.items %}
            <tr>
                <td>{{ klasse.getId() }}</td>
            <td>{{ klasse.getName() }}</td>
            <td>{{ klasse.getJahrgang() }}</td>
            <td>{{ klasse.getRndName() }}</td>
            <td>{{ klasse.getListeSchueler() }}</td>
            <td>{{ klasse.getListeSchuelerAno() }}</td>
            <td>{{ klasse.getListeLehrer() }}</td>
            <td>{{ klasse.getListeLehrerAno() }}</td>

                <td>{{ link_to("klasse/edit/"~klasse.getId(), "Edit") }}</td>
                <td>{{ link_to("klasse/delete/"~klasse.getId(), "Delete") }}</td>
            </tr>
        {% endfor %}
        {% endif %}
        </tbody>
    </table>
</div>

<div class="row">
    <div class="col-sm-1">
        <p class="pagination" style="line-height: 1.42857;padding: 6px 12px;">
            {{ page.current~"/"~page.total_pages }}
        </p>
    </div>
    <div class="col-sm-11">
        <nav>
            <ul class="pagination">
                <li>{{ link_to("klasse/search", "First") }}</li>
                <li>{{ link_to("klasse/search?page="~page.before, "Previous") }}</li>
                <li>{{ link_to("klasse/search?page="~page.next, "Next") }}</li>
                <li>{{ link_to("klasse/search?page="~page.last, "Last") }}</li>
            </ul>
        </nav>
    </div>
</div>
