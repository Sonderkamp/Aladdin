{*/***}
{** Created by PhpStorm.*}
{** User: Max*}
{** Date: 08/03/2016*}
{** Time: 20:40*}
{**/*}

<div class="container">

    {if isset($wishError)}
        <div class="form-error" id="err">Error: {htmlspecialcharsWithNL($wishError)}</div>
    {else}
        <div id="err"></div>
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
                                <a href="/wishes/action=requestMatch?Id={$selectedWish->id}"
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

    <div class="row">

        <div class="col-xs-3 small-margin-bot">
            {if (!isset($adminView))}
                <ul class="nav nav-pills nav-stacked">


                    <li>
                        <a class="btn btn-side btn-default"
                           href="{if !empty($returnPage)}{$returnPage}{else}/Wishes/action=back{/if}">
                            Go Back
                        </a>
                    </li>

                    {if $canMatch}
                        <li>
                            <a class="btn btn-side btn-default" data-toggle="modal" data-target="#matchModal">
                                Match
                            </a>
                        </li>
                    {else}
                        <li>
                            <strong>Het is niet mogelijk om met deze wens te matchen</strong>
                        </li>
                    {/if}
                </ul>
            {/if}
        </div>
        {if (!isset($adminView))}
        <div class="col-xs-9 panel panel-default">
            {else}
            <div class="col-xs-12 panel panel-default">
                {/if}
                <h3 class="text-center">{htmlspecialcharsWithNL($selectedWish->title)}</h3>
                <br>
                <div class="row">
                    <div class="col-xs-4 form-group">
                        <div class="row">
                            <label class="col-xs-4">Datum: </label>
                            <div class="col-xs-8">{$selectedWish->contentDate}</div>
                        </div>

                        <div class="row">
                            <label class="col-xs-4">Wenser: </label>
                            <div class="col-xs-8">{htmlspecialcharsWithNL($selectedWish->user->displayName)}</div>
                        </div>

                        <div class="row">
                            <label class="col-xs-4">Status: </label>
                            <div class="col-xs-8">{$selectedWish->status}</div>
                        </div>

                        <div class="row">
                            <label class="col-xs-4">Plaats: </label>
                            <div class="col-xs-8">{htmlspecialcharsWithNL($selectedWish->user->city)}</div>
                        </div>
                    </div>

                    <div class="col-xs-8">
                        <p>{htmlspecialcharsWithNL($selectedWish->content)}</p>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-8 detailBox">
                <div class="titleBox">
                    <label>Reacties</label>
                </div>

                <div class="actionBox">
                    <ul class="commentList">
                        {foreach from=$comments item=comment}
                            <li>
                                <div class="commentText">
                                    {if (isset($adminView))}
                                        <form action="/wishes/action=editComment" method="post">
                                            <input type="hidden" name="wishId" value="{$selectedWish->id}" />
                                            <input type="hidden" name="creationDate" value="{$comment->dbDate}" />
                                            <input type="hidden" name="username" value="{$comment->displayName}" />
                                            <button type="submit" class="btn btn-default" name="removeButton" value="remove">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </button>
                                            <button type="submit" class="btn btn-inbox" name="addGuestbook" value="add">
                                                <span class="glyphicon glyphicon-book"></span>
                                            </button>
                                        </form>
                                    {/if}
                                    <p class="">{$comment->message}
                                        {if !empty($comment->image)}
                                            <a href="{$comment->image}" target="_blank">
                                                <img class="thumbnail commentImage" src="{$comment->image}">
                                            </a>
                                        {/if}
                                    </p>
                                    <span class="date sub-text">{$comment->displayName}
                                        op {$comment->creationDate}</span>

                                </div>
                            </li>
                        {/foreach}
                    </ul>
                    {if !$adminView}
                        {if $canComment}
                        <form class="form-inline"
                              action="/Wishes/Id={$selectedWish->id}/action=AddComment"
                              method="post"
                              enctype="multipart/form-data">
                            <div class="form-group">
                                <input class="form-control" name="img" type="file"/><br/>
                                <input class="form-control" type="text" name="comment" placeholder="Nieuwe Reactie"/>
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

            <div class="col-xs-4">
                <div class="col-md-10 col-md-offset-1 panel panel-default">
                    <h5>Geïnteresseerde gebruikers</h5>
                    {foreach from=$matches item=match}
                        {if !empty($matches)}
                            <div class="inner-border match-controls row">
                                <p class="col-xs-6">{$match->user->displayName}</p>
                                {if $selectedWish->user->email == $currentUser->email}

                                    {*<button type="button" class="col-xs-3 btn btn-confirm btn-default" data-toggle="modal" data-target="#profileModal{$wish->id}">*}
                                    {*<span class="glyphicon glyphicon-user"></span>*}
                                    {*</button>*}
                                    <a href="/wishes/action=selectMatch?wish={$match->wishId}"
                                       class="col-xs-3 btn btn-confirm btn-default">
                                        <span class="glyphicon glyphicon-ok"></span>
                                    </a>
                                {/if}

                                {if $match->isSelected}
                                    <span class="glyphicon glyphicon-ok"></span>
                                {/if}

                            </div>
                        {else}
                            <div class="inner-border">
                                <p>Er zijn nog geen matches</p>
                            </div>
                        {/if}
                    {/foreach}
                </div>
            </div>
        </div>

    </div>