
<!--Created by PhpStorm.-->
<!--User: Joost-->
<!--Date: 27-2-2016-->
<!--Time: 21:48-->

<div class="container">
    <div class="col-sm-12 col-md-6">
        <table class="table">
            <thead>
                <tr>
                    <th>Toegevoegde talenten</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$talents item=talent}
                    <tr>
                        <td class="col-sm-12">{$talent -> talent}</td>
                        <td class="col-sm-1">
                            <button type="button" class="btn btn-inbox btn-sm" data-toggle="modal" data-target="#myModal{preg_replace('/\s+/', '', $talent->talent)}">
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
    <div class="col-sm-12 col-md-6">
        <table class="table">
            <thead>
                <tr>
                    <th>Alle talenten</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$talents item=talent}
                    <tr>
                        <td class="col-sm-12">{$talent -> talent}</td>
                        <td class="col-sm-1">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModal{preg_replace('/\s+/', '', $talent->talent)}">
                                <span class="glyphicon glyphicon-ok"></span>
                            </button>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
{foreach from=$talents item=talent}
<div id="myModal{preg_replace('/\s+/', '', $talent->talent)}" class="modal fade" role="dialog">
  <div class="modal-dialog">

      <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Talent verwijderen</h4>
            </div>
            <div class="modal-body">
                <p>
                    Weet u zeker dat u het talent "{$talent->talent}" wilt verwijderen?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Sluiten</button>
                <form action="/talents" method="post">
                    <input type="hidden" name="talent" value="{$talent -> talent}"/>
                    <button type="submit" name="submit" class="btn btn-inbox info"><span class="glyphicon glyphicon-remove"></span> Verwijderen</button>
                </form>
            </div>
        </div>

    </div>
</div>
{/foreach}