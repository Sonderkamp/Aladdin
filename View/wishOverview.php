<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Max-->
<!-- * Date: 25-Feb-16-->
<!-- * Time: 15:12-->
<!-- */-->

<div class="container">
    <table class="table">
        <thead>
        <tr>
            <th>Gebruiker</th>
            <th>Naam</th>
            <th>Land</th>
            <th>Stad</th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$wishes item=wish}
        <tr>
            <td>{$wish -> user}</td>
            <td>{$wish -> name}</td>
            <td>{$wish -> country}</td>
            <td>{$wish -> city}</td>
        </tr>
        {/foreach}
        </tbody>
    </table>
</div>