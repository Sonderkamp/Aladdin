<div class="container">


    <div>
        <h3 class="col-xs-12 col-sm-6 col-md-6 col-lg-6">Moderators beheren</h3>


        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

             <span class="info infoButtonMargin">
            <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#InfoModal">
                <span class="glyphicon glyphicon-info-sign"></span>
            </button>
            </span>

            <button class="btn btn-info pull-right" data-toggle="modal" data-target="#addAdmin">
                <span class="glyphicon glyphicon-plus"></span>
            </button>


        </div>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>Gebruikersnaam</th>
            <th>Is actief</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$admins item=admin}
            <tr>
                {if strftime(" %H:%M %#d %B %Y", strtotime($admin->creationDate)) >= strftime(" %H:%M %#d %B %Y", strtotime($currentAdmin->creationDate))}
                    <td class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        {if $admin->username != $currentAdmin->username}
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false">
                                    {htmlspecialchars(trim($admin->username))}<span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        {if $admin->isActive == 1}
                                            <a href="/AdminManage/action=blockAdmin/admin={$admin->username}">Blokkeer</a>
                                        {else}
                                            <a href="/AdminManage/action=unblockAdmin/admin={$admin->username}">Deblokkeer</a>
                                        {/if}
                                    </li>
                                </ul>
                            </div>
                        {else}
                            {htmlspecialchars(trim($admin->username))}
                        {/if}
                    </td>
                    <td class="col-xs-6 col-sm-6 col-md-6 col-lg-6">{if $admin->isActive == 1}Ja{else}Nee{/if}</td>
                    <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                        <button type="button" class="btn btn-inbox btn-small" data-toggle="modal"
                                data-target="#{htmlspecialchars($admin->username)}Edit">
                            <span class="glyphicon glyphicon-edit"></span>
                        </button>
                    </td>
                {else}
                    <td class="col-xs-6 col-sm-6 col-md-6 col-lg-6">{htmlspecialchars(trim($admin->username))}</td>
                    <td class="col-xs-6 col-sm-6 col-md-6 col-lg-6">{if $admin->isActive == 1}Ja{else}Nee{/if}</td>
                    <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></td>
                {/if}
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
                    {if !empty($addError)}
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Error!</strong> {htmlspecialchars($addError)}
                        </div>
                    {/if}
                    <div class="form-group">
                        <label for="username" class="col-sm-4 control-label">Gebruikersnaam</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="username" id="username"
                                   placeholder="Gebruikersnaam"{if !empty($addUsername)} value="{htmlspecialchars($addUsername)}"{/if}>
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


{foreach from=$admins item=admin}
    {if strftime(" %H:%M %#d %B %Y", strtotime($admin->creationDate)) >= strftime(" %H:%M %#d %B %Y", strtotime($currentAdmin->creationDate))}
        <!-- Modal Edit-->
        <div id="{htmlspecialchars($admin->username)}Edit" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Het wachtwoord van "{htmlspecialchars($admin->username)}" wijzigen</h4>
                        <br>
                    </div>
                    <form action="/adminManage/action=editAdmin" method="post" class="horizontal">
                        <input type="hidden" name="oldUsername" value="{htmlspecialchars($admin->username)}">
                        <div class="modal-body">
                            {if !empty($editError)}
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Error!</strong> {htmlspecialchars($editError)}
                                </div>
                            {/if}

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
                                    <input type="password" class="form-control" name="verifyPassword"
                                           id="verifyPassword"
                                           placeholder="Bevestig wachtwoord">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default infoLeft"
                                    data-dismiss="modal">Sluiten
                            </button>
                            <button type="submit" class="btn btn-default pull-right"><span
                                        class="glyphicon glyphicon-edit"></span> Toevoegen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    {/if}
{/foreach}


<div id="InfoModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Moderators beheer</h4>
            </div>
            <div class="modal-body">

                <p>
                    Op deze pagina kun je moderators aanmaken.<br><br>

                    Hier vind u uitleg over de icoontjes die in het moderator beheer voorkomen.
                </p>

                <div class="col-xs-12 info-row">
                    <button class="btn btn-sm">
                        <span class="glyphicon glyphicon glyphicon-plus"></span>
                    </button>
                    <span class="info-text">Nieuwe moderator aanmaken</span>
                </div>

                <div class="col-xs-12 info-row">
                    <button class="btn btn-sm">
                        <span class="glyphicon glyphicon glyphicon-edit"></span>
                    </button>
                    <span class="info-text">Wachtwoord wijzigen</span>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Sluiten
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


{if !Empty($addError)}
    <script type="text/javascript">
        $(window).load(function () {
            $('#addAdmin').modal('show');
        });
    </script>
{/if}

{if !Empty($oldUsername) && !Empty($editError)}
    <script type="text/javascript">
        $(window).load(function () {
            $('#{htmlspecialchars($oldUsername)}').modal('show');
        });
    </script>
{/if}