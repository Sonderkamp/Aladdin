<div class="col-xs-3 right">
    <div class="dropdown">
        {if $currentPage == "myWishes"}
            <span class="glyphicon glyphicon-user"></span>
            {htmlspecialcharsWithNL($wish -> user -> displayName)}
        {else}
            {if ($wish -> user -> displayName) != $displayName}
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                   role="button"
                   aria-haspopup="true"
                   aria-expanded="false">
                    <span class="glyphicon glyphicon-user">{htmlspecialcharsWithNL($wish -> user -> displayName)}</span>
                    <span class="caret"></span>
                </a>
                {if in_array(($wish -> user -> displayName),$reported)}
                    <ul class="dropdown-menu">
                        <li>
                            U heeft deze gebruiker gerapporteerd.
                        </li>
                    </ul>
                {else}
                    <ul class="dropdown-menu">
                        <li>
                            <a data-toggle="modal"
                               data-target="#myModal{preg_replace('/\s+/', '', $wish->id)}">
                                Rapporteren
                            </a>
                        </li>
                    </ul>
                {/if}
            {else}
                <a class="dropdown-toggle" data-toggle="dropdown" role="button"
                   aria-haspopup="true"
                   aria-expanded="false">
                                                       <span class="glyphicon glyphicon-user">
                                                           {htmlspecialcharsWithNL($wish -> user -> displayName)}
                                                       </span>
                </a>
            {/if}
        {/if}

    </div>
    Stad: <b>{htmlspecialcharsWithNL($wish -> user -> city)}</b><br>
    Status: <b>{htmlspecialcharsWithNL($wish -> status)}</b>
</div>

<div id="myModal{preg_replace('/\s+/', '', $wish->id)}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Rapporteren van gebruiker <span
                            class="glyphicon glyphicon-user"></span>{htmlspecialcharsWithNL($wish -> user -> displayName)}
                </h4>
            </div>
            <form action="/report/action=report" method="post">
                <div class="modal-body">

                    <div class="form-group">
                        <p>
                        <div class="col-xs-3">
                            Reden:
                        </div>
                        <div class="col-xs-9">
                            <input type="hidden" value="{$wish->id}" name="wish_id"/>
                            <input type="text" class="form-control"
                                   placeholder="Reden dat u {{htmlspecialcharsWithNL($wish -> user -> displayName)}} wilt rappoteren"
                                   name="report_message">
                        </div>
                        </p>
                        <br>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Annuleren
                    </button>
                    <button type="submit" name="submit" class="btn btn-inbox info">
                        <span class="glyphicon glyphicon-remove"></span> Bevestigen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="wishModal{$wish->id}" class="container modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Wens overzicht</h4>
            </div>
            <div class="modal-body">

                <div class="form-group">

                    <div class="row">
                        <label class="col-sm-4">Id: </label>
                        <div class="col-sm-8">{htmlspecialchars($wish->id)}</div>
                    </div>

                    <div class="row">
                        <label class="col-sm-4">Title: </label>
                        <div class="col-sm-8">{htmlspecialcharsWithNL($wish->title)}</div>
                    </div>

                    <div class="row">
                        <label class="col-sm-4">Datum: </label>
                        <div class="col-sm-8">{$wish->contentDate}</div>
                    </div>

                    <div class="row">
                        <label class="col-sm-4">Wenser: </label>
                        <div class="col-sm-8">{htmlspecialcharsWithNL($wish->user->displayName)}</div>
                    </div>

                    <div class="row">
                        <label class="col-sm-4">Status: </label>
                        <div class="col-sm-8">{$wish->status}</div>
                    </div>

                    <div class="row">
                        <label class="col-sm-4">Plaats: </label>
                        <div class="col-sm-8">{htmlspecialcharsWithNL($wish->user->city)}</div>
                    </div>

                </div>
            </div>
            <strong>Content: </strong>
            <p>{htmlspecialcharsWithNL($wish->content)}</p>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Sluiten
                </button>
            </div>
        </div>
    </div>
</div>