<?php
/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:48
 */
?>

<div class="container">
    <div class="col-sm-8">
        <table class="table">
            <thead>
                <tr>
                    <th>Talent</th>
                </tr>
            </thead>
            <tbody>
            {foreach from=$talents item=talent}
                <tr>
                    <td>{$talent -> talent}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>

    <div class="col-sm-1">
        {foreach from=$talents item=talent}
            <button>Verwijderen</button>
        {/foreach}
    </div>
</div>