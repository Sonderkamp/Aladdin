{*/***}
{** Created by PhpStorm.*}
{** User: Max*}
{** Date: 08/03/2016*}
{** Time: 20:40*}
{**/*}

{assign var="rightCol" value=(($selectedWish->status == "Match gevonden" && !empty($currentUser) && $selectedWish->user->email == $currentUser->email)
|| ($selectedWish->status == "Wordt vervuld" && !empty($currentUser) && $selectedWish->user->email == $currentUser->email))}
<img src="/Resources/Images/banner.jpg" class="img-responsive width background">
<div class="container">

    {if isset($errorString)}
        <div class="alert alert-warning">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {$errorString}
        </div>
    {/if}

    <div id="matchModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Match bevesteging</h5>
                </div>

                <div class="modal-body">
                    <p>Weet je zeker dat je met deze wens wil matchen?</p>
                    <br>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 row">
                            <div class="col-xs-6">
                                <a href="/match/action=requestMatch?Id={$selectedWish->id}"
                                   class="btn btn-confirm btn-default">
                                    Ja
                                </a>
                            </div>
                            <div class="col-xs-6">
                                <button type="button" class="btn btn-confirm btn-default" data-dismiss="modal">
                                    Nee
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        {if (!$adminView)}
            <a class="btn btn-side btn-default"
               href="{if !empty($returnPage)}{$returnPage}{else}/Wishes/action=back{/if}">
                Go Back
            </a>
            {if $selectedWish->user->email == $user->email}
                <a class="btn btn-side btn-default" href="/wishes/action=openEditView?Id={$selectedWish->id}">
                    Wijzig wens
                </a>
            {/if}

            {if $canMatch }
                <a class="btn btn-side btn-default" data-toggle="modal" data-target="#matchModal">
                    Match
                </a>
            {elseif !empty($isMatched) && $isMatched && $selectedWish->status != "Vervuld"}
                <a href="/match/action=removeMatch?Id={$selectedWish->id}"
                   class="btn btn-side btn-default">
                    Trek match terug
                </a>
            {else}
                <strong class="errorcenter">Het is niet mogelijk om met deze wens te matchen</strong>
            {/if}

        {/if}
    </div>

    <div class="row panel panel-default">
        <h3 class="text-center">
            {htmlspecialcharsWithNL($selectedWish->title)}</h3>
        <div class="col-md-3">
            <div class="well">

                <div class="row">
                    <label class="col-xs-4">Datum: </label>
                    <div class="col-xs-8">{$selectedWish->contentDate}</div>
                </div>

                <div class="row">
                    <label class="col-xs-4">Wenser: </label>
                    <div class="col-xs-8">{htmlspecialcharsWithNL($selectedWish->user->displayName)}</div>
                </div>
                {if !empty($selectedWish->user->companyName)}
                    <div class="row">
                        <label class="col-xs-4">Bedrijf: </label>
                        <div class="col-xs-8">Ja</div>
                    </div>
                {/if}
                {if !empty($selectedWish->user->guardian)}
                    <div class="row">
                        <label class="col-xs-4">Voogd: </label>
                        <div class="col-xs-8">{$selectedWish->user->guardian}</div>
                    </div>
                {/if}

                <div class="row">
                    <label class="col-xs-4">Status: </label>
                    <div class="col-xs-8">{$selectedWish->status}</div>
                </div>

                <div class="row">
                    <label class="col-xs-4">Plaats: </label>
                    <div class="col-xs-8">{htmlspecialcharsWithNL($selectedWish->user->city)}</div>
                </div>


                {if !empty($selectedWish->user->handicapInfo)}
                    <div class="row">
                        <label class="col-xs-4">Beperking: </label>
                        <div class="col-xs-8"> {htmlspecialcharsWithNL($selectedWish->user->handicapInfo)}</div>
                    </div>
                {/if}
                {if !empty($selectedWish->completionDate)}
                    <div class="row">
                        <label class="col-xs-4">Vervuld datum: </label>
                        <div class="col-xs-8">{htmlspecialcharsWithNL($selectedWish->completionDate|date_format:"%d/%m/%y")}</div>
                    </div>
                {/if}
            </div>
            <div class="well">
                <h5>Geïnteresseerde gebruikers</h5>
                {foreach from=$matches item=match}
                    {if !empty($matches)}
                        <div class="match-controls row">
                            {if $match->isActive == 0}
                                <p class="col-xs-6"><s>{$match->user->displayName}</s></p>
                            {else}
                                <p class="col-xs-6">{$match->user->displayName}</p>
                            {/if}

                            {if !empty($currentUser) && $selectedWish->user->email == $currentUser->email && $match->isActive == 1 && !$match->isSelected}
                                <form action="/match/action=selectMatch" method="post">
                                    <input type="hidden" name="Id" value="{$match->wishId}">
                                    <input type="hidden" name="User" value="{$match->user->email}">
                                    <button type="submit" class="btn btn-confirm btn-default col-xs-3">
                                        <span class="glyphicon glyphicon-ok"></span>
                                    </button>
                                </form>
                            {/if}

                            {if $match->isSelected}
                                <span class="glyphicon glyphicon-ok"></span>
                            {/if}

                        </div>
                    {else}
                        <div>
                            <p>Er zijn nog geen matches</p>
                        </div>
                    {/if}
                {/foreach}
            </div>

        </div>

        {if ($rightCol)}
        <div class="col-md-5">
            {else}
            <div class="col-md-9">
                {/if}

                <span class="h5">
            Wens
            </span>
                <p class="well">{htmlspecialcharsWithNL($selectedWish->content)}</p>


                {if $selectedWish->status == "Vervuld"}
                    <div id="COMMENTS" class="well">
                        <div>
                            <label>Reacties</label>
                        </div>

                        <div class="actionBox">
                            <ul class="commentList">
                                {foreach from=$comments item=comment}
                                    <li>
                                        <div class="commentText">
                                            {if $adminView}
                                                <form action="/wishes/action=editComment" method="post">
                                                    <input type="hidden" name="wishId" value="{$selectedWish->id}"/>
                                                    <input type="hidden" name="creationDate"
                                                           value="{$comment->dbDate}"/>
                                                    <input type="hidden" name="username"
                                                           value="{$comment->displayName}"/>
                                                    <button type="submit" class="btn btn-default" name="removeButton"
                                                            value="remove">
                                                        <span class="glyphicon glyphicon-remove"></span>
                                                    </button>
                                                    {if $comment->inGuestbook == "0"}
                                                        <button type="submit" class="btn btn-inbox" name="addGuestbook"
                                                                value="add">
                                                            <span class="glyphicon glyphicon-book"></span>
                                                        </button>
                                                    {/if}
                                                </form>
                                            {/if}
                                            <p>{htmlspecialchars($comment->message)}
                                                {if !empty($comment->image)}
                                                    <a href="{$comment->image}" target="_blank">
                                                        <img class="thumbnail commentImage" src="{$comment->image}">
                                                    </a>
                                                {/if}
                                            </p>
                                    <span class="date sub-text">{htmlspecialchars($comment->displayName)}
                                        op {$comment->creationDate}</span>

                                        </div>
                                    </li>
                                {/foreach}
                            </ul>
                            {if (!$adminView)}
                                {if $canComment}
                                    <form class="form-inline"
                                          action="/Wishes/Id={$selectedWish->id}/action=AddComment"
                                          method="post"
                                          enctype="multipart/form-data">
                                        <div class="form-group">
                                            <input class="form-control" name="img" type="file"/><br/>
                                            <input class="form-control" type="text" name="comment"
                                                   placeholder="Nieuwe Reactie"/>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-default">Add</button>
                                        </div>
                                    </form>
                                {else}
                                    <span>Reageren is alleen mogelijk bij vervulde wensen</span>
                                {/if}
                            {else}
                                <span>Het is niet mogelijk voor admins om te reageren</span>
                            {/if}
                        </div>
                    </div>
                {/if}

            </div>
            {if ($rightCol)}
            <div class="col-md-4">

                {if ($selectedWish->status == "Match gevonden" || $selectedWish->status == "Wordt vervuld" )
                && !empty($currentUser) && $selectedWish->user->email == $currentUser->email}
                    <div class="well">
                        <form method="post" action="/wishes/action=setCompletionDate">

                            Vervul datum:<br>

                            <input type="date" class="form-group" required title="completionDate" name="completionDate">
                            <input type="hidden" name="Id" value="{$selectedWish->id}">

                            <button class="btn btn-default" type="submit">Bevestig</button>
                        </form>
                    </div>
                {/if}
                {if $selectedWish->status == "Wordt vervuld" && !empty($currentUser) && $selectedWish->user->email == $currentUser->email}
                    <div class="well">
                        <div>Vervul datum: {$selectedWish->completionDate|date_format:"%d/%m/%y"}</div>
                        <br>
                        <form method="post" action="/wishes/action=confirmCompletion">
                            <input type="hidden" name="completionDate" value="{$selectedWish->completionDate}">
                            <input type="hidden" name="Id" value="{$selectedWish->id}">
                            <button type="submit" class="btn btn-default">Markeer wens als vervuld</button>
                        </form>
                    </div>
                {/if}
                {/if}
            </div>
        </div>
