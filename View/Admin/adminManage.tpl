<div class="container">
    <div>
        <h3 class="col-xs-12 col-sm-6 col-md-6 col-lg-6">Moderators beheren</h3>

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <button class="btn btn-info pull-right" data-toggle="modal" data-target="#addAdmin">
                <span class="glyphicon glyphicon-plus"></span>
            </button>
        </div>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>Gebruikersnaam</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$admins item=admin}
            <tr>
                <td class="col-xs-12 col-sm-12 col-md-12 col-lg-12">{htmlspecialchars(trim($admin->username))}</td>
                <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>

<!-- Modal Add-->
<div id="addAdmin" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Moderator toevoegen</h4>
            </div>
            <form action="/adminManage/action=addAdmin" method="post" class="horizontal">
                <div class="modal-body">
                    {if !empty($adminError)}
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Error!</strong> {$adminError}
                        </div>
                    {/if}
                    <div class="form-group">
                        <label for="username" class="col-sm-4 control-label">Gebruikersnaam</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="username" id="username"
                                   placeholder="Gebruikersnaam">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-sm-4 control-label">Wachtwoord</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="password" id="password"
                                   placeholder="Wachtwoord">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="verifyPassword" class="col-sm-4 control-label">Wachtwoord bevestigen</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="verifyPassword" id="verifyPassword"
                                   placeholder="Bevestig wachtwoord">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <br>
                    <button type="button" class="btn btn-default infoLeft"
                            data-dismiss="modal">Sluiten
                    </button>
                    <button type="submit" class="btn btn-default pull-right"><span
                                class="glyphicon glyphicon-plus"></span> Toevoegen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{if !Empty($adminError)}
    <script type="text/javascript">
        $(window).load(function(){
            $('#addAdmin').modal('show');
        });
    </script>
{/if}