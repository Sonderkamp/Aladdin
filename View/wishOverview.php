<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Max-->
<!-- * Date: 25-Feb-16-->
<!-- * Time: 15:12-->
<!-- */-->
<!-- TODO *************************** -->
<!-- TODO  BALK HEFT EEN WIDTH KUT -->
<!-- TODO ***************************-->
<div class="container">
    <div class="row">
         <span class="hidden-xs hidden-sm  hidden-md info">
                <a>
                    {if isset($isset)}
                    <button type="button" class="btn btn-primary side-button" disabled data-toggle="button">
                </a>
                        {else}
                        <a href="/Wishes/action=open_wish">
                            <button type="button" class="btn btn-primary side-button">
                                {/if}
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </a>
            </span>


        <div class="col-xs-12 col-lg-2">
            <h5>Wensen overzicht</h5>
            <hr/>
             <span class="  hidden-lg info">
                <a>
                    {if isset($isset)}
                    <button type="button" class="btn btn-primary side-button" disabled="disabled">
                </a>
                    {else}
                    <a href="/Wishes/action=open_wish">
                        <button type="button" class="btn btn-primary side-button">
                            {/if}
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </a>
            </span>

            <table>
                <tr>
                    <td>
                        <a href="/Wishes/action=mywishes">
                            <button type="button" class="btn btn-primary side-button">
                                <span class="glyphicon glyphicon-align-justify"></span> Mijn wensen
                            </button>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>
                        <a href="/Wishes/action=incompletedwishes">
                            <button type="button" class="btn btn-default side-button">
                                <span class="glyphicon glyphicon-remove"></span> Onvervulde wensen
                            </button>
                        </a>
                    </td>
                </tr

                <tr>
                    <td>
                        <a href="/Wishes/action=completedwishes">
                            <button type="button" class="btn btn-default side-button">
                                <span class="glyphicon glyphicon-ok"></span> Vervulde wensen
                            </button>
                        </a>
                    </td>
                </tr>


            </table>
        </div>
        <div class="col-lg-10">

            {if isset($wishError)}
            <div class="form-error" id="err">Error: {htmlspecialchars($wishError)}</div>
            {else}
            <div id="err"></div>
            {/if}

            <form action="/wishes/action=searchWish" method="get">

                <input class="form-control" name="title" placeholder="Wat is uw wens" value="Zoek een wens">

                <button type="submit" class="btn btn-primary">
                    Zoek
                </button>

            </form>

            <table class="table">
                <thead>
                <tr>
                    <th>Gebruiker</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                {foreach from=$wishes item=wish}
                <!--<<<<<<< HEAD-->
                <!--                <tr>-->
                <!--                    <td>{$wish -> user}</td>-->
                <!--                    <td>{$wish -> title}</td>-->
                <!--                    <td>{$wish -> country}</td>-->
                <!--                    <td>{$wish -> city}</td>-->
                <!--                    <td>{$wish -> content}</td>-->
                <!--                    <td>-->
                <!--                        <form action="/Wishes/action=open_edit_wish" method="get">-->
                <!--                            <a>-->
                <!--                                <button name="editwishbtn" value="{$wish -> id}" type="sumbit"-->
                <!--                                        class="btn btn-inbox btn-sm" data-toggle="modal">-->
                <!--                                    <span class="glyphicon glyphicon-edit"></span>-->
                <!--=======-->
                <tr>
                    <td>{$wish -> user}</td>
                    <td>{$wish -> title}</td>
                    <td>{$wish -> content}</td>
                    <td>
                        <a href="/Wishes/wish_id={$wish->id}">
                            <button class="btn btn-default">
                                    <span class="glyphicon glyphicon-eye-open">
                                    </span>
                                <!--                                >>>>>>> 55b03be8450bc299653419b88ffbae238d319cf9-->
                            </button>
                        </a>
                    </td>
                    <td>
                        <form action="/Wishes/action=open_edit_wish" method="get">
                            <a>
                                <button name="editwishbtn" value="{$wish -> id}" type="sumbit"
                                        class="btn btn-inbox btn-sm" data-toggle="modal">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                            </a>
                        </form>
                    </td>
                </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>