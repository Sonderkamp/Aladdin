<div class="container">

    {if isset($error)}
    <div class="form-error" id="err">Error: {htmlspecialchars($error)}</div>
    {else}
    <p id="err"></p>
    {/if}

    <form action="/Account" method="post">
        <p> <span><input type="text" placeholder="Email" name="username" value="{$username}"  data-validation="email"
                         data-validation-error-msg="vul een valide gebruikersnaam in."></span>
             <span><input type="password" placeholder="Wachtwoord" name="password" data-validation="required" data-validation-error-msg="vul een wachtwoord in."></span>
        <input class="btn btn-default" value="Inloggen" type="submit">
    </form>
    <br>
    <a type="button" class="btn btn-default" href="/Account/action=Register">Register</a>
    <a type="button" class="btn btn-default" href="/Account/action=Recover">Vergeten</a>

</div>

<script>

    var $messages = $('#err');
    // http://www.formvalidator.net/
    $.validate({
        validateOnBlur : false,
        errorMessagePosition : $messages,
        onValidate : function() {
            $messages.empty();
        }
    });

</script>


