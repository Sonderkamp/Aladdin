<!--Created by PhpStorm.-->
<!--User: Joost-->
<!--Date: 27-2-2016-->
<!--Time: 21:48-->

<div class="container">
    {if !Empty($talentError)}
        <div class="alert alert-danger fade in">
            <a href="#" class="close" data-dismiss="alert"
               aria-label="close">&times;</a>
            <strong>Error!</strong> {htmlspecialchars($talentError)}
        </div>
    {/if}

    {if !Empty($talentSuccess)}
        <div class="alert alert-success fade in">
            <a href="#" class="close" data-dismiss="alert"
               aria-label="close">&times;</a>
            <strong>Succes!</strong> {htmlspecialchars($talentSuccess)}
        </div>
    {/if}
    <h3>Talenten</h3>
    <div id="rootwizard">

        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <li {if $page == "myTalents"}class="active"{/if}><a href="#tab1" data-toggle="tab">Mijn talenten</a></li>
                <li {if $page == "allTalents"}class="active"{/if}><a href="#tab2" data-toggle="tab">Alle talenten</a></li>
                <li {if $page == "createTalent"}class="active"{/if}><a href="#tab3" data-toggle="tab">Talent toevoegen</a></li>
            </ul>
        </div>
        <div class="col-md-10">
            <div class="tab-content">
                <div class="tab-pane{if $page == "myTalents"} fade in active{/if}" id="tab1">
                    {if $talentsNumber <= 3}
                        <div class="alert alert-warning">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Waarschuwing!</strong> U moet minimaal 3 talenten hebben.
                        </div>
                    {/if}

                    <form class="col-xs-12 col-sm-12 col-md-12 col-lg-12" action="/talents" method="get">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <input class="form-control" name="searchAdded" placeholder="Zoek een toegevoegd talent"{if !Empty($searchAdded)}value="{$searchAdded}"{/if}>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <button type="submit" class="btn btn-primary">Zoek</button>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <a href="/talents/p=myTalents" class="btn btn-warning">Alle</a>
                        </div>
                    </form>

                    <table class="table">
                        <thead>
                        <tr>
                            <th>Mijn talenten</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            {foreach from=$talentsUser item=talent}
                                <tr>
                                    <td class="col-xs-12 col-sm-12 col-md-12 col-lg-12">{htmlentities(trim($talent->name),ENT_QUOTES)}</td>
                                    <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                        {if $talentsNumber <= 3}
                                            <button type="button" class="btn btn-inbox disabled btn-sm">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </button>
                                        {else}
                                            <button type="button" class="btn btn-inbox btn-sm" data-toggle="modal" data-target="#myModal{preg_replace('/\s+/', '', htmlentities(trim($talent->id),ENT_QUOTES))}">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </button>
                                        {/if}
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>

                    {* if there are more than 10 talents show pagination *}
                    {if $userCount > 1}

                        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="col-xs-offset-4">
                                <nav>
                                    <ul class="pagination">

                                        {* if currentUserCount is smaller than or same as 1 disable the previous button *}
                                        {* ELSE enable the previous button *}
                                        {if $currentUserCount <= 1}
                                            <li class="disabled">
                                                <a href="#" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                        {else}
                                            <li>
                                                <a href="/talents/p=myTalents/myTalents={$currentUserCount - 1}/allTalents={$currentTalentCount}/createTalent={$currentRequestedCount}" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                        {/if}

                                        {* If userCount is smaller than seven show normal pagination (1,2,3,4,5,6) *}
                                        {* ELSE show pagination for larger (first,...,3,4,5,...,last) *}
                                        {if $userCount < 7}

                                            {* Loop the number of userCount *}
                                            {for $number=1 to $userCount}

                                                {* if the number is the same as currentUserCount disable the button *}
                                                {* ELSE enable the button *}
                                                {if $number == $currentUserCount}
                                                    <li class="active">
                                                        <a href="#">{$number}</a>
                                                    </li>
                                                {else}
                                                    <li>
                                                        <a href="/talents/p=myTalents/myTalents={$number}/allTalents={$currentTalentCount}/createTalent={$currentRequestedCount}">{$number}</a>
                                                    </li>
                                                {/if}
                                            {/for}
                                        {else}

                                            {* If currentUserCount equals 1 disable the first button *}
                                            {* ELSE enable the first button *}
                                            {if 1 == $currentUserCount}
                                                <li class="active">
                                                    <a href="#">1</a>
                                                </li>
                                            {else}
                                                <li>
                                                    <a href="/talents/p=myTalents/myTalents=1/allTalents={$currentTalentCount}/createTalent={$currentRequestedCount}">1</a>
                                                </li>
                                            {/if}

                                            {* If currentUserCount is smaller than 4 show pagination like this (1,2,3,4,...,last) *}
                                            {if $currentUserCount < 4}

                                                {* loop from 2 until 4 *}
                                                {for $number=2 to 4}

                                                    {* If the number equals page than disable the button *}
                                                    {* ELSE enable the button *}
                                                    {if $number == $currentUserCount}
                                                        <li class="active">
                                                            <a href="#">{$number}</a>
                                                        </li>
                                                    {else}
                                                        <li>
                                                            <a href="/talents/p=myTalents/myTalents={$number}/allTalents={$currentTalentCount}/createTalent={$currentRequestedCount}">{$number}</a>
                                                        </li>
                                                    {/if}
                                                {/for}

                                                <li class="disabled">
                                                    <a href="#">...</a>
                                                </li>

                                                {* if currentUserCount is greater than userCount minus 3 (for example currentUserCount is 8 and userCount(10) - 3 is 7) *}
                                            {elseif $currentUserCount > ($userCount - 3)}

                                                <li class="disabled">
                                                    <a href="#">...</a>
                                                </li>

                                                {* Loop through the last four numbers until the last minus one *}
                                                {for $number=($userCount - 3) to ($userCount - 1)}

                                                    {* if number equals currentUserCount disable the button *}
                                                    {* ELSE enable the button *}
                                                    {if $number == $currentUserCount}
                                                        <li class="active">
                                                            <a href="#">{$number}</a>
                                                        </li>
                                                    {else}
                                                        <li>
                                                            <a href="/talents/p=myTalents/myTalents={$number}/allTalents={$currentTalentCount}/createTalent={$currentRequestedCount}">{$number}</a>
                                                        </li>
                                                    {/if}
                                                {/for}

                                                {* ELSE the pagination will be shown like this (first,...,3,4,5,...,last) *}
                                            {else}
                                                <li class="disabled">
                                                    <a href="#">...</a>
                                                </li>

                                                {* Loop from currentUserCount minus one until currentUserCount plus one *}
                                                {for $number=($currentUserCount - 1) to ($currentUserCount + 1)}

                                                    {* if number equals currentUserCount than disable the button *}
                                                    {* ELSE enable the button *}
                                                    {if $number == $currentUserCount}
                                                        <li class="active">
                                                            <a href="#">{$number}</a>
                                                        </li>
                                                    {else}
                                                        <li>
                                                            <a href="/talents/p=myTalents/myTalents={$number}/allTalents={$currentTalentCount}/createTalent={$currentRequestedCount}">{$number}</a>
                                                        </li>
                                                    {/if}
                                                {/for}
                                                <li class="disabled">
                                                    <a href="#">...</a>
                                                </li>
                                            {/if}

                                            {* If userCount equals currentUserCount disable the button *}
                                            {* ELSE enable the button *}
                                            {if $userCount == $currentUserCount}
                                                <li class="active">
                                                    <a href="#">{$number}</a>
                                                </li>
                                            {else}
                                                <li>
                                                    <a href="/talents/p=myTalents/myTalents={$userCount}/allTalents={$currentTalentCount}/createTalent={$currentRequestedCount}">
                                                        <span aria-hidden="true">{$userCount}</span>
                                                    </a>
                                                </li>
                                            {/if}
                                        {/if}

                                        {* if currentUserCount is greater than or the same as userCount disable the next button *}
                                        {* ELSE enable the button *}
                                        {if $currentUserCount >= $userCount}
                                            <li class="disabled">
                                                <a href="#" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        {else}
                                            <li>
                                                <a href="/talents/p=myTalents/myTalents={$currentUserCount + 1}/allTalents={$currentTalentCount}/createTalent={$currentRequestedCount}" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        {/if}
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    {/if}
                </div>
                <div class="tab-pane{if $page == "allTalents"} fade in active{/if}" id="tab2">
                    <form class="col-xs-12 col-sm-12 col-md-12 col-lg-12" action="/talents" method="get">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <input class="form-control" name="searchAll" placeholder="Zoek een talent"{if !Empty($searchAll)}value="{$searchAll}"{/if}>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <button type="submit" class="btn btn-primary">Zoek</button>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <a href="/talents/p=allTalents" class="btn btn-warning">Alle</a>
                        </div>
                    </form>

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
                                <td class="col-xs-12 col-sm-12 col-md-12 col-lg-12">{htmlentities(trim($talent->name),ENT_QUOTES)}</td>
                                <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <form action="/talents/action=addTalent" method="get">
                                        <input type="hidden" name="talent" value="{htmlentities(trim($talent->id),ENT_QUOTES)}"/>
                                        <button type="submit" class="btn btn-add btn-sm">
                                            <span class="glyphicon glyphicon-ok"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                    {* if there are more than 10 talents show pagination *}
                    {if $talentCount > 1}

                        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="col-xs-offset-4">
                                <nav>
                                    <ul class="pagination">

                                        {* if currentTalentCount is smaller than or same as 1 disable the previous button *}
                                        {* ELSE enable the previous button *}
                                        {if $currentTalentCount <= 1}
                                            <li class="disabled">
                                                <a href="#" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                        {else}
                                            <li>
                                                <a href="/talents/p=allTalents/myTalents={$currentUserCount}/allTalents={$currentTalentCount - 1}/createTalent={$currentRequestedCount}" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                        {/if}

                                        {* If talentCount is smaller than seven show normal pagination (1,2,3,4,5,6) *}
                                        {* ELSE show pagination for larger (first,...,3,4,5,...,last) *}
                                        {if $talentCount < 7}

                                            {* Loop the number of talentCount *}
                                            {for $number=1 to $talentCount}

                                                {* if the number is the same as currentTalentCount disable the button *}
                                                {* ELSE enable the button *}
                                                {if $number == $currentTalentCount}
                                                    <li class="active">
                                                        <a href="#">{$number}</a>
                                                    </li>
                                                {else}
                                                    <li>
                                                        <a href="/talents/p=allTalents/myTalents={$currentUserCount}/allTalents={$number}/createTalent={$currentRequestedCount}">{$number}</a>
                                                    </li>
                                                {/if}
                                            {/for}
                                        {else}

                                            {* If currentTalentCount equals 1 disable the first button *}
                                            {* ELSE enable the first button *}
                                            {if 1 == $currentTalentCount}
                                                <li class="active">
                                                    <a href="#">1</a>
                                                </li>
                                            {else}
                                                <li>
                                                    <a href="/talents/p=allTalents/myTalents={$currentUserCount}/allTalents=1/createTalent={$currentRequestedCount}">1</a>
                                                </li>
                                            {/if}

                                            {* If currentTalentCount is smaller than 4 show pagination like this (1,2,3,4,...,last) *}
                                            {if $currentTalentCount < 4}

                                                {* loop from 2 until 4 *}
                                                {for $number=2 to 4}

                                                    {* If the number equals page than disable the button *}
                                                    {* ELSE enable the button *}
                                                    {if $number == $currentTalentCount}
                                                        <li class="active">
                                                            <a href="#">{$number}</a>
                                                        </li>
                                                    {else}
                                                        <li>
                                                            <a href="/talents/p=allTalents/myTalents={$currentUserCount}/allTalents={$number}/createTalent={$currentRequestedCount}">{$number}</a>
                                                        </li>
                                                    {/if}
                                                {/for}

                                                <li class="disabled">
                                                    <a href="#">...</a>
                                                </li>

                                                {* if currentTalentCount is greater than talentCount minus 3 (for example currentTalentCount is 8 and talentCount(10) - 3 is 7) *}
                                            {elseif $currentTalentCount > ($talentCount - 3)}

                                                <li class="disabled">
                                                    <a href="#">...</a>
                                                </li>

                                                {* Loop through the last four numbers until the last minus one *}
                                                {for $number=($talentCount - 3) to ($talentCount - 1)}

                                                    {* if number equals currentTalentCount disable the button *}
                                                    {* ELSE enable the button *}
                                                    {if $number == $currentTalentCount}
                                                        <li class="active">
                                                            <a href="#">{$number}</a>
                                                        </li>
                                                    {else}
                                                        <li>
                                                            <a href="/talents/p=allTalents/myTalents={$currentUserCount}/allTalents={$number}/createTalent={$currentRequestedCount}">{$number}</a>
                                                        </li>
                                                    {/if}
                                                {/for}

                                                {* ELSE the pagination will be shown like this (first,...,3,4,5,...,last) *}
                                            {else}
                                                <li class="disabled">
                                                    <a href="#">...</a>
                                                </li>

                                                {* Loop from currentTalentCount minus one until currentTalentCount plus one *}
                                                {for $number=($currentTalentCount - 1) to ($currentTalentCount + 1)}

                                                    {* if number equals currentTalentCount than disable the button *}
                                                    {* ELSE enable the button *}
                                                    {if $number == $currentTalentCount}
                                                        <li class="active">
                                                            <a href="#">{$number}</a>
                                                        </li>
                                                    {else}
                                                        <li>
                                                            <a href="/talents/p=allTalents/myTalents={$currentUserCount}/allTalents={$number}/createTalent={$currentRequestedCount}">{$number}</a>
                                                        </li>
                                                    {/if}
                                                {/for}
                                                <li class="disabled">
                                                    <a href="#">...</a>
                                                </li>
                                            {/if}

                                            {* If talentCount equals currentTalentCount disable the button *}
                                            {* ELSE enable the button *}
                                            {if $talentCount == $currentTalentCount}
                                                <li class="active">
                                                    <a href="#">{$number}</a>
                                                </li>
                                            {else}
                                                <li>
                                                    <a href="/talents/p=allTalents/myTalents={$currentUserCount}/allTalents={$talentCount}/createTalent={$currentRequestedCount}">
                                                        <span aria-hidden="true">{$talentCount}</span>
                                                    </a>
                                                </li>
                                            {/if}
                                        {/if}

                                        {* if currentTalentCount is greater than or the same as talentCount disable the next button *}
                                        {* ELSE enable the button *}
                                        {if $currentTalentCount >= $talentCount}
                                            <li class="disabled">
                                                <a href="#" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        {else}
                                            <li>
                                                <a href="/talents/p=allTalents/myTalents={$currentUserCount}/allTalents={$currentTalentCount + 1}/createTalent={$currentRequestedCount}" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        {/if}
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    {/if}
                </div>
                <div class="tab-pane{if $page == "createTalent"} fade in active{/if}" id="tab3">
                    <div class="col-md-10">
                        <form class="col-xs-12 col-sm-12 col-md-12 col-lg-12" action="/talents/action=createTalent" method="get">
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 form-control-label">Naam talent</label>
                                <div class="col-sm-10">
                                    <input type="text" name="talent" class="form-control" id="name" placeholder="Naam" value="{if !Empty($talentName)}{$talentName}{/if}">
                                    <small class="text-muted">Dit is de naam van het talent. Deze naam moet voldoen aan de algemene voorwaarden.</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-add">
                                        Aanvragen
                                    </button>
                                </div>
                            </div>
                        </form>

                        <table class="table">
                            <thead>
                            <tr>
                                <th>Aangevraagde talenten</th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach from=$requestedTalents item=talent}
                                <tr>
                                    <td class="col-xs-12 col-sm-12 col-md-12 col-lg-12">{$talent -> name}</td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                        {* if there are more than 10 talents show pagination *}
                        {if $requestedCount > 1}

                            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="col-xs-offset-4">
                                    <nav>
                                        <ul class="pagination">

                                            {* if currentRequestedCount is smaller than or same as 1 disable the previous button *}
                                            {* ELSE enable the previous button *}
                                            {if $currentRequestedCount <= 1}
                                                <li class="disabled">
                                                    <a href="#" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                            {else}
                                                <li>
                                                    <a href="/talents/p=createTalent/myTalents={$currentUserCount}/allTalents={$currentTalentCount}/createTalent={$currentRequestedCount - 1}" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                            {/if}

                                            {* If requestedCount is smaller than seven show normal pagination (1,2,3,4,5,6) *}
                                            {* ELSE show pagination for larger (first,...,3,4,5,...,last) *}
                                            {if $requestedCount < 7}

                                                {* Loop the number of requestedCount *}
                                                {for $number=1 to $requestedCount}

                                                    {* if the number is the same as currentRequestedCount disable the button *}
                                                    {* ELSE enable the button *}
                                                    {if $number == $currentRequestedCount}
                                                        <li class="active">
                                                            <a href="#">{$number}</a>
                                                        </li>
                                                    {else}
                                                        <li>
                                                            <a href="/talents/p=createTalent/myTalents={$currentUserCount}/allTalents={$currentTalentCount}/createTalent={$number}">{$number}</a>
                                                        </li>
                                                    {/if}
                                                {/for}
                                            {else}

                                                {* If currentRequestedCount equals 1 disable the first button *}
                                                {* ELSE enable the first button *}
                                                {if 1 == $currentRequestedCount}
                                                    <li class="active">
                                                        <a href="#">1</a>
                                                    </li>
                                                {else}
                                                    <li>
                                                        <a href="/talents/p=createTalent/myTalents={$currentUserCount}/allTalents={$currentTalentCount}/createTalent=1">1</a>
                                                    </li>
                                                {/if}

                                                {* If currentRequestedCount is smaller than 4 show pagination like this (1,2,3,4,...,last) *}
                                                {if $currentRequestedCount < 4}

                                                    {* loop from 2 until 4 *}
                                                    {for $number=2 to 4}

                                                        {* If the number equals page than disable the button *}
                                                        {* ELSE enable the button *}
                                                        {if $number == $currentRequestedCount}
                                                            <li class="active">
                                                                <a href="#">{$number}</a>
                                                            </li>
                                                        {else}
                                                            <li>
                                                                <a href="/talents/p=createTalent/myTalents={$currentUserCount}/allTalents={$currentTalentCount}/createTalent={$number}">{$number}</a>
                                                            </li>
                                                        {/if}
                                                    {/for}

                                                    <li class="disabled">
                                                        <a href="#">...</a>
                                                    </li>

                                                    {* if currentRequestedCount is greater than requestedCount minus 3 (for example currentRequestedCount is 8 and requestedCount(10) - 3 is 7) *}
                                                {elseif $currentRequestedCount > ($requestedCount - 3)}

                                                    <li class="disabled">
                                                        <a href="#">...</a>
                                                    </li>

                                                    {* Loop through the last four numbers until the last minus one *}
                                                    {for $number=($requestedCount - 3) to ($requestedCount - 1)}

                                                        {* if number equals currentRequestedCount disable the button *}
                                                        {* ELSE enable the button *}
                                                        {if $number == $currentRequestedCount}
                                                            <li class="active">
                                                                <a href="#">{$number}</a>
                                                            </li>
                                                        {else}
                                                            <li>
                                                                <a href="/talents/p=createTalent/myTalents={$currentUserCount}/allTalents={$currentTalentCount}/createTalent={$number}">{$number}</a>
                                                            </li>
                                                        {/if}
                                                    {/for}

                                                    {* ELSE the pagination will be shown like this (first,...,3,4,5,...,last) *}
                                                {else}
                                                    <li class="disabled">
                                                        <a href="#">...</a>
                                                    </li>

                                                    {* Loop from currentRequestedCount minus one until currentRequestedCount plus one *}
                                                    {for $number=($currentRequestedCount - 1) to ($currentRequestedCount + 1)}

                                                        {* if number equals currentRequestedCount than disable the button *}
                                                        {* ELSE enable the button *}
                                                        {if $number == $currentRequestedCount}
                                                            <li class="active">
                                                                <a href="#">{$number}</a>
                                                            </li>
                                                        {else}
                                                            <li>
                                                                <a href="/talents/p=createTalent/myTalents={$currentUserCount}/allTalents={$currentTalentCount}/createTalent={$number}">{$number}</a>
                                                            </li>
                                                        {/if}
                                                    {/for}
                                                    <li class="disabled">
                                                        <a href="#">...</a>
                                                    </li>
                                                {/if}

                                                {* If requestedCount equals currentRequestedCount disable the button *}
                                                {* ELSE enable the button *}
                                                {if $requestedCount == $currentRequestedCount}
                                                    <li class="active">
                                                        <a href="#">{$number}</a>
                                                    </li>
                                                {else}
                                                    <li>
                                                        <a href="/talents/p=createTalent/myTalents={$currentUserCount}/allTalents={$currentTalentCount}/createTalent={$requestedCount}">
                                                            <span aria-hidden="true">{$requestedCount}</span>
                                                        </a>
                                                    </li>
                                                {/if}
                                            {/if}

                                            {* if currentRequestedCount is greater than or the same as requestedCount disable the next button *}
                                            {* ELSE enable the button *}
                                            {if $currentRequestedCount >= $requestedCount}
                                                <li class="disabled">
                                                    <a href="#" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            {else}
                                                <li>
                                                    <a href="/talents/p=createTalent/myTalents={$currentUserCount}/allTalents={$currentTalentCount}/createTalent={$currentRequestedCount + 1}" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            {/if}
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Remove-->
{foreach from=$talentsUser item=talent}
    <div id="myModal{preg_replace('/\s+/', '', htmlentities(trim($talent->id),ENT_QUOTES))}" class="modal fade"
         role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Talent verwijderen</h4>
                </div>
                <div class="modal-body">
                    <p>
                        Weet u zeker dat u het talent "{htmlentities(trim($talent->name),ENT_QUOTES)}" wilt
                        verwijderen?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default infoLeft"
                            data-dismiss="modal">Sluiten
                    </button>
                    <form action="/talents/action=removeTalent" method="get">
                        <input type="hidden" name="talent" value="{htmlentities(trim($talent->id),ENT_QUOTES)}"/>
                        <button type="submit" class="btn btn-inbox info">
                            <span class="glyphicon glyphicon-remove"></span> Verwijderen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
{/foreach}