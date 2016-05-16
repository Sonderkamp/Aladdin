<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Max-->
<!-- * Date: 25-Feb-16-->
<!-- * Time: 15:12-->
<!-- */-->

<div class="container">
    <div class="row">
        <h3>Wensen overzicht</h3>
        <div class="col-xs-12 col-md-2 col-sm-2 col-lg-2">

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
            <br>
            {if $wishes}
                {foreach from=$wishes item=wish}
                    <div class="panel panel-default">
                        <div class="panel-heading">

                            <a href="/Wishes/wish_id={$wish->id}"
                               class="h3">{htmlspecialcharsWithNL($wish -> title)}</a>
                        </div>
                        <div class="panel-body">

                            <div class="row">


                                <div class="col-xs-9">
                                    <p>{htmlspecialcharsWithNL($wish -> content)}</p>
                                </div>

                                <div class="col-xs-3 right">
                                    <div class="dropdown">
                                        {if $currentPage == "mywishes"}
                                            <span class="glyphicon glyphicon-user"></span>
                                            {htmlspecialcharsWithNL($wish -> displayName)}
                                        {else}
                                            {if isset($displayName)}
                                                {if ($wish -> displayName) != $displayName}
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                                                       role="button"
                                                       aria-haspopup="true"
                                                       aria-expanded="false"><span
                                                                class="glyphicon glyphicon-user"></span>
                                                        {htmlspecialcharsWithNL($wish -> displayName)}</span><span
                                                                class="caret"></span></a>
                                                    {if in_array(($wish -> displayName),$reported)}
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a>
                                                                    U heeft deze gebruiker gerapporteerd.
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    {else}
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a data-toggle="modal"
                                                                   data-target="#myModal{preg_replace('/\s+/', '', $wish->id)}">
                                                                    Rapporteren
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    {/if}
                                                {else}
                                                    <a class="dropdown-toggle" data-toggle="dropdown" role="button"
                                                       aria-haspopup="true"
                                                       aria-expanded="false"><span
                                                                class="glyphicon glyphicon-user"></span>
                                                        {htmlspecialcharsWithNL($wish -> displayName)}</span></a>
                                                {/if}
                                            {/if}
                                        {/if}

                                    </div>
                                    Stad: <b>{htmlspecialcharsWithNL($wish -> city)}</b><br>
                                    Status: <b>{htmlspecialcharsWithNL($wish -> status)}</b>
                                </div>

                            </div>
                        </div>
                        <div class="panel-footer right">

                            {if $currentPage == "mywishes"}
                                {if {htmlspecialcharsWithNL($wish -> status) != "Geweigerd"}}
                                    <form class='noPadding infoLeft' action="/Wishes/action=open_edit_wish"
                                          method="get">
                                        <button name="editwishbtn" value="{$wish -> id}" type="sumbit"
                                                class="btn btn-inbox" data-toggle="modal">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </button>
                                    </form>
                                {/if}
                                <a class="btn btn-danger infoLeft margLeft"
                                   href="/Wishes/action=remove/wishID={$wish->id}">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </a>
                            {/if}

                            <form class='noPadding' method="post">
                                <button class="btn btn-default"
                                        formaction="/Wishes/wish_id={$wish->id}"
                                        value="{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}"
                                        type="submit"
                                        name="page">
                                    <span class="glyphicon glyphicon-menu-right"></span>
                                </button>
                            </form>

                        </div>


                    </div>
                {/foreach}
            {else}
                <div class="center-block text-center"><h4>Er zijn momenteel geen wensen</h4></div>
            {/if}
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
                    <h4 class="modal-title">Rapporteren van gebruiker <span
                                class="glyphicon glyphicon-user"></span>{htmlspecialcharsWithNL($wish -> displayName)}
                    </h4>
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
                                <input type="text" class="form-control"
                                       placeholder="Reden dat u {{htmlspecialcharsWithNL($wish -> displayName)}} wilt rappoteren"
                                       name="report_message">
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