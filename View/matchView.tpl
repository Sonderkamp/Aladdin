<img src="/Resources/Images/banner.jpg" class="img-responsive width background">
<div class="container">
    <div class="row">

        <div class="col-xs-12 col-md-2 col-sm-2 col-lg-2">
            <h5>Matches overzicht</h5>

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


            <table class="table">
                <thead>
                <tr>
                    <th>Gebruiker</th>
                    <th>Wens</th>
                    <th>Beschrijving</th>
                </tr>
                </thead>
                <tbody>
                {if isset($possibleMatches)}
                    {foreach from=$possibleMatches item=wish}
                        <tr>
                            <td>
                                <div class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                           aria-haspopup="true"
                                           aria-expanded="false"><span class="glyphicon glyphicon-user"></span>
                                            {htmlspecialcharsWithNL($wish -> getUser() -> getDisplayName())}</span><span
                                                    class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="/report/action=report/wish_id={$wish->getId()}">Rapporteren</a></li>
                                        </ul>
                                </div>

                            </td>
                            <td>{htmlspecialcharsWithNL($wish -> getUser() -> getDisplayName())}</td>
                            <td>{htmlspecialcharsWithNL($wish -> getTitle())}</td>
                            <td>{htmlspecialcharsWithNL($wish -> getContent())}</td>
                            <td>
                                <form method="post">
                                    <button class="btn btn-default"
                                            formaction="/Wishes/wish_id={$wish->getId()}"
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
                                        <button name="editwishbtn" value="{$wish -> getId()}" type="sumbit"
                                                class="btn btn-inbox" data-toggle="modal">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </button>
                                    </form>
                                </td>
                            {/if}
                        </tr>
                    {/foreach}
                {/if}
                </tbody>
            </table>
        </div>
    </div>
</div>

