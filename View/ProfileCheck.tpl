<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Marius-->
<!-- */-->
<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <div class="profile-usertitle">
                <div class="text-center">
                    <b>{$user->displayName}</b>
                </div>
                <div class="text-center">
                    {$user->email}
                </div>
                <div class="text-center danger">
                    {if $blockstatus !== false}
                        Gebruiker is geblokkeerd
                        <br>
                        {$blockstatus}
                    {/if}

                </div>
                <div class="text-center">
                    {if $blockstatus !== false}
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#deblock">
                            Deblokkeer
                        </button>
                    {else}
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#block">
                            Blokkeer
                        </button>
                    {/if}
                </div>
            </div>
            <br>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <ul class="nav nav-pills nav-stacked">
                <li class="active">
                    <a href="#tab1" data-toggle="tab"> <i class="glyphicon glyphicon-user"></i> Overzicht</a>
                </li>
                <li><a href="#tab2" data-toggle="tab"><i class="glyphicon glyphicon-edit"></i> Wensen</a></li>
                <li><a href="#tab3" data-toggle="tab"><i class="glyphicon glyphicon-option-horizontal"></i>
                        Talenten</a>
                </li>
                <br>
                <li><a href="#tab5" data-toggle="tab"><i class="glyphicon glyphicon-option-horizontal"></i>
                        Rapporteer</a>
                </li>
                <li><a href="#tab4" data-toggle="tab"><i class="glyphicon glyphicon-option-horizontal"></i>
                        Blokkeer</a>
                </li>

            </ul>

        </div>
        <div class="col-sm-9">
            <div class="profile-content">
                <div class="panel">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1">

                            <div class="panel-body ">
                                <div class="row">
                                    <div class="col-xs-10 col-xs-offset-1">
                                        <h5>Overzicht</h5>
                                        <table class="table table-user-information">
                                            <tbody>
                                            <tr>
                                                <td>Naam:</td>
                                                <td>{$user->name} {$user->surname}</td>
                                            </tr>
                                            <tr>
                                                <td>E-Mail</td>
                                                <td>{$user->email}</td>
                                            </tr>
                                            <tr>
                                                <td>Initialen</td>
                                                <td>{$user->initials}</td>
                                            </tr>
                                            <tr>
                                                <td>Adress</td>
                                                <td>{$user->address}</td>
                                            </tr>
                                            <tr>
                                                <td>Postcode</td>
                                                <td>{$user->postalcode}</td>
                                            </tr>
                                            <tr>
                                                <td>Plaats</td>
                                                <td>{$user->city}</td>
                                            </tr>
                                            <tr>
                                                <td>Land</td>
                                                <td>{$user->country}</td>
                                            </tr>
                                            <tr>
                                                <td>Geboortedatum</td>
                                                <td>{$user->dob|date_format:"%d-%m-%Y"}</td>
                                            </tr>
                                            <tr>
                                                <td>Geslacht</td>
                                                {if $user->gender eq 'male'}
                                                    <td>Man</td>
                                                {elseif $user->gender eq 'female'}
                                                    <td>Vrouw</td>
                                                {elseif $user->gender eq 'other'}
                                                    <td>-</td>
                                                {/if}
                                            </tr>
                                            <tr>
                                                <td>Handicap</td>
                                                {if $user->handicap}
                                                    <td>Ja</td>
                                                {else}
                                                    <td>Nee</td>
                                                {/if}
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="tab2">
                            <div class="panel-heading text-center">
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-10 col-xs-offset-1">
                                        <h5>Angemaakte wensen</h5>
                                        {if count($wishes) > 0}
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>Gebruiker</th>
                                                    <th>Wens</th>
                                                    <th>Status</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                {foreach from=$wishes item=wish}
                                                    <tr>
                                                        <td>{$wish->user->displayName}</td>
                                                        <td>{$wish->title}</td>
                                                        <td>{$wish->status}</td>
                                                        <td><a>Bekijk</a></td>
                                                    </tr>
                                                {/foreach}
                                                </tbody>
                                            </table>
                                        {else}
                                            <h6>Deze gebruiker heeft nog geen wensen.</h6>
                                        {/if}
                                        <h5>Vervulde wensen</h5>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Gebruiker</th>
                                                <th>Wens</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {*{foreach from=$wishes item=wish}*}
                                            {*<tr>*}
                                            {*<td>{$wish->user->displayName}</td>*}
                                            {*<td>{$wish->title}</td>*}
                                            {*<td>{$wish->status}</td>*}
                                            {*<td><a>Bekijk</a></td>*}
                                            {*</tr>*}
                                            {*{/foreach}*}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="tab3">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-10 col-xs-offset-1">
                                        <h5>Talenten</h5>
                                        {foreach from=$talents item=$value}
                                            <div class="col-md-4">
                                                <div class="thumbnail">
                                                    <div class="caption">
                                                        <div class="row">
                                                            <label class="col-xs-12 text-center">{htmlspecialchars($value->name)}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        {/foreach}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="tab4">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-10 col-xs-offset-1">
                                        <h5>Blokkeergeschiedenis</h5>
                                        {if count($blocks) == 0}
                                            <h6>Deze gebruiker is nog nooit geblokkeerd.</h6>
                                        {else}
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>Datum</th>
                                                    <th>Reden</th>
                                                    <th>Geblokkeerd</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                {foreach from=$blocks item=wish}
                                                    <tr>
                                                        <td>{$wish.DateBlocked}</td>
                                                        <td>{$wish.Reason}</td>
                                                        <td>{if $wish.IsBlocked} Ja {else} Nee {/if}</td>
                                                    </tr>
                                                {/foreach}
                                                </tbody>
                                            </table>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="tab5">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-10 col-xs-offset-1">
                                        <h5>Rapporteer</h5>
                                        {if count($reports) > 0}
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>Melder</th>
                                                    <th>Reden</th>
                                                    <th>Gebruiker</th>
                                                    <th>Status</th>
                                                    <th>Datum</th>
                                                </tr>
                                                </thead>
                                                <tbody>


                                                {foreach from=$reports item=report}
                                                    <tr>
                                                        <td>
                                                            <span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> getReporter() -> getDisplayName())}
                                                        </td>
                                                        <td>{htmlspecialcharsWithNL($report -> getMessage()|substr:0:20)}</td>
                                                        <td>
                                                            <span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> getReported() -> getDisplayName())}
                                                        </td>
                                                        <td>{htmlspecialcharsWithNL($report -> getStatus())}</td>
                                                        <td>
                                                            <span class="glyphicon glyphicon-calendar"></span> {htmlspecialcharsWithNL($report -> getDate())}
                                                        </td>
                                                    </tr>
                                                {/foreach}

                                                </tbody>
                                            </table>
                                        {else}
                                            <h6>Deze gebruiker is nog nooit geraporteerd.</h6>
                                        {/if}
                                        {if count($reports2) > 0}
                                            <h5>Geraporteerd</h5>
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>Melder</th>
                                                    <th>Reden</th>
                                                    <th>Gebruiker</th>
                                                    <th>Status</th>
                                                    <th>Datum</th>
                                                </tr>
                                                </thead>
                                                <tbody>


                                                {foreach from=$reports2 item=report}
                                                    <tr>
                                                        <td>
                                                            <span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> getReporter() -> getDisplayName())}
                                                        </td>
                                                        <td>{htmlspecialcharsWithNL($report -> getMessage()|substr:0:20)}</td>
                                                        <td>
                                                            <span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> getReported() -> getDisplayName())}
                                                        </td>
                                                        <td>{htmlspecialcharsWithNL($report -> getStatus())}</td>
                                                        <td>
                                                            <span class="glyphicon glyphicon-calendar"></span> {htmlspecialcharsWithNL($report -> getDate())}
                                                        </td>
                                                    </tr>
                                                {/foreach}

                                                </tbody>
                                            </table>
                                        {else}
                                            <h6>Deze gebruiker heeft nog niemand geraporteerd.</h6>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="block" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Blokkeer gebruiker</h4>
            </div>
            <div class="modal-body">
                <form action="/profileoverview/action=block/user={$user->email}" method="get">
                    <fieldset class="form-group">
                        <input type="text" class="form-control" name="reason"
                               placeholder="Reden">
                        <small class="text-muted">Geef de reden op die de gebruiker ziet als hij probeert in te loggen
                        </small>
                    </fieldset>
                    <button type="submit" class="btn btn-default">Blokkeer</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<div class="modal fade" id="deblock" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Delokkeer gebruiker</h4>
            </div>
            <div class="modal-body">
                <form action="/profileoverview/action=deblock/user={$user->email}" method="get">
                    <fieldset class="form-group">
                        <input type="text" class="form-control" name="reason"
                               placeholder="Reden">
                        <small class="text-muted">Geef een reden op voor andere administratoren.
                        </small>
                    </fieldset>
                    <button type="submit" class="btn btn-default">Blokkeer</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
