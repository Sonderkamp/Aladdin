<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Max-->
<!-- * Date: 25-Feb-16-->
<!-- * Time: 15:12-->
<!-- */-->

<div class="container">
    <div class="row">
         <span class="hidden-xs hidden-sm  hidden-md info">
                 <a href="/Wishes/action=open_wish">
                     <button type="button" {if !$canAddWish}disabled{/if} class="btn btn-primary">
                         <span class="glyphicon glyphicon-plus"></span>
                     </button>
                 </a>
         </span>

        <div class="col-xs-12 col-md-2 col-sm-2 col-lg-2">
            <h5>Wensen overzicht</h5>
            <hr/>
            <span class="hidden-lg info">
                 <a href="/Wishes/action=open_wish">
                     <button type="button" {if !$canAddWish}disabled{/if} class="btn btn-primary">
                         <span class="glyphicon glyphicon-plus"></span>
                     </button>
                 </a>
            </span>

            <ul class="nav nav-pills nav-stacked">
                <li {if $currentPage == "mywishes"} class="active" {/if}><a href="/wishes/action=mywishes">Mijn wensen</a></li>
                <li {if $currentPage == "incompletedwishes"} class="active" {/if}><a href="/wishes/action=incompletedwishes">Onvervulde wensen</a></li>
                <li {if $currentPage == "completedwishes"} class="active" {/if}><a href="/wishes/action=completedwishes">Vervulde wensen</a></li>
            </ul>

        </div>

        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">

            {if isset($wishError)}
                <div class="form-error" id="err">Error: {htmlspecialchars($wishError)}</div>
            {else}
                <div id="err"></div>
            {/if}


            <form action="/wishes/search" method="get">
                <div class="row">
                    <div class="col-lg-10">
                        <input class="form-control" name="search" placeholder="Zoek een wens">
                    </div>
                    <div class="col-lg-2">
                        <button type="submit" class="btn btn-primary">Zoek</button>
                    </div>
                </div>
            </form>



            <table class="table">
                <thead>
                <tr>
                    <th>Gebruiker</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th class="smallColumn"></th>
                    <th class="smallColumn"></th>
                    <th class="smallColumn"></th>
                </tr>
                </thead>
                <tbody>
                {foreach from=$wishes item=wish}
                    <tr>
                        <td>{$wish -> user}</td>
                        <td>{$wish -> title}</td>
                        <td>{$wish -> content}</td>
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