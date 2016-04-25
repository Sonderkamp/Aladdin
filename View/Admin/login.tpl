<div class="container">

    <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">

        <div class="row">

        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title text-center">Administrator</div>
            </div>

            <div class="panel-body">
                {if isset($error)}
                <div class="alert alert-danger" id="err">Error: {htmlspecialchars($error)}</div>
                {else}
                <p id="err"></p>
                {/if}
                <br>
                <form action="/Admin" name="form" id="form" class="form-horizontal" enctype="multipart/form-data"
                      method="POST">

                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                        <input id="user" type="text" class="form-control" name="username" value="{$username}"
                               placeholder="Gebruikersnaam" data-validation="required"
                               data-validation-error-msg="vul een gebruikersnaam in.">
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input id="password" type="password" class="form-control" name="password"
                               placeholder="Password" data-validation="required" data-validation-error-msg="vul een wachtwoord in.">
                    </div>

                    <div class="form-group">
                        <!-- Button -->
                        <div class="col-sm-12 controls">
                            <button type="submit" href="#" class="btn btn-primary pull-right"><i
                                    class="glyphicon glyphicon-log-in"></i> Log in
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
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