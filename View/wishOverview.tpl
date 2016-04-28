<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Max-->
<!-- * Date: 25-Feb-16-->
<!-- * Time: 15:12-->
<!-- */-->

<div class="container">
    <div class="row">

        <div class="col-xs-12 col-md-2 col-sm-2 col-lg-2">
            <h5>Wensen overzicht</h5>
            {*<hr/>*}

            <ul class="nav nav-pills nav-stacked">
                <li {if $currentPage == "mywishes"} class="active" {/if}><a href="/wishes/action=mywishes">Mijn
                        wensen</a></li>
                <li {if $currentPage == "incompletedwishes"} class="active" {/if}><a
                            href="/wishes/action=incompletedwishes">Onvervulde wensen</a></li>
                <li {if $currentPage == "completedwishes"} class="active" {/if}><a
                            href="/wishes/action=completedwishes">Vervulde wensen</a></li>
                <li {if $currentPage == "match"} class="active" {/if}><a
                            href="/match/action=open_match_view">Mogelijke matches</a></li>
            </ul>

        </div>

        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">

            {if isset($wishError)}
                <div class="form-error" id="err">Error: {htmlspecialchars($wishError)}</div>
            {else}
                <div id="err"></div>
            {/if}


            <div class="row">
                <form action="/wishes/search" method="get">
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                        <input class="form-control" name="search" placeholder="Zoek een wens">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <button type="submit" class="btn btn-primary">Zoek</button>
                    </div>
                </form>
                <span class="col-lg-2 col-md-2 col-sm-2 col-xs-2 info">
                 <a href="/Wishes/action=open_wish">
                     <button type="button" {if !$canAddWish}disabled{/if} class="btn btn-primary">
                         <span class="glyphicon glyphicon-plus"></span>
                     </button>
                 </a>
                </span>
            </div>


            <table class="table">
                <thead>
                <tr>
                    <th>Gebruiker</th>
                    <th>Plaats</th>
                    <th>Onderwerp</th>
                    <th>Omschrijving</th>
                    <th>Status</th>
                    <th class="smallColumn"></th>
                    {if $currentPage == "mywishes"}
                        <th class="smallColumn"></th>
                    {/if}
                    {if $currentPage == "mywishes"}
                        <th class="smallColumn"></th>
                    {/if}
                </tr>
                </thead>
                <tbody>
                {foreach from=$wishes item=wish}
                    <tr>
                        <td>
                            <div class="dropdown">
                                {if $currentPage == "mywishes"}
                                    {htmlspecialcharsWithNL($wish -> user -> displayName)}
                                {else}
                                    {if isset($displayName)}
                                        {if ($wish -> user -> displayName) != $displayName}
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                               aria-haspopup="true"
                                               aria-expanded="false"><span class="glyphicon glyphicon-user"></span>
                                                {htmlspecialcharsWithNL($wish -> user -> displayName)}</span><span
                                                        class="caret"></span></a>
                                            <ul class="dropdown-menu">
                                                {*<li><a href="/report/action=report/wish_id={$wish->id}">Rapporteren</a></li>*}
                                                <li>
                                                    <a  data-toggle="modal" data-target="#myModal{preg_replace('/\s+/', '', $wish->id)}">
                                                        Rapporteren
                                                    </a>
                                                </li>
                                            </ul>
                                            {else}
                                            <span class="glyphicon glyphicon-user"></span>
                                            <a>{$wish -> user -> displayName}</a>
                                        {/if}
                                    {/if}
                                {/if}
                            </div>

                        </td>
                        {*<td>{htmlspecialcharsWithNL($wish -> user -> displayName)}</td>*}
                        <td>{htmlspecialcharsWithNL($wish -> user -> city)}</td>
                        <td>{htmlspecialcharsWithNL($wish -> title)}</td>
                        <td>{htmlspecialcharsWithNL($wish -> content)}</td>
                        <td>{htmlspecialcharsWithNL($wish -> status)}</td>
                        <td>
                            <form method="post">
                                <button class="btn btn-default"
                                        formaction="/Wishes/wish_id={$wish->id}"
                                        value="{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}"
                                        type="submit"
                                        name="page">
                                    <span class="glyphicon glyphicon-menu-right"/>
                                </button>
                            </form>
                        </td>
                        {if $currentPage == "mywishes"}
                            <td>
                                <form action="/Wishes/action=open_edit_wish" method="get">
                                    <button name="editwishbtn" value="{$wish -> id}" type="sumbit"
                                            class="btn btn-inbox" data-toggle="modal">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <a class="btn btn-danger" href="/Wishes/action=remove/wishID={$wish->id}">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </a>
                            </td>
                        {/if}


                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal deny request-->
{foreach from=$wishes item=wish}
    <div id="myModal{preg_replace('/\s+/', '', $wish->id)}" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Rapporteren van gebruiker <span class="glyphicon glyphicon-user"></span>{htmlspecialcharsWithNL($wish -> user -> displayName)}</h4>
                </div>
                <form action="/report/action=report" method="post">
                    <div class="modal-body">

                        <div class="form-group">
                            <p>
                            <div class="col-xs-3">
                                Reden:
                            </div>
                            <div class="col-xs-9">
                                <input type="hidden" value="{$wish->id}" name="wish_id"/>
                                <input type="text" class="form-control" placeholder="Reden dat u {{htmlspecialcharsWithNL($wish -> user -> displayName)}} wilt rappoteren" name="report_message">
                            </div>
                            </p>
                            <br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Annuleren</button>
                        <button type="submit" name="submit" class="btn btn-inbox info">
                            <span class="glyphicon glyphicon-remove"></span> Bevestigen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
{/foreach}