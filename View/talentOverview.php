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
                <td class="col-sm-12">{$talent -> talent}</td>
                <td class="col-sm-1"><a href="/talents/remove={$talent -> talent}" class="btn-danger btn-sm">Verwijderen</a></td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>