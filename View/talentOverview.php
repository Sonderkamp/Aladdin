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
                <form action="/talents" method="post">
                    <td class="col-sm-12">{$talent -> talent}</td>
                    <input type="hidden" name="talent" value="{$talent -> talent}"/>
                    <td class="col-sm-1"><input type="submit" name="submit" value="Verwijderen" class="btn btn-danger btn-sm" /></td>
                </form>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>