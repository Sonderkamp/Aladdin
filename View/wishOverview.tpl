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
            <hr/>

            <ul class="nav nav-pills nav-stacked">
                <li {if $currentPage == "mywishes"} class="active" {/if}><a href="/wishes/action=mywishes">Mijn
                        wensen</a></li>
                <li {if $currentPage == "incompletedwishes"} class="active" {/if}><a
                            href="/wishes/action=incompletedwishes">Onvervulde wensen</a></li>
                <li {if $currentPage == "completedwishes"} class="active" {/if}><a
                            href="/wishes/action=completedwishes">Vervulde wensen</a></li>
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
                    <th>Onderwerp</th>
                    <th>Omschrijving</th>
                    <th>Status</th>
                    <th class="smallColumn"></th>
                    <th class="smallColumn"></th>
                    <th class="smallColumn"></th>
                </tr>
                </thead>
                <tbody>
                {foreach from=$wishes item=wish}
                    <tr>
                        <td>{htmlspecialchars($wish -> userDisplayName)}</td>
                        <td>{htmlspecialchars($wish -> userCity)}</td>
                        <td>{htmlspecialchars($wish -> title)}</td>
                        <td>{htmlspecialchars($wish -> content)}</td>
                        <td>{htmlspecialchars($wish -> status)}</td>
                        <td>
                            <form method="post">
                                <button class="btn btn-default"
                                        formaction="/Wishes/wish_id={$wish->id}"
                                        value="{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}"
                                        type="submit"
                                        name="page">
                                    <span class="glyphicon glyphicon-eye-open"/>
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action="/Wishes/action=open_edit_wish" method="get">
                                <button name="editwishbtn" value="{$wish -> id}" type="sumbit"
                                        class="btn btn-inbox btn-sm" data-toggle="modal">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>