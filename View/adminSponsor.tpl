<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Simon / Max-->
<!-- * Date: 8-3-2016 Rewritten on: 14-05-2016-->
<!-- */-->
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


    <form action="/AdminSponsor/action=search/" method="get">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><input class="form-control" name="search"
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
        {if isset($error)}
            <div class="alert  form-error alert-dismissible " role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                {htmlspecialchars($error)}
            </div>
        {/if}
    </div>

    <div class="col-md-10">
        <div class="tab-content">
            <div class="tab-pane fade in {if $currentPage == "sponsors"}active{/if}" id="requestedTab">
                {if $sponsors}
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Bedrijfsnaam</th>
                            <th>Url</th>
                            <th>Beschrijving</th>
                            <th>Contactpersoon</th>
                            <th>Actie</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$sponsors item=sponsor}
                            <tr>
                                <td>{$sponsor->name}</td>
                                <td>{$sponsor->image}</td>
                                <td>{$sponsor->url}</td>
                                <td>{$sponsor->userMail}</td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                           aria-haspopup="true"
                                           aria-expanded="false"></span>
                                            Kies</span><span class="caret"></span></a>


                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="#">Zet op non-actief</a>
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
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

</div>











