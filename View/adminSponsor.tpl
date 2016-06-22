<script>
    function popup(mylink, windowname, w, h) {
        if (!window.focus)return true;
        var href;
        if (typeof(mylink) == 'string') href = mylink; else href = mylink.href;
        window.open(href, windowname, 'width=' + w + ',height=' + h + ',scrollbars=yes');
        return false;
    }
</script>
<img src="/Resources/Images/banner.jpg" class="img-responsive width background">
<div class="container">

    <span class="info">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#InfoModal">
                <span class="glyphicon glyphicon-info-sign"></span>
            </button>
    </span>

    <h5>Sponsor Beheer</h5>
    <div class="col-md-2">
        <ul class="nav nav-pills nav-stacked">
            <li {if $currentPage == "sponsors"}class="active"{/if}>
                <a href="#requestedTab" data-toggle="tab">Sponsors</a>
            </li>
        </ul>
    </div>


    <form action="/AdminSponsor/action=searchSponsor/" method="get">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><input class="form-control" name="searchKey"
                                                                placeholder="Zoeken.."></div>
        <div>
            <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#add">
                <span class="glyphicon glyphicon-plus"></span>
            </button>
            <button type="submit" class="btn btn-primary">Zoek</button>
            <button type="submit" class="btn btn-primary">Reset zoekfilter</button>
        </div>
    </form>

    <div class="col-md-10">
        <div class="tab-content">
            {if isset($error)}
                <div class="alert  form-error alert-dismissible " role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    {htmlspecialchars($error)}
                </div>
            {/if}
            <div class="tab-pane fade in {if $currentPage == "sponsors"}active{/if}" id="requestedTab">
                {if $sponsors}
                    <br>
                    <br>
                    <table class="table panel">
                        <thead>
                        <tr>
                            <th>Bedrijfsnaam</th>
                            <th>Url</th>
                            <th>Beschrijving</th>
                            <th>Email</th>
                            <th>Actie</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$sponsors item=sponsor}
                            <tr>
                                <td>{$sponsor->name}</td>
                                <td><a href="http://{$sponsor->url}" target="_blank">{$sponsor->url}</a></td>

                                <td>{$sponsor->description}</td>
                                <td>{$sponsor->userMail}</td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                           aria-haspopup="true"
                                           aria-expanded="false"></span>Kies</span><span class="caret"></span></a>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a data-toggle="modal"
                                                   data-target="#myModal{preg_replace('/\s+/', '', htmlentities(trim($sponsor->id),ENT_QUOTES))}">
                                                    Bewerken</a>
                                                <a href="/AdminSponsor/action=deleteSponsor?sponsorID={$sponsor->id}">Verwijderen</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {else}
                    <div class="center-block text-center"><h4>Er zijn geen sponsoren.</h4></div>
                {/if}
            </div>
        </div>
    </div>


    <div class="modal fade" id="add" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Sponsor toevoegen</h4>
                </div>
                <div class="modal-body">
                    <form action="/adminsponsor/action=addSponsor" method="post" enctype="multipart/form-data">
                        <fieldset class="form-group">
                            <h6 class="modal-title">Contactpersoon:</h6>
                            <select name="userEmail">
                                <option value="default"> - kies gebruiker -</option>
                                {foreach $users as $user}
                                    <option value="{$user->displayName}">{$user->displayName} </option>
                                {/foreach}
                            </select>
                            <br><br>
                            <h6 class="modal-title">Bedrijfsnaam:</h6>
                            <input type="text" class="form-control" name="name"
                                   placeholder="Bedrijfsnaam">
                            <br>
                            <h6 class="modal-title">Beschrijving:</h6>
                            <input type="text" class="form-control" name="description"
                                   placeholder="Beschrijving">
                            <br>
                            <h6 class="modal-title">Url:</h6>
                            <input type="text" class="form-control" name="url"
                                   placeholder="Link naar website">
                            <br>
                            <h6 class="modal-title">Kies afbeelding:</h6>
                            <input class="form-control" name="img" type="file"/><br/>
                        </fieldset>
                        <button type="submit" class="btn btn-default">Opslaan</button>
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>

        </div>
    </div>


    {foreach from=$sponsors item=sponsor}
        <div id="myModal{preg_replace('/\s+/', '', htmlentities(trim($sponsor->id),ENT_QUOTES))}" class="modal fade"
             role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <form action="/AdminSponsor/action=updateSponsor" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>


                            <input name="id" type="hidden" value="{$sponsor->id}" readonly>

                            <h6 class="modal-title">Contactpersoon:</h6>
                            <select name="userEmail">
                                <option value="{$sponsor -> userMail}">{$sponsor -> userMail}</option>
                                {foreach $users as $user}
                                    {if ($user->email) != ($sponsor -> userMail)}
                                        <option value="{$user->email}">{$user->email}</option>
                                    {/if}
                                {/foreach}
                            </select>
                            <br><br>
                            <h6 class="modal-title">Bedrijfsnaam:</h6>
                            <input type="text" class="form-control" name="name"
                                   placeholder="Bedrijfsnaam" value="{$sponsor -> name}">
                            <br>
                            <h6 class="modal-title">Beschrijving:</h6>
                            <input type="text" class="form-control" name="description"
                                   placeholder="Beschrijving" value="{$sponsor -> description}">
                            <br>
                            <h6 class="modal-title">URL:</h6>
                            <input type="text" class="form-control" name="url"
                                   placeholder="Link naar website" value="{$sponsor -> url}">
                            <br>
                            <h6 class="modal-title">Kies afbeelding:</h6>
                            <input class="form-control" name="img" type="file"/><br/>
                            <a href="{$sponsor->image}" target="_blank">
                                <img class="thumbnail commentImage" src="{$sponsor->image}">
                            </a>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default pull-left">Opslaan</button>
                            <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>

                            {*<a class="btn btn-default" data-dismiss="modal">Annuleren</a>*}
                        </div>
                    </form>

                </div>
            </div>
        </div>
    {/foreach}
</div>

<div id="InfoModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Sponsor beheer</h4>
            </div>
            <div class="modal-body">

                <p>
                    Bij het aanmaken van een sponsor is de bedrijfsnaam of de gebruiker verplicht.<br>
                    <a>Kies<span class="glyphicon glyphicon-triangle-bottom"/></a> hiermee kunt u kiezen om een sponsor
                    te bewerken of te verwijderen.<br><br>

                    Hier vind u uitleg over de icoontjes die in het talentbeheer systeem voorkomen:
                </p>

                <div class="col-xs-12 info-row">
                    <button class="btn btn-sm">
                        <span class="glyphicon glyphicon glyphicon-plus"/>
                    </button>
                    <span class="info-text">Sponsor toevoegen</span>
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











