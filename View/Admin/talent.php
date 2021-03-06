
<!--Created by PhpStorm.-->
<!--User: Joost-->
<!--Date: 10-3-2016-->
<!--Time: 09:37-->
<div class="container">
    <div id="rootwizard">
        <h5>Talenten beheren</h5>
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#tab1" data-toggle="tab">Talenten beheren</a></li>
                <li><a href="#tab2" data-toggle="tab">Aanvragen talenten</a></li>
            </ul>
        </div>
        <div class="col-md-10">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab1">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Alle talenten</th>
                            <th>Is geaccepteerd</th>
                            <th>Gecheckt door</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$all_talents item=talent}
                        <tr>
                            <td class="col-xs-4 col-sm-4 col-md-4 col-lg-4">{$talent -> name}</td>
                            {if !Empty($talent-> moderator_username)}
                                {if $talent->is_rejected == true}
                                <td class="col-xs-4 col-sm-4 col-md-4 col-lg-4">Ja</td>
                                {else}
                                <td class="col-xs-4 col-sm-4 col-md-4 col-lg-4">Nee</td>
                                {/if}
                                <td class="col-xs-4 col-sm-4 col-md-4 col-lg-4">{$talent -> moderator_username}</td>
                            {else}
                            <td class="col-xs-4 col-sm-4 col-md-4 col-lg-4">-</td>
                            <td class="col-xs-4 col-sm-4 col-md-4 col-lg-4">-</td>
                            {/if}
                            <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                <button type="button" class="btn btn-inbox btn-sm" data-toggle="modal" data-target="#myModal{preg_replace('/\s+/', '', $talent->name)}">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                            </td>
                        </tr>
                        {/foreach}
                        </tbody>
                    </table>
                    {if $all_talent_number > 1}
                    <div>
                        <nav>
                            <ul class="pagination">
                                {if $current_all_talents_number <= 1}
                                <li class="disabled">
                                    <a href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                {else}
                                <li>
                                    <a href="/talents/admin_a={$current_all_talents_number - 1}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                {/if}

                                {for $number=1 to $all_talent_number}
                                {if $number == $current_all_talents_number}
                                <li class="active">
                                    <a href="#">{$number}</a>
                                </li>
                                {else}
                                <li>
                                    <a href="/talents/admin_a={$number}">{$number}</a>
                                </li>
                                {/if}
                                {/for}

                                {if $current_all_talents_number >= $all_talent_number}
                                <li class="disabled">
                                    <a href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                {else}
                                <li>
                                    <a href="/talents/admin_a={$current_all_talents_number + 1}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                {/if}
                            </ul>
                        </nav>
                    </div>
                    {/if}
                </div>

                <div class="tab-pane" id="tab2">
                    <div>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Aangevraagde talenten</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach from=$unaccepted_talents item=talent}
                            <tr>
                                <td class="col-xs-12 col-sm-12 col-md-12 col-lg-12">{$talent -> name}</td>
                                <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <button type="button" class="btn btn-add btn-sm" data-toggle="modal" data-target="#myModal{preg_replace('/\s+/', '', $talent->name)}accept">
                                        <span class="glyphicon glyphicon-ok"></span>
                                    </button>
                                </td>
                                <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <button type="button" class="btn btn-inbox btn-sm" data-toggle="modal" data-target="#myModal{preg_replace('/\s+/', '', $talent->name)}deny">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </button>
                                </td>
                            </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Remove-->
{foreach from=$all_talents item=talent}
<div id="myModal{preg_replace('/\s+/', '', $talent->name)}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Het talent {$talent->name} aanpassen</h4>
            </div>
            <form action="/talents" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <p>
                            <div class="col-xs-3">
                                Nieuwe naam:
                            </div>
                            <div class="col-xs-9">
                                <input type="hidden" name="admin_talent_id" value="{$talent->id}">
                                <input type="text" class="form-control" placeholder="Naam" name="admin_talent_name" value="{$talent->name}">
                            </div>
                        </p>
                        <br>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Sluiten</button>
                    <button type="submit" name="submit" class="btn btn-inbox info">
                        <span class="glyphicon glyphicon-edit"></span> Aanpassen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{/foreach}

<!-- Modal deny request-->
{foreach from=$unaccepted_talents item=talent}
<div id="myModal{preg_replace('/\s+/', '', $talent->name)}deny" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Wilt u het talentaanvraag {$talent->name} afwijzen?</h4>
            </div>
            <form action="/talents" method="post">
                <div class="modal-body">

                    <div class="form-group">
                        <p>
                            <div class="col-xs-3">
                                Rede afwijzing:
                            </div>
                            <div class="col-xs-9">
                                <input type="hidden" value="{$talent->id}" name="deny_id"/>
                                <input type="text" class="form-control" placeholder="Rede afwijzing" name="deny_message">
                            </div>
                        </p>
                        <br>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Sluiten</button>
                    <button type="submit" name="submit" class="btn btn-inbox info">
                        <span class="glyphicon glyphicon-remove"></span> Afwijzen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{/foreach}

<!-- Modal accept request-->
{foreach from=$unaccepted_talents item=talent}
<div id="myModal{preg_replace('/\s+/', '', $talent->name)}accept" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Wilt u het talentaanvraag {$talent->name} accepteren?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Sluiten</button>
                <form action="/talents" method="post">
                <input type="hidden" value="{$talent->id}" name="accept_id"/>
                    <button type="submit" name="submit" class="btn btn-add info">
                        <span class="glyphicon glyphicon-ok"></span> Accepteren
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
{/foreach}