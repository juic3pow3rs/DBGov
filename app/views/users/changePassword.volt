{{ content() }}

<form method="post" autocomplete="off" action="{{ url("users/changePassword") }}">

    <div class="center scaffold">

        <h2>Passwort & E-Mail ändern</h2>

        <div class="clearfix">
            <label for="email">Change Email</label>
            {{ form.render("email") }}
        </div>

        <div class="clearfix">
            <label for="password1">Password</label>
            {{ form.render("password1") }}
        </div>

        <div class="clearfix">
            <label for="confirmPassword">Confirm Password</label>
            {{ form.render("confirmPassword") }}
        </div>

        <div class="clearfix">
            {{ submit_button("Ändern", "class": "btn btn-primary") }}
        </div>

    </div>

</form>