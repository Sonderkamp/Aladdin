<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Marius-->
<!-- */-->
<script>
    function popup(mylink, windowname, w, h) {
        if (!window.focus)return true;
        var href;
        if (typeof(mylink) == 'string') href = mylink; else href = mylink.href;
        window.open(href, windowname, 'width=' + w + ',height=' + h + ',scrollbars=yes');
        return false;
    }
</script>
<img src="/Resources/Images/banner.jpg" class="img-responsive width background">
<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <div class="profile-usertitle">
                <div class="text-center">
                    <b>{$curUser->displayName}</b>
                </div>
                <div class="text-center">
                    {$curUser->email}
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
                                            {if !empty($curUser->companyName)}
                                                <tr>
                                                    <td>Bedrijfsnaam:</td>
                                                    <td> {$curUser->companyName} </td>
                                                </tr>
                                            {/if}
                                            <tr>
                                                <td>Naam:</td>
                                                <td>{$curUser->name} {$curUser->surname}</td>
                                            </tr>
                                            <tr>
                                                <td>E-Mail</td>
                                                <td>{$curUser->email}</td>
                                            </tr>
                                            <tr>
                                                <td>Voorletters</td>
                                                <td>{$curUser->initials}</td>
                                            </tr>
                                            <tr>
                                                <td>Adress</td>
                                                <td>{$curUser->address}</td>
                                            </tr>
                                            <tr>
                                                <td>Postcode</td>
                                                <td>{$curUser->postalcode}</td>
                                            </tr>
                                            <tr>
                                                <td>Plaats</td>
                                                <td>{$curUser->city}</td>
                                            </tr>
                                            <tr>
                                                <td>Land</td>
                                                <td>{$curUser->country}</td>
                                            </tr>
                                            {if empty($curUser->companyName)}
                                                <tr>
                                                    <td>Geboortedatum</td>
                                                    <td>{$curUser->dob|date_format:"%d-%m-%Y"}</td>
                                                </tr>
                                                {if !empty($curUser->guardian)}
                                                    <tr>
                                                        <td>Voogd:</td>
                                                        <td> {$curUser->guardian} </td>
                                                    </tr>
                                                {/if}
                                                <tr>
                                                    <td>Geslacht</td>
                                                    {if $curUser->gender eq 'male'}
                                                        <td>Man</td>
                                                    {elseif $curUser->gender eq 'female'}
                                                        <td>Vrouw</td>
                                                    {elseif $curUser->gender eq 'other'}
                                                        <td>-</td>
                                                    {/if}
                                                </tr>
                                                <tr>
                                                    <td>Handicap</td>
                                                    {if $curUser->handicap}
                                                        <td>Ja</td>
                                                    {else}
                                                        <td>Nee</td>
                                                    {/if}
                                                </tr>
                                                {if $curUser->handicap}
                                                    <tr>
                                                        <td>Handicap Informatie</td>
                                                        <td>{$curUser->handicapInfo}</td>

                                                    </tr>
                                                {/if}
                                            {/if}
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
                                                        <td>
                                                            <a href="/wishes/action=getSpecificWish/admin=true/Id={$wish->id}"
                                                               onClick="return popup(this, 'notes',900,400)">Bekijk</a>
                                                        </td>
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
                                            {foreach from=$completedWishes item=wish}
                                                <tr>
                                                    <td>{$wish->user->displayName}</td>
                                                    <td>{$wish->title}</td>
                                                    <td>{$wish->status}</td>
                                                    <td>
                                                        <a href="/wishes/action=getSpecificWish/admin=true/Id={$wish->id}"
                                                           onClick="return popup(this, 'notes',900,400)">Bekijk</a></td>
                                                </tr>
                                            {/foreach}
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
                                                    <th>Type</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>


                                                {foreach from=$reports item=report}
                                                    <tr>
                                                        <td>
                                                            <span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> reporter -> displayName)}
                                                        </td>
                                                        <td>{htmlspecialcharsWithNL($report -> message|substr:0:20)}</td>
                                                        <td>
                                                            <span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> reported -> displayName)}
                                                        </td>
                                                        <td>{htmlspecialcharsWithNL($report -> status)}</td>
                                                        <td>
                                                            <span class="glyphicon glyphicon-calendar"></span> {htmlspecialcharsWithNL($report -> date)}
                                                        </td>
                                                        <td>
                                                            {if !empty($report->wishID)}
                                                                Wens
                                                            {else}
                                                                {* bekijk bericht *}
                                                                Bericht
                                                            {/if}
                                                        </td>
                                                        <td>

                                                            {if !empty($report->wishID)}
                                                                <a href="wishes/action=getSpecificWish?admin=true&Id={$report->wishID}"
                                                                   onClick="return popup(this, 'notes',900,400)">Bekijk
                                                                    wens</a>
                                                            {else}
                                                                {* bekijk bericht *}
                                                                <a href="adminmail/action=show/id={$report->messageID}/user={$report -> reporter -> email}"
                                                                   onClick="return popup(this, 'notes',700,400)">Bekijk
                                                                    bericht</a>
                                                            {/if}

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
                                                    <th>Type</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>


                                                {foreach from=$reports2 item=report}
                                                    <tr>
                                                        <td>
                                                            <span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> reporter -> displayName)}
                                                        </td>
                                                        <td>{htmlspecialcharsWithNL($report -> message|substr:0:20)}</td>
                                                        <td>
                                                            <span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> reported -> displayName)}
                                                        </td>
                                                        <td>{htmlspecialcharsWithNL($report -> status)}</td>
                                                        <td>
                                                            <span class="glyphicon glyphicon-calendar"></span> {htmlspecialcharsWithNL($report -> date)}
                                                        </td>
                                                        <td>
                                                            {if !empty($report->wishID)}
                                                                Wens
                                                            {else}
                                                                {* bekijk bericht *}
                                                                Bericht
                                                            {/if}
                                                        </td>
                                                        <td>

                                                            {if !empty($report->wishID)}
                                                                <a href="/wishes/action=getSpecificWish?admin=true&Id={$report->wishID}"
                                                                   onClick="return popup(this, 'notes',900,400)">Bekijk
                                                                    wens</a>
                                                            {else}
                                                                {* bekijk bericht *}
                                                                <a href="/adminmail/action=show/id={$report->messageID}/user={$report -> reporter -> email}"
                                                                   onClick="return popup(this, 'notes',700,400)">Bekijk
                                                                    bericht</a>
                                                            {/if}

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
                <form action="/profileoverview/action=block/user={$curUser->email}" method="get">
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
                <form action="/profileoverview/action=deblock/user={$curUser->email}" method="get">
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
