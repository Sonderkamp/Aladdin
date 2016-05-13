<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: simon-->
<!-- * Date: 8-3-2016-->
<!-- * Time: 17:51-->
<!-- */-->


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

    {* <form action="/AdminWish/action=accept" method="post">
                                <td>{$i.display}</td>
                                <td>{$i.title|escape:"html"}</td>
                                <input type="hidden" value={$i.wishid} name="wishid">
                                <input type="hidden" value={$i.user} name="user">
                                <input type="hidden" value={$i.mdate|replace:' ':'%20'} name="mdate" step="1">
                                <input type="hidden" value={$i.title|escape:"html"} name="wishtitle">
                                <input type="hidden" value={$i.display|escape:"html"} name="wishdisplay">
                                <input type="hidden" value={$i.content|escape:"html"} name="wishcontent">
                                <input type="hidden" value={$current_page} name="page">
                                <td>
                                    <button type="button" class="btn btn-sm"
                                            data-toggle="modal"
                                            data-title="{$i.title|escape:"html"}"
                                            data-content="{$i.content|escape:"html"}"
                                            data-owner="{$i.display}"
                                            data-status="{$i.status}"
                                            data-date="{$i.mdate}"
                                            data-target="#checkModal">
                                        <span class="glyphicon glyphicon-eye-open"></span>
                                    </button>
                                </td>

                                <td>
                                    <button type="submit" class="btn btn-sm" formaction="/AdminWish/action=accept"><span
                                                class="glyphicon glyphicon glyphicon-ok"></span></button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm"
                                            data-toggle="modal"
                                            data-id="{$i.wishid}"
                                            data-title="{$i.title|escape:"html"}"
                                            data-content="{$i.content|escape:"html"}"
                                            data-owner="{$i.display}"
                                            data-user="{$i.user}"
                                            data-mdate="{$i.mdate|replace:' ':'%20'}"
                                            data-target="#denyModal">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm"
                                            data-toggle="modal"
                                            data-display="{$i.display}"
                                            data-email="{$i.user}"
                                            data-address="{$i.address}"
                                            data-postalcode="{$i.postalcode}"
                                            data-country="{$i.ucountry}"
                                            data-city="{$i.ucity}"
                                            data-target="#profileModal">
                                        <span class="glyphicon glyphicon glyphicon-user"></span>
                                    </button>
                                </td>
                            </form> *}


    <div class="col-md-10">
        <span class="info">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">
                <span class="glyphicon glyphicon-info-sign"></span>
            </button>
        </span>
        <div class="tab-content">

            {*Requested Wishes*}

            <div class="tab-pane fade in active" id="requestedTab">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Gebruiker</th>
                        <th>Wens</th>
                        <th>Status</th>
                        {*<th width="1%"></th>*}
                        {*<th width="1%"></th>*}
                        {*<th width="1%"></th>*}
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$requested item=wish}
                        <tr>
                            <td>{$wish->user->displayName}</td>
                            <td>{$wish->title}</td>
                            <td>{$wish->status}</td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>

            {*Published Wishes*}

            <div class="tab-pane fade in" id="publishedTab">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Gebruiker</th>
                        <th>Wens</th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$requested item=wish}
                        <tr>

                            <td>{$wish->title}</td>

                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>

            {*Matched Wishes*}

            <div class="tab-pane fade in" id="matchedTab">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Gebruiker</th>
                        <th>Wens</th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$requested item=wish}
                        <tr>

                            <td>{$wish->title}</td>

                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>

            {*Current Wishes*}

            <div class="tab-pane fade in" id="currentTab">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Gebruiker</th>
                        <th>Wens</th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$requested item=wish}
                        <tr>

                            <td>{$wish->title}</td>

                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>

            {*Completed Wishes*}

            <div class="tab-pane fade in" id="completedTab">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Gebruiker</th>
                        <th>Wens</th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$requested item=wish}
                        <tr>

                            <td>{$wish->title}</td>

                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>

            {*Denied Wishes*}

            <div class="tab-pane fade in" id="deniedTab">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Gebruiker</th>
                        <th>Wens</th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$requested item=wish}
                        <tr>

                            <td>{$wish->title}</td>

                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>

            {*Deleted Wishes*}

            <div class="tab-pane fade in" id="deletedTab">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Gebruiker</th>
                        <th>Wens</th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$requested item=wish}
                        <tr>

                            <td>{$wish->title}</td>

                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

