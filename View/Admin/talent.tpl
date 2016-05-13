
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
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$all_talents item=talent}
                                <tr>
                                    <td class="col-xs-4 col-sm-4 col-md-4 col-lg-4">{htmlentities(trim($talent->name),ENT_QUOTES)}</td>
                                    {if !Empty(htmlentities(trim($talent->moderator_username),ENT_QUOTES))}
                                        <td class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                            {if htmlentities(trim($talent->is_rejected),ENT_QUOTES) == true}
                                                Ja
                                            {else}
                                                Nee
                                            {/if}
                                        </td>
                                        <td class="col-xs-4 col-sm-4 col-md-4 col-lg-4">{htmlentities(trim($talent->moderator_username),ENT_QUOTES)}</td>
                                    {else}
                                        <td class="col-xs-4 col-sm-4 col-md-4 col-lg-4">-</td>
                                        <td class="col-xs-4 col-sm-4 col-md-4 col-lg-4">-</td>
                                    {/if}
                                    {if $talent->is_rejected === 1}
                                        <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                            {if !Empty(htmlentities(trim($talent->moderator_username),ENT_QUOTES))}
                                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal{preg_replace('/\s+/', '', htmlentities(trim($talent->id),ENT_QUOTES))}synonym">
                                                    <span class="glyphicon glyphicon-wrench"></span>
                                                </button>
                                            {/if}
                                        </td>
                                    {else}
                                        <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></td>
                                    {/if}
                                    <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                        {if !Empty(htmlentities(trim($talent->moderator_username),ENT_QUOTES))}
                                            <button type="button" class="btn btn-inbox btn-sm" data-toggle="modal" data-target="#myModal{preg_replace('/\s+/', '', htmlentities(trim($talent->id),ENT_QUOTES))}">
                                                <span class="glyphicon glyphicon-edit"></span>
                                            </button>
                                        {/if}
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                    {if $all_talent_number > 1}
                        <div class="row">
                            <div class="col-xs-offset-4">
                                <nav>
                                    {if $all_talent_number < 7}
                                        <ul class="pagination">
                                            {if $current_all_talents_number <= 1}
                                                <li class="disabled">
                                                    <a href="#" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                            {else}
                                                <li>
                                                    <a href="/admintalents/admin_a={$current_all_talents_number - 1}" aria-label="Previous">
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
                                                        <a href="/admintalents/admin_a={$number}">{$number}</a>
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
                                                    <a href="/admintalents/admin_a={$current_all_talents_number + 1}" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            {/if}
                                        </ul>
                                    {else}
                                        <ul class="pagination">
                                            {if $current_all_talents_number <= 1}
                                                <li class="disabled">
                                                    <a href="#" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                            {else}
                                                <li>
                                                    <a href="/admintalents/admin_a={$current_all_talents_number - 1}" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                            {/if}
                                            {if 1 == $current_all_talents_number}
                                                <li class="active">
                                                    <a href="#">1</a>
                                                </li>
                                            {else}
                                                <li>
                                                    <a href="/admintalents/admin_a=1">1</a>
                                                </li>
                                            {/if}
                                            {if $current_all_talents_number < 4}
                                                {for $number=2 to 4}
                                                    {if $number == $current_all_talents_number}
                                                        <li class="active">
                                                            <a href="#">{$number}</a>
                                                        </li>
                                                    {else}
                                                        <li>
                                                            <a href="/admintalents/admin_a={$number}">{$number}</a>
                                                        </li>
                                                    {/if}
                                                {/for}
                                                <li class="disabled">
                                                    <a href="#">...</a>
                                                </li>
                                            {elseif $current_all_talents_number > ($all_talent_number - 3)}
                                                <li class="disabled">
                                                    <a href="#">...</a>
                                                </li>
                                                {for $number=($all_talent_number - 3) to ($all_talent_number - 1)}
                                                    {if $number == $current_all_talents_number}
                                                        <li class="active">
                                                            <a href="#">{$number}</a>
                                                        </li>
                                                    {else}
                                                        <li>
                                                            <a href="/admintalents/admin_a={$number}">{$number}</a>
                                                        </li>
                                                    {/if}
                                                {/for}
                                            {else}
                                                <li class="disabled">
                                                    <a href="#">...</a>
                                                </li>
                                                {for $number=($current_all_talents_number - 1) to ($current_all_talents_number + 1)}
                                                    {if $number == $current_all_talents_number}
                                                        <li class="active">
                                                            <a href="#">{$number}</a>
                                                        </li>
                                                    {else}
                                                        <li>
                                                            <a href="/admintalents/admin_a={$number}">{$number}</a>
                                                        </li>
                                                    {/if}
                                                {/for}
                                                <li class="disabled">
                                                    <a href="#">...</a>
                                                </li>
                                            {/if}
                                            {if $all_talent_number == $current_all_talents_number}
                                                <li class="active">
                                                    <a href="#">{$number}</a>
                                                </li>
                                            {else}
                                                <li>
                                                    <a href="/admintalents/admin_a={$all_talent_number}">
                                                        <span aria-hidden="true">{$all_talent_number}</span>
                                                    </a>
                                                </li>
                                            {/if}
                                            {if $current_all_talents_number >= $all_talent_number}
                                                <li class="disabled">
                                                    <a href="#" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            {else}
                                                <li>
                                                    <a href="/admintalents/admin_a={$current_all_talents_number + 1}" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            {/if}
                                        </ul>
                                    {/if}
                                </nav>
                            </div>
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
                                    <button type="button" class="btn btn-add btn-sm" data-toggle="modal" data-target="#myModal{preg_replace('/\s+/', '', htmlentities(trim($talent->id),ENT_QUOTES))}accept">
                                        <span class="glyphicon glyphicon-ok"></span>
                                    </button>
                                </td>
                                <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <button type="button" class="btn btn-inbox btn-sm" data-toggle="modal" data-target="#myModal{preg_replace('/\s+/', '', htmlentities(trim($talent->id),ENT_QUOTES))}deny">
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

