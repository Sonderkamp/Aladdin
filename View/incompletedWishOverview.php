<!---->
<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Max-->
<!-- * Date: 28/02/2016-->
<!-- * Time: 19:41-->
<!-- */-->

<div class="container">
    <div class="row">
        <div class="col-lg-2">
            <h5>Wensen overzicht</h5>
            <hr>
            <h7><a type="button" class="btn btn-default side-button" href="/Wishes/action=mywishes">Mijn wensen</a></h7><br>
            <h7><a type="button" class="btn btn-primary btn-default side-button " href="/Wishes/action=incompletedWishes">Onvervulde wensen</a></h7><br>
            <h7><a type="button" class="btn btn-default side-button" href="/Wishes/action=completedWishes">Vervulde wensen</a></h7><br>
        </div>
        <div class="col-lg-10">
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