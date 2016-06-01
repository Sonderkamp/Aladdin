<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Simon / Max-->
<!-- * Date: 8-3-2016 Rewritten on: 14-05-2016-->
<!-- */-->

<div class="container">

    <h5>Wensen Beheer</h5>
    <div class="col-md-2">
        <ul class="nav nav-pills nav-stacked">
            <li {if $currentPage == "requested"}class="active"{/if}>
                <a href="#requestedTab" data-toggle="tab">Aangevraagde wensen</a>
            </li>

            <li {if $currentPage == "published"}class="active"{/if}>
                <a href="#publishedTab" data-toggle="tab">Gepubliseerde wensen</a>
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
                    <table class="table">
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
                                    {include file='view/adminWishModals.tpl'}
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
                                                <form action="/wishes/action=getSpecificWish">
                                                    <input type="hidden" name="admin" value="true">
                                                    <input type="hidden" name="Id" value="{$wish->id}">
                                                    <button type="submit" class="btn btn-sm">
                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                    </button>
                                                </form>
                                                {*<a href="/wishes/action=getSpecificWish?Id={$wish->id}" class="btn btn-sm">*}
                                                    {*<span class="glyphicon glyphicon-eye-open"></span>*}
                                                {*</a>*}
                                            </li>

                                            <li>
                                                <a href="/AdminWish/action=acceptWish?Id={$wish->id}" class="btn btn-sm">
                                                    <span class="glyphicon glyphicon-ok"></span>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="/AdminWish/action=refuseWish?Id={$wish->id}" class="btn btn-sm">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </a>
                                            </li>

                                            <li>
                                                <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#profileModal{$wish->id}">
                                                    <span class="glyphicon glyphicon-user"></span>
                                                </button>
                                            </li>

                                            <li>
                                                <a href="/AdminWish/action=deleteWish?Id={$wish->id}" class="btn btn-sm">
                                                    <span class="glyphicon glyphicon-trash"></span>
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
                    <table class="table">
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
                                    {include file='view/adminWishModals.tpl'}
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
                                                <form action="/wishes/action=getSpecificWish">
                                                    <input type="hidden" name="admin" value="true">
                                                    <input type="hidden" name="Id" value="{$wish->id}">
                                                    <button type="submit" class="btn btn-sm">
                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                    </button>
                                                </form>
                                                {*<a href="/wishes/action=getSpecificWish?Id={$wish->id}" class="btn btn-sm">*}
                                                {*<span class="glyphicon glyphicon-eye-open"></span>*}
                                                {*</a>*}
                                            </li>

                                            <li>
                                                <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#profileModal{$wish->id}">
                                                    <span class="glyphicon glyphicon-user"></span>
                                                </button>
                                            </li>

                                            <li>
                                                <a href="/AdminWish/action=deleteWish?Id={$wish->id}" class="btn btn-sm">
                                                    <span class="glyphicon glyphicon-trash"></span>
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
                    <div class="center-block text-center"><h4>Er zijn momenteel geen gepubliseerde wensen</h4></div>
                {/if}
            </div>

            {*Matched Wishes*}

            <div class="tab-pane fade in {if $currentPage == "matched"}active{/if}" id="matchedTab">
                {if $matched}
                    <table class="table">
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
                                    {include file='view/adminWishModals.tpl'}
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
                                                <form action="/wishes/action=getSpecificWish">
                                                    <input type="hidden" name="admin" value="true">
                                                    <input type="hidden" name="Id" value="{$wish->id}">
                                                    <button type="submit" class="btn btn-sm">
                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                    </button>
                                                </form>
                                                {*<a href="/wishes/action=getSpecificWish?Id={$wish->id}" class="btn btn-sm">*}
                                                {*<span class="glyphicon glyphicon-eye-open"></span>*}
                                                {*</a>*}
                                            </li>

                                            <li>
                                                <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#profileModal{$wish->id}">
                                                    <span class="glyphicon glyphicon-user"></span>
                                                </button>
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
                    <table class="table">
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
                                    {include file='view/adminWishModals.tpl'}
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
                                                <form action="/wishes/action=getSpecificWish">
                                                    <input type="hidden" name="admin" value="true">
                                                    <input type="hidden" name="Id" value="{$wish->id}">
                                                    <button type="submit" class="btn btn-sm">
                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                    </button>
                                                </form>
                                                {*<a href="/wishes/action=getSpecificWish?Id={$wish->id}" class="btn btn-sm">*}
                                                {*<span class="glyphicon glyphicon-eye-open"></span>*}
                                                {*</a>*}
                                            </li>

                                            <li>
                                                <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#profileModal{$wish->id}">
                                                    <span class="glyphicon glyphicon-user"></span>
                                                </button>
                                            </li>

                                            <li>
                                                <a href="/AdminWish/action=deleteWish?Id={$wish->id}" class="btn btn-sm">
                                                    <span class="glyphicon glyphicon-trash"></span>
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
                    <table class="table">
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
                                    {include file='view/adminWishModals.tpl'}
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
                                                <form action="/wishes/action=getSpecificWish">
                                                    <input type="hidden" name="admin" value="true">
                                                    <input type="hidden" name="Id" value="{$wish->id}">
                                                    <button type="submit" class="btn btn-sm">
                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                    </button>
                                                </form>
                                                {*<a href="/wishes/action=getSpecificWish?Id={$wish->id}" class="btn btn-sm">*}
                                                {*<span class="glyphicon glyphicon-eye-open"></span>*}
                                                {*</a>*}
                                            </li>

                                            <li>
                                                <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#profileModal{$wish->id}">
                                                    <span class="glyphicon glyphicon-user"></span>
                                                </button>
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
                    <table class="table">
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
                                    {include file='view/adminWishModals.tpl'}
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
                                                <form action="/wishes/action=getSpecificWish">
                                                    <input type="hidden" name="admin" value="true">
                                                    <input type="hidden" name="Id" value="{$wish->id}">
                                                    <button type="submit" class="btn btn-sm">
                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                    </button>
                                                </form>
                                                {*<a href="/wishes/action=getSpecificWish?Id={$wish->id}" class="btn btn-sm">*}
                                                {*<span class="glyphicon glyphicon-eye-open"></span>*}
                                                {*</a>*}
                                            </li>

                                            <li>
                                                <a href="/AdminWish/action=acceptWish?Id={$wish->id}" class="btn btn-sm">
                                                    <span class="glyphicon glyphicon-ok"></span>
                                                </a>
                                            </li>

                                            <li>
                                                <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#profileModal{$wish->id}">
                                                    <span class="glyphicon glyphicon-user"></span>
                                                </button>
                                            </li>

                                            <li>
                                                <a href="/AdminWish/action=deleteWish?Id={$wish->id}" class="btn btn-sm">
                                                    <span class="glyphicon glyphicon-trash"></span>
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
                    <table class="table">
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
                                    {include file='view/adminWishModals.tpl'}
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
                                                <form action="/wishes/action=getSpecificWish">
                                                    <input type="hidden" name="admin" value="true">
                                                    <input type="hidden" name="Id" value="{$wish->id}">
                                                    <button type="submit" class="btn btn-sm">
                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                    </button>
                                                </form>
                                                {*<a href="/wishes/action=getSpecificWish?Id={$wish->id}" class="btn btn-sm">*}
                                                {*<span class="glyphicon glyphicon-eye-open"></span>*}
                                                {*</a>*}
                                            </li>

                                            <li>
                                                <a href="/AdminWish/action=acceptWish?Id={$wish->id}" class="btn btn-sm">
                                                    <span class="glyphicon glyphicon-ok"></span>
                                                </a>
                                            </li>

                                            <li>
                                                <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#profileModal{$wish->id}">
                                                    <span class="glyphicon glyphicon-user"></span>
                                                </button>
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

