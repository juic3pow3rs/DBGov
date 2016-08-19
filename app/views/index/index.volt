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
        <p>Access Control List</p>
        <p>Klassen Model</p>
        <p>Klassen Controller - Create</p>
      </div>
    </div>

    <div class="span4">
      <h3>next ToDo</h3>
      <p>E-Mail ändern</p>
      <p>Registrieren unterbinden</p>
      <p>Klassen Controller - Delete</p>
    </div>

    <div class="span4">
      <h3>Developer</h3>
      <address>
        <strong>Andreas Müller</strong><br>
        GitHub Link hier<br>
      </address>
      <address>
        <strong>E-Mail</strong><br>
        <a href="mailto:#">andreas.g.mueller@gmx.net</a>
      </address>
    </div>

  </div>
