<div class="navbar">
  <div class="navbar-inner">
    <div class="container" style="width: auto;">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      {{ link_to(null, 'class': 'brand', 'DBGov')}}
        <div class="nav-collapse">
          <ul class="nav">

            {% if dispatcher.getControllerName() == 'index' %}
                <li class="active">{{ link_to('index', 'Home') }}</li>
            {% else %}
                <li>{{ link_to('index', 'Home') }}</li>
            {% endif %}

            {% if dispatcher.getControllerName() == 'klasse' %}
                <li class="active dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Klasse <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>{{ link_to('klasse/new', 'Klasse erstellen') }}</li>
                        <li>{{ link_to('klasse/list', 'Klassenliste') }}</li>
                    </ul>
                </li>
            {% else %}
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Klasse <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>{{ link_to('klasse/new', 'Klasse erstellen') }}</li>
                        <li>{{ link_to('klasse/list', 'Klassenliste') }}</li>
                    </ul>
                </li>
            {% endif %}

            {% if dispatcher.getControllerName() == 'about' %}
                <li class="active dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">About <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>{{ link_to('about/index', 'Über DBGov') }}</li>
                        <li>{{ link_to('about/faq', 'FAQ') }}</li>
                    </ul>
                </li>
            {% else %}
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">About <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>{{ link_to('about/index', 'Über DBGov') }}</li>
                        <li>{{ link_to('about/faq', 'FAQ') }}</li>
                    </ul>
                </li>
            {% endif %}

          </ul>

        <ul class="nav pull-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ auth.getName() }} <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li>{{ link_to('users/changePassword', 'Email/Passswort ändern') }}</li>
              <li>{{ link_to('impressum/editImpressum', 'Impressum ändern') }}</li>
            </ul>
          </li>
          <li>{{ link_to('session/logout', 'Logout') }}</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container main-container">
  {{ content() }}
</div>

<footer>
    Made with love © {{ date("Y") }} juic3pow3rs.<br>
    {%- if (logged_in is empty) %}
        {{ link_to('session/login', 'Login') }}
    {% endif %}
    {{ link_to('about/impressum', 'Impressum') }}
</footer>
