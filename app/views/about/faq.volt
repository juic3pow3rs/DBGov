<script
        src="http://code.jquery.com/jquery-3.1.0.min.js"
        integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s="
        crossorigin="anonymous">
</script>

<script>
    $(document).ready(function(){
        $(".flip").click(function(){
            $(".panel1").toggle();
        });
        $(".flip2").click(function(){
            $(".panel2").toggle();
        });
        $(".flip3").click(function(){
            $(".panel3").toggle();
        });
        $(".flip4").click(function(){
            $(".panel4").toggle();
        });
        $(".flip5").click(function(){
            $(".panel5").toggle();
        });
        $(".flip6").click(function(){
            $(".panel6").toggle();
        });
    });
</script>

<style type="text/css">
    p.flip, p.flip2, p.flip3, p.flip4, p.flip5, p.flip6
    {
        padding:5px;
    }
    div .panel1, .panel2, .panel3, .panel4, .panel5, .panel6
    {
        display:none;
    }
    p.flip, p.flip2, p.flip3, p.flip4, p.flip5, p.flip6
    {
        cursor:pointer;
    }

    .border {
        border:1px solid #000000 !important;
    }
</style>

{{ content() }}

<div align="center">

    <div align="left">
        <h2>FAQ - Häufig gestellte Fragen</h2>
    </div>
    <br>
    <div align="left">
        <h3>Ich habe meine Passwort vergessen</h3>
        <p class="flip"><span class="icon-plus-sign" aria-hidden="true"></span>Anzeigen/Verbergen</p>
        <div class="panel1">
            <p>Auf der folgenden Seite die E-Mail Adresse eingeben und auf "Send" klicken. An die angegebene E-Mail Adresse werden dann Instruktionen zum Passwort zurücksetzen gesendet:</p>
            <p>{{ link_to('session/forgotPassword', 'Passwort vergessen') }}</p>
            <p>Der Inhalt der E-Mail sieht wie folgt aus:</p>
            <p><img src="/public/img/resetpw.png" class="border"></p>
            <p>Durch einen Klick auf "Zurücksetzen" kommt man auf die "Passwort & E-Mail ändern" Seite, auf der das Passwort und ggf. die E-Mail Adresse geändert werden kann.</p>
        </div>
    </div>

    <div align="left">
        <h3>Warum muss ich das Email und das Passwort ändern?</h3>
        <p class="flip2"><span class="icon-plus-sign" aria-hidden="true"></span>Anzeigen/Verbergen</p>
        <div class="panel2">
            <p>
                Bei dem ersten Login in das System sind sowohl für die E-Mail Adresse, als auch für das Passwort Initialwerte vergeben.
                Aus Sicherheitsgründen, sollte beides nach dem ersten Login sofort geändert werden
            </p>
        </div>
    </div>

    <div align="left">
        <h3>Wie erstelle ich eine Klasse?</h3>
        <p class="flip3"><span class="icon-plus-sign" aria-hidden="true"></span>Anzeigen/Verbergen</p>
        <div class="panel3">
            <p>Das Erstellen einer neuen Klasse ist nur eingeloggt möglich!</p>
            <p>Um eine neue Klasse zu erstellen bitte folgenden Link verwenden: {{ link_to('klasse/new', 'Hier Klicken') }} oder über das Menu dorthin navigieren.</p>
            <p>Folgende Eingabemaske wird aufgerufen</p>
            <p><img src="/public/img/klasse_create_inhalt.png" class="border"></p>
            <p>Die Felder entsprechend ausfüllen und auf "Anlegen" klicken. Bitte Format Vorgaben beachten, genaue angaben in der Kontext-Hilfe (Mauszeiger auf das Fragezeichen).</p>
            <p>Beispiel Schüler-Liste als CSV zur Bearbeitung: <a href="/klasse/download/muster_schuelerliste.csv">Download Muster CSV-Datei</a></p>
            <p>Durch einen Haken im Kästchen "Benutzernamen anonymisieren" werden die Namen der Schüler nicht zum generieren der Benutzernamen verwendet. </p>
            <p>Nach dem Anlegen wird man auf die Klassenliste weitergeleitet. Dort ist es möglich die Zugangsdaten als PDF zu downloaden und die Klasse wieder zu löschen.</p>
        </div>
    </div>

    <div align="left">
        <h3>Wie ändere ich das Impressum?</h3>
        <p class="flip4"><span class="icon-plus-sign" aria-hidden="true"></span>Anzeigen/Verbergen</p>
        <div class="panel4">

            <p>Um die Daten des Verantwortlichen im Impressum zu ändern bitte folgenden Link verwenden: {{ link_to('impressum/editImpressum', 'Hier Klicken') }} oder über das Menu dorthin navigieren:</p>
            <p><img src="/public/img/impressum_link.png" class="border"></p>
            <p>Folgende Eingabemaske wird aufgerufen</p>
            <p><img src="/public/img/impressum_ändern.png" class="border"></p>
            <p>Die Felder entsprechend ausfüllen und auf "Speichern" klicken. Nach dem Speichern wird man direkt auf das komplette Impressum weitergeleitet.</p>
        </div>
    </div>

    <div align="left">
        <h3>Ich habe einen Fehler gefunden</h3>
        <p class="flip5"><span class="icon-plus-sign" aria-hidden="true"></span>Anzeigen/Verbergen</p>
        <div class="panel5">
            <p>Bitte eine E-Mail mit Beschreibung des Fehlers wo und wie dieser aufgetreten ist und sofern vorhanden die Fehlermeldung an folgende Adresse senden:</p>
            <p><a href="mailto:andreas.g.mueller@gmx.net">andreas.g.mueller@gmx.net</a></p>
        </div>
    </div>

    <div align="left">
        <h3>Ich möchte DBGov verbessern oder weiterentwickeln</h3>
        <p class="flip6"><span class="icon-plus-sign" aria-hidden="true"></span>Anzeigen/Verbergen</p>
        <div class="panel6">
            <p>
                Das komplette Projekt ist OpenSource und auf GitHub zu finden. Für den Fall einer Verbesserung bitte ein Pull-Request öffnen.
                Alternativ ist es auch möglich, das Projekt zu forken und eigenständig weiter zu entwickeln.
            </p>
            <p><a href="https://github.com/juic3pow3rs/DBGov">GitHub</a></p>
        </div>
    </div>
</div>