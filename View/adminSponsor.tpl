<script>
    function popup(mylink, windowname, w, h) {
        if (!window.focus)return true;
        var href;
        if (typeof(mylink) == 'string') href = mylink; else href = mylink.href;
        window.open(href, windowname, 'width=' + w + ',height=' + h + ',scrollbars=yes');
        return false;
    }
</script>

<div class="container">


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
            <button type="submit" class="btn btn-primary pull-right">Reset zoekfilter</button>
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
                    <table class="table">
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
                                <td><a href="http://{$sponsor->url}" target="_blank">{$sponsor->url}</a> </td>

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
                    <form action="/adminsponsor/action=addSponsor" method="post">
                        <fieldset class="form-group">
                            <select name="userEmail">
                                <option value="default">- kies gebruiker -</option>
                                {foreach $users as $user}
                                    <option value="{$user->email}">{$user->email}</option>
                                {/foreach}
                            </select>
                            <input type="text" class="form-control" name="name"
                                   placeholder="Bedrijfsnaam">
                            <input type="text" class="form-control" name="description"
                                   placeholder="Beschrijving">
                            <input type="text" class="form-control" name="url"
                                   placeholder="Link naar website">
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
                    <form action="/AdminSponsor/action=updateSponsor" method="post">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                            <h6 class="modal-title">ID:</h6>
                            <input name="id" class="form-control" value="{$sponsor->id}" readonly>

                            <h6 class="modal-title">Contactpersoon:</h6>
                            <select name="userEmail">
                                <option value="{$sponsor -> userMail}">{$sponsor -> userMail}</option>
                                {foreach $users as $user}
                                    {if ($user->email) != ($sponsor -> userMail)}
                                        <option value="{$user->email}">{$user->email}</option>
                                    {/if}
                                {/foreach}
                            </select>

                            <h6 class="modal-title">Bedrijfsnaam:</h6>
                            <input type="text" class="form-control" name="name"
                                   placeholder="Bedrijfsnaam" value="{$sponsor -> name}">

                            <h6 class="modal-title">Beschrijving:</h6>
                            <input type="text" class="form-control" name="description"
                                   placeholder="Beschrijving" value="{$sponsor -> description}">

                            <h6 class="modal-title">URL:</h6>
                            <input type="text" class="form-control" name="url"
                                   placeholder="Link naar website" value="{$sponsor -> url}">

                            <input class="form-control" name="img" type="file"/><br/>
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










