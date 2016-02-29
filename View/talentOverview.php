<?php
/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:48
 */
?>

<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th>Talent</th>
                <th>Verwijderen</th>
            </tr>
        </thead>
        <tbody>
        {foreach from=$talents item=talent}
            <tr>
                <td class="col-sm-8">{$talent -> talent}</td>
                <td class="col-sm-1"><button type="button" class="btn btn-danger btn-sm">Verwijderen</button></td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>