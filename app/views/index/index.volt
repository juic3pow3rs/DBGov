{{ content() }}

<header class="jumbotron subhead" id="overview">
	<div class="hero-unit">
		<h1>Welcome!</h1>
		<p class="lead">This is a website secured by Phalcon Framework</p>

		<div align="right">
      {{ link_to('session/signup', '<i class="icon-ok icon-white"></i> Create an Account', 'class': 'btn btn-primary btn-large') }}
		</div>
	</div>
</header>

<div class="row">

    <div class="span4">
      <div class="well">
      	<h3>Done</h3>
      	<p>Login/Logout</p>
      	<p>Passwort vergessen</p>
        <p>Klassen Create, Delete, Search & List (kinda)</p>
        <p>CSV Upload & Auslesen</p
        <p>Namenskonventionen Klassennamen</p>
      </div>
    </div>

    <div class="span4">
      <h3>Next ToDo</h3>
      <p>PDF Creation</p>
      <p>E-Mail ändern</p>
      <p>Registrieren unterbinden</p>
      <p>Klassen Funktion nur eingeloggt aufrufbar</p>
      <p>Aufforderung bei erstem Login E-Mail & Passwort zu ändern</p>
      <p>Genaue Rechtevergabe globale DBs</p>
      <p>Help Texte einbauen</p>
      <p>Kontakt-Seite</p>
      <p>Linux & MySQL root Zugangsdaten irgendwo einpflegen</p>
    </div>

    <div class="span4">
      <h3>Developer</h3>
      <address>
        <strong>Andreas Müller</strong><br>
          <a href="https://github.com/juic3pow3rs/DBGov">Projekt auf GitHub</a><br>
      </address>
      <address>
        <strong>E-Mail</strong><br>
        <a href="mailto:#">andreas.g.mueller@gmx.net</a>
      </address>
    </div>

  </div>
