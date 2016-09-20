<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous">{{ link_to("klasse", "Go Back") }}</li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h1>
        Klasse anlegen
    </h1>
</div>

{{ content() }}

{{ form("klasse/create", "method":"post", "autocomplete" : "off", "class" : "form-horizontal", 'enctype': 'multipart/form-data') }}

{% for element in form %}
    {% do element.setAttribute('class', element.getAttribute('class')~' form-control') %}
    {% set hasErrors = form.hasMessagesFor(element.getName()) %}

    <div class="form-group{% if hasErrors %} has-error{% endif %}">
        <label for="{{ element.getName() }}">{{ element.getLabel() }}</label>
        {% if hasErrors %}
            <span class="help-block">
                {% for error in form.getMessagesFor(element.getName()) %}
                    {{ error }}
                {% endfor %}
            </span>
        {% endif %}
        {{ element }}
        {% if element.getName() == "name" %}
            <div class="help-tip" aria-title="Name der Klasse (zB. 9a, 10b oder 12an für naturwissenschaftlich ). Bitte kein abweichendes Format verwenden. Mehrere Klassen mit selben Namen in selben Jahrgang nicht möglich."></div>
        {% elseif element.getName() == "jahrgang" %}
                <div class="help-tip" aria-title="Jahrgang der Klasse (zB. 14/15, 15/16). Bitte kein abweichendes Format verwenden."></div>
        {% elseif element.getName() == "anzdb" %}
            <div class="help-tip" aria-title="Anzahl der Datenbanken, die für jeden Schüler erstellt werden sollen (Max. 3). Auf diese Datenbanken hat nur der Schüler selbst und der Lehrer Zugriff."></div>
        {% endif %}
    </div>
{% endfor %}

<div class="form-group">
    <label for="csvfile" class="col-sm-2 control-label left">CSV Datei der Schüler</label>
    <div class="col-sm-10">
        {{ file_field('csv', 'class': 'input-small', 'id' : 'csvfile') }}
        <div class="help-tip" aria-title="Liste der Schüler als CSV-Datei. Format: wie aus dem Inforportal herunter geladen, falls nicht vorhanden siehe Beispiel CSV-Datei in den FAQs."></div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-10">
        {{ check_field("anonym", "class" : "form-control anonym", "id" : "anonym", "value" : "ja") }}
        <label for="anonym" class="anonym">Benutzernamen anonymisieren</label>
        <div class="help-tip" aria-title="Anonymisiert die MySQL Benutzernamen der Schüler. Ergebnis 9a1516usr1, 9a1516usr2, 9a1516usr3, usw. (beispielhaft)"></div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ submit_button('Anlegen', 'class': 'btn btn-success') }}
    </div>
</div>

</form>