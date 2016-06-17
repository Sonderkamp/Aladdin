<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Max-->
<!-- * Date: 25-Feb-16-->
<!-- * Time: 15:12-->
<!-- */-->

<div class="container">
    <div class="row">
        <h3>Wensen overzicht</h3>
        {if isset($wishError)}
            <div class="form-error" id="err">Error: {htmlspecialchars($wishError)}</div>
        {else}
            <div id="err"></div>
        {/if}
        <div class="col-xs-12 col-md-4 col-sm-2 col-lg-2">
            <ul class="nav nav-pills nav-stacked">
                <li {if $currentPage == "myWishes"} class="active" {/if}>
                    <a href="#myWishes" data-toggle="tab">Mijn wensen</a>
                </li>

                <li {if $currentPage == "incompletedWishes"} class="active" {/if}>
                    <a href="#incompletedWishes" data-toggle="tab">Onvervulde wensen</a>
                </li>

                <li {if $currentPage == "completedWishes"} class="active" {/if}>
                    <a href="#completedWishes" data-toggle="tab">Vervulde wensen</a>
                </li>

                <li {if $currentPage == "myCompletedWishes"} class="active" {/if}>
                    <a href="#myCompletedWishes" data-toggle="tab">Mijn vervulde wensen</a>
                </li>

                <li {if $currentPage == "matchedWishes"} class="active" {/if}>
                    <a href="#matchedWishes" data-toggle="tab">Mogelijke matches</a>
                </li>
            </ul>
        </div>

        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
            <div class="row">
                <form class="col-xs-10 row" action="/wishes/search" method="get">
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                        <input class="form-control" name="search" placeholder="Zoek een wens">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <button type="submit" class="btn btn-primary">Zoek</button>
                    </div>
                </form>

                <form class="col-xs-2 info" action="/wishes/action=openAddView">
                    <button type="submit" {if !$canAddWish}disabled{/if} class="btn btn-primary">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </form>
            </div>
            <br>
            <div class="tab-content">
                <div class="tab-pane fade in {if $currentPage == "myWishes"}active{/if}" id="myWishes">
                    {if $myWishes}
                        {foreach from=$myWishes item=wish}
                            <div class="panel panel-default">

                                <div class="panel-heading">
                                    <a href="/Wishes/action=getSpecificWish?Id={$wish->id}"
                                       class="h3">{htmlspecialcharsWithNL($wish -> title)}</a>
                                </div>

                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-9">
                                            <p>{htmlspecialcharsWithNL($wish -> content)}</p>
                                        </div>
                                        {include file='view/wishOverviewModals.tpl'}
                                    </div>
                                </div>

                                <div class="panel-footer right">

                                    {if {htmlspecialcharsWithNL($wish -> status) != "Geweigerd"}}
                                        <a href="/wishes/action=openEditView?Id={$wish->id}"
                                           class="btn btn-inbox infoLeft">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
                                    {/if}
                                    <a href="/Wishes/action=remove?Id={$wish->id}" class="btn btn-danger infoLeft">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                    <a href="/wishes/action=getSpecificWish?Id={$wish->id}" class="btn btn-default">
                                        <span class="glyphicon glyphicon-menu-right"></span>
                                    </a>
                                </div>
                            </div>
                        {/foreach}
                    {else}
                        <div class="center-block text-center"><h4>U heeft momenteel geen wensenn</h4></div>
                    {/if}
                </div>

                <div class="tab-pane fade in {if $currentPage == "incompletedWishes"}active{/if}"
                     id="incompletedWishes">
                    {if $incompletedWishes}
                        {foreach from=$incompletedWishes item=wish}
                            <div class="panel panel-default">

                                <div class="panel-heading">
                                    <a href="/Wishes/action=getSpecificWish?Id={$wish->id}"
                                       class="h3">{htmlspecialcharsWithNL($wish -> title)}</a>
                                </div>

                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-9">
                                            <p>{htmlspecialcharsWithNL($wish -> content)}</p>
                                        </div>
                                        {include file='view/wishOverviewModals.tpl'}
                                    </div>
                                </div>

                                <div class="panel-footer right">
                                    <a href="/wishes/action=getSpecificWish?Id={$wish->id}" class="btn btn-default">
                                        <span class="glyphicon glyphicon-menu-right"></span>
                                    </a>
                                </div>
                            </div>
                        {/foreach}
                    {else}
                        <div class="center-block text-center"><h4>Er zijn momenteel geen onvervulde wensen</h4></div>
                    {/if}
                </div>

                <div class="tab-pane fade in {if $currentPage == "completedWishes"}active{/if}" id="completedWishes">
                    {if $completedWishes}
                        {foreach from=$completedWishes item=wish}
                            <div class="panel panel-default">

                                <div class="panel-heading">
                                    <a href="/Wishes/action=getSpecificWish?Id={$wish->id}"
                                       class="h3">{htmlspecialcharsWithNL($wish -> title)}</a>
                                </div>

                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-9">
                                            <p>{htmlspecialcharsWithNL($wish -> content)}</p>
                                        </div>
                                        {include file='view/wishOverviewModals.tpl'}
                                    </div>
                                </div>

                                <div class="panel-footer right">
                                    <a href="/wishes/action=getSpecificWish?Id={$wish->id}" class="btn btn-default">
                                        <span class="glyphicon glyphicon-menu-right"></span>
                                    </a>
                                </div>
                            </div>
                        {/foreach}
                    {else}
                        <div class="center-block text-center"><h4>Er zijn momenteel geen vervulde wensen</h4></div>
                    {/if}
                </div>

                <div class="tab-pane fade in {if $currentPage == "myCompletedWishes"}active{/if}" id="myCompletedWishes">
                    {if $myCompletedWishes}
                        {foreach from=$myCompletedWishes item=wish}
                            <div class="panel panel-default">

                                <div class="panel-heading">
                                    <a href="/Wishes/action=getSpecificWish?Id={$wish->id}"
                                       class="h3">{htmlspecialcharsWithNL($wish -> title)}</a>
                                </div>

                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-9">
                                            <p>{htmlspecialcharsWithNL($wish -> content)}</p>
                                        </div>
                                        {include file='view/wishOverviewModals.tpl'}
                                    </div>
                                </div>

                                <div class="panel-footer right">
                                    <a href="/wishes/action=getSpecificWish?Id={$wish->id}" class="btn btn-default">
                                        <span class="glyphicon glyphicon-menu-right"></span>
                                    </a>
                                </div>
                            </div>
                        {/foreach}
                    {else}
                        <div class="center-block text-center"><h4>U heeft momenteel geen vervulde wensen</h4></div>
                    {/if}
                </div>

                <div class="tab-pane fade in {if $currentPage == "matchedWishes"}active{/if}" id="matchedWishes">
                    {if $matchedWishes}
                        {foreach from=$matchedWishes item=wish}
                            <div class="panel panel-default">

                                <div class="panel-heading">
                                    <a href="/Wishes/action=getSpecificWish?Id={$wish->id}"
                                       class="h3">{htmlspecialcharsWithNL($wish -> title)}</a>
                                </div>

                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-9">
                                            <p>{htmlspecialcharsWithNL($wish -> content)}</p>
                                        </div>
                                        {include file='view/wishOverviewModals.tpl'}
                                    </div>
                                </div>

                                <div class="panel-footer right">
                                    <a href="/wishes/action=getSpecificWish?Id={$wish->id}" class="btn btn-default">
                                        <span class="glyphicon glyphicon-menu-right"></span>
                                    </a>
                                </div>
                            </div>
                        {/foreach}
                    {else}
                        <div class="center-block text-center"><h4>Er zijn momenteel geen mogelijke matches</h4></div>
                    {/if}
                </div>
            </div>
        </div>
    </div>
</div>