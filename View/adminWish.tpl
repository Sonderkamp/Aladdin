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

<img src="/Resources/Images/banner.jpg" class="img-responsive width background">
<div class="container">

    <h5>Wensen Beheer</h5>
    <div class="col-md-2">
        <ul class="nav nav-pills nav-stacked">
            <li {if $currentPage == "requested"}class="active"{/if}>
                <a href="#requestedTab" data-toggle="tab">Aangevraagde wensen</a>
            </li>

            <li {if $currentPage == "published"}class="active"{/if}>
                <a href="#publishedTab" data-toggle="tab">Gepubliceerde wensen</a>
            </li>

            <li {if $currentPage == "matched"}class="active"{/if}>
                <a href="#matchedTab" data-toggle="tab">Gevonden Matches</a>
            </li>

            <li {if $currentPage == "current"}class="active"{/if}>
                <a href="#currentTab" data-toggle="tab">Wordt vervuld</a>
            </li>

            <li {if $currentPage == "completed"}class="active"{/if}>
                <a href="#completedTab" data-toggle="tab">Vervulde Wensen</a>
            </li>

            <li {if $currentPage == "denied"}class="active"{/if}>
                <a href="#deniedTab" data-toggle="tab">Geweigerde Wensen</a>
            </li>

            <li {if $currentPage == "deleted"}class="active"{/if}>
                <a href="#deletedTab" data-toggle="tab">Verwijderde wensen</a>
            </li>
        </ul>
    </div>


    <div id="InfoModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Uitleg wens beheer</h4>
                </div>
                <div class="modal-body">

                    <p> Hier vind u uitleg over de icoontjes die in het wensbeheer systeem voor komen:</p>

                    <div class="col-xs-12 info-row">
                        <button class="btn btn-sm">
                            <span class="glyphicon glyphicon glyphicon-eye-open"></span>
                        </button>
                        <span class="info-text">Opent een pagina waar je de bijbehorende wens kan ziens</span>
                    </div>

                    <div class="col-xs-12 info-row">
                        <button class="btn btn-sm">
                            <span class="glyphicon glyphicon glyphicon-ok"></span>
                        </button>
                        <span class="info-text">Accepteert de wens</span>
                    </div>

                    <div class="col-xs-12 info-row">
                        <button class="btn btn-sm">
                            <span class="glyphicon glyphicon glyphicon-remove"></span>
                        </button>
                        <span class="info-text">Weigert de wens</span>
                    </div>

                    <div class="col-xs-12 info-row">
                        <button class="btn btn-sm">
                            <span class="glyphicon glyphicon glyphicon-user"></span>
                        </button>
                        <span class="info-text">Gaat naar profiel pagina van een gebruiker</span>
                    </div>

                    <div class="col-xs-12 info-row">
                        <button class="btn btn-sm">
                            <span class="glyphicon glyphicon glyphicon glyphicon-trash"></span>
                        </button>
                        <span class="info-text">Verwijderd wens</span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Sluiten
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-10">
        <span class="info">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#InfoModal">
                <span class="glyphicon glyphicon-info-sign"></span>
            </button>
        </span>
        <div class="tab-content">

            {*Requested Wishes*}

            <div class="tab-pane fade in {if $currentPage == "requested"}active{/if}" id="requestedTab">
                {if $requested}
                    <table class="table panel">
                        <thead>
                        <tr>
                            <th>Wish id</th>
                            <th>Gebruiker</th>
                            <th>Wens</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$requested item=wish}
                            <tr>
                                <td>{$wish->id}</td>
                                <td>{$wish->user->displayName}</td>
                                <td>{$wish->title}</td>
                                <td>{$wish->status}</td>
                                <td>
                                    {include file='View/adminWishModals.tpl'}
                                    <div class="dropdown">
                                        <a href="#"
                                           class="dropdown-toggle"
                                           data-toggle="dropdown"
                                           role="button"
                                           aria-haspopup="true"
                                           aria-expanded="false">
                                            <span class="glyphicon glyphicon-chevron-down"></span>
                                        </a>
                                        <ul class="dropdown-menu small-dropdown-menu">

                                            <li>
                                                <a
                                                   href="/wishes/action=getSpecificWish/admin=true/Id={$wish->id}"
                                                   onClick="return popup(this, 'notes',900,400)">
                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                    Wens bekijken
                                                </a>
                                                {*<a href="/wishes/action=getSpecificWish?Id={$wish->id}" class="btn btn-sm">*}
                                                {*<span class="glyphicon glyphicon-eye-open"></span>*}
                                                {*</a>*}
                                            </li>

                                            <li>
                                                <a data-toggle="modal"
                                                   data-target="#acceptModal{$wish->id}">
                                                    <span class="glyphicon glyphicon-ok"></span>
                                                    Wens accepteren
                                                </a>
                                            </li>

                                            <li>
                                                <a data-toggle="modal"
                                                   data-target="#refuseModal{$wish->id}">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                    Wens weigeren
                                                </a>
                                            </li>

                                            <li>
                                                <a href="/profileoverview/action=viewProfile/user={$wish->user->email}">
                                                    <span class="glyphicon glyphicon-user"></span>
                                                    Gebruiker bekijken
                                                </a>
                                            </li>

                                            <li>
                                                <a href="/AdminWish/action=deleteWish?Id={$wish->id}">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                    Verwijder wens
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {else}
                    <div class="center-block text-center"><h4>Er zijn momenteel geen aangevraagde wensen</h4></div>
                {/if}
            </div>

            {*Published Wishes*}

            <div class="tab-pane fade in {if $currentPage == "published"}active{/if}" id="publishedTab">
                {if $published}
                    <table class="table panel">
                        <thead>
                        <tr>
                            <th>Wish id</th>
                            <th>Gebruiker</th>
                            <th>Wens</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$published item=wish}
                            <tr>
                                <td>{$wish->id}</td>
                                <td>{$wish->user->displayName}</td>
                                <td>{$wish->title}</td>
                                <td>{$wish->status}</td>
                                <td>
                                    {include file='View/adminWishModals.tpl'}
                                    <div class="dropdown">
                                        <a href="#"
                                           class="dropdown-toggle"
                                           data-toggle="dropdown"
                                           role="button"
                                           aria-haspopup="true"
                                           aria-expanded="false">
                                            <span class="glyphicon glyphicon-chevron-down"></span>
                                        </a>
                                        <ul class="dropdown-menu small-dropdown-menu">

                                            <li>
                                                <a
                                                   href="/wishes/action=getSpecificWish/admin=true/Id={$wish->id}"
                                                   onClick="return popup(this, 'notes',900,400)">
                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                    Wens bekijken
                                                </a>
                                            </li>

                                            <li>
                                                <a href="/profileoverview/action=viewProfile/user={$wish->user->email}">
                                                    <span class="glyphicon glyphicon-user"></span>
                                                    Gebruiker bekijken
                                                </a>
                                            </li>

                                            <li>
                                                <a href="/AdminWish/action=deleteWish?Id={$wish->id}">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                    Verwijder wens
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {else}
                    <div class="center-block text-center"><h4>Er zijn momenteel geen gepubliceerde wensen</h4></div>
                {/if}
            </div>

            {*Matched Wishes*}

            <div class="tab-pane fade in {if $currentPage == "matched"}active{/if}" id="matchedTab">
                {if $matched}
                    <table class="table panel">
                        <thead>
                        <tr>
                            <th>Wish id</th>
                            <th>Gebruiker</th>
                            <th>Wens</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$matched item=wish}
                            <tr>
                                <td>{$wish->id}</td>
                                <td>{$wish->user->displayName}</td>
                                <td>{$wish->title}</td>
                                <td>{$wish->status}</td>
                                <td>
                                    {include file='View/adminWishModals.tpl'}
                                    <div class="dropdown">
                                        <a href="#"
                                           class="dropdown-toggle"
                                           data-toggle="dropdown"
                                           role="button"
                                           aria-haspopup="true"
                                           aria-expanded="false">
                                            <span class="glyphicon glyphicon-chevron-down"></span>
                                        </a>
                                        <ul class="dropdown-menu small-dropdown-menu">
                                            <li>
                                                <a
                                                   href="/wishes/action=getSpecificWish/admin=true/Id={$wish->id}"
                                                   onClick="return popup(this, 'notes',900,400)">
                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                    Wens bekijken
                                                </a>
                                            </li>

                                            <li>
                                                <a href="/profileoverview/action=viewProfile/user={$wish->user->email}">
                                                    <span class="glyphicon glyphicon-user"></span>
                                                    Gebruiker bekijken
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {else}
                    <div class="center-block text-center"><h4>Er zijn momenteel geen gevonden matches</h4></div>
                {/if}
            </div>

            {*Current Wishes*}

            <div class="tab-pane fade in {if $currentPage == "current"}active{/if}" id="currentTab">
                {if $current}
                    <table class="table panel">
                        <thead>
                        <tr>
                            <th>Wish id</th>
                            <th>Gebruiker</th>
                            <th>Wens</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$current item=wish}
                            <tr>
                                <td>{$wish->id}</td>
                                <td>{$wish->user->displayName}</td>
                                <td>{$wish->title}</td>
                                <td>{$wish->status}</td>
                                <td>
                                    {include file='View/adminWishModals.tpl'}
                                    <div class="dropdown">
                                        <a href="#"
                                           class="dropdown-toggle"
                                           data-toggle="dropdown"
                                           role="button"
                                           aria-haspopup="true"
                                           aria-expanded="false">
                                            <span class="glyphicon glyphicon-chevron-down"></span>
                                        </a>
                                        <ul class="dropdown-menu small-dropdown-menu">

                                            <li>
                                                <a
                                                   href="/wishes/action=getSpecificWish/admin=true/Id={$wish->id}"
                                                   onClick="return popup(this, 'notes',900,400)">
                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                    Wens bekijken
                                                </a>
                                            </li>

                                            <li>
                                                <a href="/profileoverview/action=viewProfile/user={$wish->user->email}">
                                                    <span class="glyphicon glyphicon-user"></span>
                                                    Gebruiker bekijken
                                                </a>
                                            </li>

                                            <li>
                                                <a href="/AdminWish/action=deleteWish?Id={$wish->id}">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                    Wens verwijderen
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {else}
                    <div class="center-block text-center"><h4>Er zijn momenteel geen wensen die worden vervuld</h4>
                    </div>
                {/if}
            </div>

            {*Completed Wishes*}

            <div class="tab-pane fade in {if $currentPage == "completed"}active{/if}" id="completedTab">
                {if $completed}
                    <table class="table panel">
                        <thead>
                        <tr>
                            <th>Wish id</th>
                            <th>Gebruiker</th>
                            <th>Wens</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$completed item=wish}
                            <tr>
                                <td>{$wish->id}</td>
                                <td>{$wish->user->displayName}</td>
                                <td>{$wish->title}</td>
                                <td>{$wish->status}</td>
                                <td>
                                    {include file='View/adminWishModals.tpl'}
                                    <div class="dropdown">
                                        <a href="#"
                                           class="dropdown-toggle"
                                           data-toggle="dropdown"
                                           role="button"
                                           aria-haspopup="true"
                                           aria-expanded="false">
                                            <span class="glyphicon glyphicon-chevron-down"></span>
                                        </a>
                                        <ul class="dropdown-menu small-dropdown-menu">

                                            <li>
                                                <a
                                                   href="/wishes/action=getSpecificWish/admin=true/Id={$wish->id}"
                                                   onClick="return popup(this, 'notes',900,400)">
                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                    Wens bekijken
                                                </a>
                                            </li>

                                            <li>
                                                <a href="/profileoverview/action=viewProfile/user={$wish->user->email}">
                                                    <span class="glyphicon glyphicon-user"></span>
                                                    Wens verwijderen
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {else}
                    <div class="center-block text-center"><h4>Er zijn momenteel geen vervulde wensen</h4></div>
                {/if}
            </div>

            {*Denied Wishes*}

            <div class="tab-pane fade in {if $currentPage == "denied"}active{/if}" id="deniedTab">
                {if $denied}
                    <table class="table panel">
                        <thead>
                        <tr>
                            <th>Wish id</th>
                            <th>Gebruiker</th>
                            <th>Wens</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$denied item=wish}
                            <tr>
                                <td>{$wish->id}</td>
                                <td>{$wish->user->displayName}</td>
                                <td>{$wish->title}</td>
                                <td>{$wish->status}</td>
                                <td>
                                    {include file='View/adminWishModals.tpl'}
                                    <div class="dropdown">
                                        <a href="#"
                                           class="dropdown-toggle"
                                           data-toggle="dropdown"
                                           role="button"
                                           aria-haspopup="true"
                                           aria-expanded="false">
                                            <span class="glyphicon glyphicon-chevron-down"></span>
                                        </a>
                                        <ul class="dropdown-menu small-dropdown-menu">

                                            <li>
                                                <a
                                                   href="/wishes/action=getSpecificWish/admin=true/Id={$wish->id}"
                                                   onClick="return popup(this, 'notes',900,400)">
                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                    Wens bekijken
                                                </a>
                                            </li>

                                            <li>
                                                <a data-toggle="modal"
                                                   data-target="#acceptModal{$wish->id}">
                                                    <span class="glyphicon glyphicon-ok"></span>
                                                    Wens accepteren
                                                </a>
                                            </li>

                                            <li>
                                                <a href="/profileoverview/action=viewProfile/user={$wish->user->email}">
                                                    <span class="glyphicon glyphicon-user"></span>
                                                    Gebruiker bekijken
                                                </a>
                                            </li>

                                            <li>
                                                <a href="/AdminWish/action=deleteWish?Id={$wish->id}">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                    Wens verwijderen
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {else}
                    <div class="center-block text-center"><h4>Er zijn momenteel geen geweigerde wensen</h4></div>
                {/if}
            </div>

            {*Deleted Wishes*}

            <div class="tab-pane fade in {if $currentPage == "deleted"}active{/if}" id="deletedTab">
                {if $deleted}
                    <table class="table panel">
                        <thead>
                        <tr>
                            <th>Wish id</th>
                            <th>Gebruiker</th>
                            <th>Wens</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$deleted item=wish}
                            <tr>
                                <td>{$wish->id}</td>
                                <td>{$wish->user->displayName}</td>
                                <td>{$wish->title}</td>
                                <td>{$wish->status}</td>
                                <td>
                                    {include file='View/adminWishModals.tpl'}
                                    <div class="dropdown">
                                        <a href="#"
                                           class="dropdown-toggle"
                                           data-toggle="dropdown"
                                           role="button"
                                           aria-haspopup="true"
                                           aria-expanded="false">
                                            <span class="glyphicon glyphicon-chevron-down"></span>
                                        </a>
                                        <ul class="dropdown-menu small-dropdown-menu">

                                            <li>
                                                <a
                                                   href="/wishes/action=getSpecificWish/admin=true/Id={$wish->id}"
                                                   onClick="return popup(this, 'notes',900,400)">
                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                    Wens bekijken
                                                </a>
                                            </li>

                                            <li>
                                                <a data-toggle="modal"
                                                   data-target="#acceptModal{$wish->id}">
                                                    <span class="glyphicon glyphicon-ok"></span>
                                                    Wens accepteren
                                                </a>
                                            </li>

                                            <li>
                                                <a href="/profileoverview/action=viewProfile/user={$wish->user->email}">
                                                    <span class="glyphicon glyphicon-user"></span>
                                                    Gebruiker bekijken
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {else}
                    <div class="center-block text-center"><h4>Er zijn momenteel geen verwijderde wensen</h4></div>
                {/if}
            </div>
        </div>
    </div>

