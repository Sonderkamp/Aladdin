<div class="container">

    {if isset($error)}
    <div class="form-error" id="err">Error: {htmlspecialchars($error)}</div>
    {else}
    <div id="err"></div>
    {/if}
    <div id="error"></div>

    <form name="recover" action="/Account/action=recover" method="post" onsubmit="return validateEmail()">
       <input placeholder="Email" type="text" name="username" value="{$username}" data-validation="email"
              data-validation-error-msg="vul een valide gebruikersnaam in.">
        <input class="btn btn-default" value="Reset Wachtwoord" type="submit">
    </form>
    <br>
    <a type="button" class="btn btn-default" href="/Account/action=Register">Register</a>
    <a type="button" class="btn btn-default" href="/Account">log in</a>

</div>

<script>

    var $messages = $('#err');
    // http://www.formvalidator.net/
    $.validate({
        validateOnBlur : false,
        errorMessagePosition : $messages,
        onValidate : function() {
            $messages.empty();
            $("#error").empty();
            $("#error").removeClass("form-error");
        },
        onSuccess : function(){
            if(!validateEmail())
            {
                $("#error").text("Er bestaat geen gebruiker met dit email adres.");
                $("#error").addClass("form-error");
            }
        }

    });

    function validateEmail() {
        {literal}
        var val = {username: document.forms["recover"]["username"].value};
        {/literal}
        var ret = true;
        $.ajax({
            type: 'POST',
            url: "/Account/action=check",
            data: val,
            dataType: "text",
            success: function (resultData) {
                resultData = JSON.parse(resultData);
                console.log(resultData);
                if (resultData.result == false) {
                    ret = false;
                }
                return false;
            },
            async: false
        });

        return ret;
    }
</script>