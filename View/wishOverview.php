<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Max-->
<!-- * Date: 25-Feb-16-->
<!-- * Time: 15:12-->
<!-- */-->
<!-- TODO *************************** -->
<!-- TODO * BALK HEFT EEN WIDTH KUT *-->
<!-- TODO ***************************-->
<div class="container">
    <div class="row">
         <span class="hidden-xs hidden-sm  hidden-md info">
                <a class="btn btn-primary side-button" href="/Wishes/action=open_wish">
                    <span class="glyphicon glyphicon-plus"></span>
                </a>
            </span>

        <div class=" col-xs-12 col-lg-2">
            <h5>Wensen overzicht</h5>
            <hr/>
             <span class="hidden-lg info">
                <a class="btn btn-primary side-button" href="/Wishes/action=open_wish">
                    <span class="glyphicon glyphicon-plus"></span>
                </a>
            </span>

            <table>
                <tr>
                    <td>
                        <a>
                            <button type="button" class="btn btn-primary side-button">
                                <span class="glyphicon glyphicon-align-justify"></span> Mijn wensen
                            </button>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>
                        <a>
                            <button type="button" class="btn btn-default side-button">
                                <span class="glyphicon glyphicon-ok"></span> Vervulde wensen
                            </button>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>
                        <a>
                            <button type="button" class="btn btn-default side-button">
                                <span class="glyphicon glyphicon-remove"></span> Onvervulde wensen
                            </button>
                        </a>
                    </td>
                </tr>

            </table>


            <!--            <h7><a type="button" class="btn btn-primary btn-default side-button" href="/Wishes/action=mywishes">Mijn-->
            <!--                    wensen</a></h7>-->
            <!--            <br>-->
            <!--            <h7><a type="button" class="btn btn-default side-button" href="/Wishes/action=incompletedWishes">Onvervulde-->
            <!--                    wensen</a></h7>-->
            <!--            <br>-->
            <!--            <h7><a type="button" class="btn btn-default side-button" href="/Wishes/action=completedWishes">Vervulde-->
            <!--                    wensen</a></h7>-->
            <!--            <br>-->
        </div>
        <div class="col-lg-10">

            {if isset($wishError)}
            <div class="form-error" id="err">Error: {htmlspecialchars($wishError)}</div>
            {else}
            <div id="err"></div>
            {/if}

            <table class="table">
                <thead>
                <tr>
                    <th>Gebruiker</th>
                    <th>Title</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Content</th>
                </tr>
                </thead>
                <tbody>
                {foreach from=$wishes item=wish}
                <tr>
                    <td>{$wish -> user}</td>
                    <td>{$wish -> title}</td>
                    <td>{$wish -> country}</td>
                    <td>{$wish -> city}</td>
                    <td>{$wish -> content}</td>
                </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>