<!-- Modal Edit-->
{foreach from=$all_talents item=talent}
    {if !Empty(htmlentities(trim($talent->moderator_username),ENT_QUOTES))}
        <div id="myModal{preg_replace('/\s+/', '', htmlentities(trim($talent->id),ENT_QUOTES))}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Het talent {htmlentities(trim($talent->name),ENT_QUOTES)} aanpassen</h4>
                    </div>
                    <form action="/admintalents" method="post">
                        <input type="hidden" name="admin_talent_id" value="{htmlentities(trim($talent->id),ENT_QUOTES)}">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="name">Nieuwe naam:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="name" placeholder="Naam" name="admin_talent_name" value="{htmlentities(trim($talent->name),ENT_QUOTES)}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <div class="checkbox">
                                        {if htmlentities(trim($talent->is_rejected),ENT_QUOTES) == true}
                                            <label><input type="checkbox" checked="checked" name="admin_talent_is_rejected"> Is geaccepteerd</label>
                                        {else}
                                            <label><input type="checkbox" name="admin_talent_is_rejected"> Is geaccepteerd</label>
                                        {/if}
                                    </div>
                                </div>
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

        {if $talent->is_rejected === 1}
            <!-- Modal synonym-->
            <div id="myModal{preg_replace('/\s+/', '', htmlentities(trim($talent->id),ENT_QUOTES))}synonym" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Synoniemen beheren van "{htmlentities(trim($talent->name),ENT_QUOTES)}"</h4>
                        </div>
                        <div class="modal-body">
                            <form action="admintalents" method="post">
                                <input type="hidden" name="talent_synonym_id" value="{$talent->id}">
                                <fieldset class="form-group col-xs-5">
                                    <label for="synonym">Wel synoniem</label>
                                    <select name="synonym_remove[]" multiple class="form-control" id="synonym">
                                        {foreach from=$talent->synonyms item=synonym}
                                            <option value="{htmlentities(trim($synonym["id"]),ENT_QUOTES)}">{htmlentities(trim($synonym["name"]),ENT_QUOTES)}</option>
                                        {/foreach}
                                    </select>
                                </fieldset>
                                <div class="list-arrows col-xs-2 text-center">
                                    <button name="add_synonym_button" class="btn btn-default btn-sm move-left small-margin-bottom" value="add">
                                        <span class="glyphicon glyphicon-chevron-left"></span>
                                    </button>

                                    <button name="remove_synonym_button" class="btn btn-default btn-sm move-right" value="remove">
                                        <span class="glyphicon glyphicon-chevron-right"></span>
                                    </button>
                                </div>
                                <fieldset class="form-group col-xs-5">
                                    <label for="no_synonym">Geen synoniem</label>
                                    <select name="synonym_add[]" multiple class="form-control" id="no_synonym">
                                        {foreach from=$accepted_talents item=talent2}
                                            {if $talent->id != $talent2->id && array_search($talent2->id, array_column($talent->synonyms, "id")) === false}
                                                <option value="{htmlentities(trim($talent2->id),ENT_QUOTES)}">{htmlentities(trim($talent2->name),ENT_QUOTES)}</option>
                                            {/if}
                                        {/foreach}
                                    </select>
                                </fieldset>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Sluiten</button>
                        </div>
                    </div>
                </div>
            </div>
        {/if}
    {/if}
{/foreach}

<!-- Modal deny request-->
{foreach from=$unaccepted_talents item=talent}
<div id="myModal{preg_replace('/\s+/', '', htmlentities(trim($talent->id),ENT_QUOTES))}deny" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Wilt u het talentaanvraag {htmlentities(trim($talent->name),ENT_QUOTES)} afwijzen?</h4>
            </div>
            <form action="/admintalents" method="post">
                <div class="modal-body">

                    <div class="form-group">
                        <p>
                            <div class="col-xs-3">
                                Rede afwijzing:
                            </div>
                            <div class="col-xs-9">
                                <input type="hidden" value="{htmlentities(trim($talent->id),ENT_QUOTES)}" name="deny_id"/>
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
<div id="myModal{preg_replace('/\s+/', '', htmlentities(trim($talent->id),ENT_QUOTES))}accept" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Wilt u het talentaanvraag {htmlentities(trim($talent->name),ENT_QUOTES)} accepteren?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Sluiten</button>
                <form action="/admintalents" method="post">
                <input type="hidden" value="{htmlentities(trim($talent->id),ENT_QUOTES)}" name="accept_id"/>
                    <button type="submit" name="submit" class="btn btn-add info">
                        <span class="glyphicon glyphicon-ok"></span> Accepteren
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
{/foreach}

{if !Empty($synonym_id)}
    <script type="text/javascript">
        $(window).load(function(){
            $('#myModal{preg_replace('/\s+/', '', htmlentities(trim($synonym_id),ENT_QUOTES))}synonym').modal('show');
        });
    </script>
{/if}
