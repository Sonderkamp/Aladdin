
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
                {foreach from=$user_talents item=talent}
                    <tr>
                        <td class="col-sm-12">{$talent -> name}</td>
                        <td class="col-sm-1">
                            <button type="button" class="btn btn-inbox btn-sm" data-toggle="modal" data-target="#myModal{preg_replace('/\s+/', '', $talent->name)}">
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
                        <td class="col-sm-12">{$talent -> name}</td>
                        <td class="col-sm-1">
                            <form action="/talents" method="post">
                                <input type="hidden" name="add_id" value="{$talent->id}"/>
                            <button type="submit" name="submit" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-ok"></span>
                            </button>
                                </form>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
{foreach from=$user_talents item=talent}
<div id="myModal{preg_replace('/\s+/', '', $talent->name)}" class="modal fade" role="dialog">
  <div class="modal-dialog">

      <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Talent verwijderen</h4>
            </div>
            <div class="modal-body">
                <p>
                    Weet u zeker dat u het talent "{$talent->name}" wilt verwijderen?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Sluiten</button>
                <form action="/talents" method="post">
                    <input type="hidden" name="remove_id" value="{$talent->id}"/>
                    <button type="submit" name="submit" class="btn btn-inbox info"><span class="glyphicon glyphicon-remove"></span> Verwijderen</button>
                </form>
            </div>
        </div>

    </div>
</div>
{/foreach}