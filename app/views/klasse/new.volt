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
    </div>
{% endfor %}

<div class="form-group">
    <label for="csvfile" class="col-sm-2 control-label left">CSV Datei der Schüler</label>
    <div class="col-sm-10">
        {{ file_field('csv', 'class': 'input-small', 'id' : 'csvfile') }}
    </div>
</div>

<div class="form-group">
    <div class="col-sm-10">
        {{ check_field("anonym", "class" : "form-control anonym", "id" : "anonym", "value" : "ja") }}
        <label for="anonym" class="anonym">Benutzernamen anonymisieren</label>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ submit_button('Anlegen', 'class': 'btn btn-success') }}
    </div>
</div>

</form>