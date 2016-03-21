<div class="container">
    <div class="row">

        <div class="col-xs-12 col-md-2 col-sm-2 col-lg-2">
            <h5>Matches overzicht</h5>
            <hr/>

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
                    <th>Plaats</th>
                    <th>Onderwerp</th>
                    <th>Omschrijving</th>
                    <th>Status</th>
                    <th class="smallColumn"></th>
                    {if $currentPage == "mywishes"}
                        <th class="smallColumn"></th>
                    {/if}
                </tr>
                </thead>
                <tbody>
                {if isset($matches)}
                    {foreach from=$wishes item=wish}
                        <tr>
                            <td>{htmlspecialcharsWithNL($wish -> user -> displayName)}</td>
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
                            {/if}
                        </tr>
                    {/foreach}
                {/if}
                </tbody>
            </table>
        </div>
    </div>
</div>