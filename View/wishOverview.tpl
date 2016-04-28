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
            {foreach from=$wishes item=wish}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="noMargin">{htmlspecialcharsWithNL($wish -> title)}</h3>
                    </div>
                    <div class="panel-body">

                        <div class="row">


                            <div class="col-xs-9">
                                <p>{htmlspecialcharsWithNL($wish -> content)}</p>
                            </div>

                            <div class="col-xs-3 right">
                                <div class="dropdown">


                                    {if $currentPage == "mywishes"}
                                        {htmlspecialcharsWithNL($wish -> user -> displayName)}
                                    {else}
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                           aria-haspopup="true"
                                           aria-expanded="false"><span class="glyphicon glyphicon-user"></span>
                                            {htmlspecialcharsWithNL($wish -> user -> displayName)}</span><span
                                                    class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="/report/action=report/wish_id={$wish->id}">Rapporteren</a></li>
                                        </ul>
                                    {/if}


                                </div>


                                Stad: {htmlspecialcharsWithNL($wish -> user -> city)}<br>
                                {htmlspecialcharsWithNL($wish -> status)}
                            </div>

                        </div>
                    </div>
                    <div class="panel-footer">

                        <form class='noPadding' method="post">
                            <button class="btn btn-default"
                                    formaction="/Wishes/wish_id={$wish->id}"
                                    value="{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}"
                                    type="submit"
                                    name="page">
                                <span class="glyphicon glyphicon-menu-right"/>
                            </button>
                        </form>
                        {if $currentPage == "mywishes"}
                            <form class='noPadding' action="/Wishes/action=open_edit_wish" method="get">
                                <button name="editwishbtn" value="{$wish -> id}" type="sumbit"
                                        class="btn btn-inbox" data-toggle="modal">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                            </form>
                            <a class="btn btn-danger info" href="/Wishes/action=remove/wishID={$wish->id}">
                                <span class="glyphicon glyphicon-trash"></span>
                            </a>
                        {/if}
                    </div>


                </div>
            {/foreach}

        </div>
    </div>
</div>