<div class="container">
    {if $isAdmin}
        <span class="info col-sm-12">
            <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#infoGuestbook">
                <span class="glyphicon glyphicon-info-sign"></span>
            </button>
        </span>
    {/if}
    {if count($comments) > 0}
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            {for $comment=0 to count($comments) step 2}
                {if !empty($comments[$comment])}
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {if $isAdmin}
                                <form action="/guestbook/action=removeComment" method="post">
                                    <input type="hidden" name="wishId" value="{$comments[$comment]->wishId}"/>
                                    <input type="hidden" name="creationDate" value="{$comments[$comment]->dbDate}"/>
                                    <input type="hidden" name="username" value="{$comments[$comment]->displayName}"/>
                                    <button type="submit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </button>
                                </form>
                                <br>
                            {/if}
                            <div class="row">
                                <div class="col-xs-9">
                                    <p>{htmlspecialchars(trim($comments[$comment]->message))}
                                        {if !empty($comments[$comment]->image)}
                                            <a href="{htmlspecialchars($comments[$comment]->image)}" target="_blank">
                                                <img class="thumbnail commentImage"
                                                     src="{htmlspecialchars($comments[$comment]->image)}">
                                            </a>
                                        {/if}
                                    </p>
                                </div>
                                <div class="col-xs-3">
                                    <span class="date sub-text">{htmlspecialchars($comments[$comment]->displayName)}
                                        op {htmlspecialchars($comments[$comment]->creationDate)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                {/if}
            {/for}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            {for $comment=1 to count($comments) step 2}
                {if !empty($comments[$comment])}
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {if $isAdmin}
                                <form action="/guestbook/action=removeComment" method="post">
                                    <input type="hidden" name="wishId" value="{$comments[$comment]->wishId}"/>
                                    <input type="hidden" name="creationDate" value="{$comments[$comment]->dbDate}"/>
                                    <input type="hidden" name="username" value="{$comments[$comment]->displayName}"/>
                                    <button type="submit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </button>
                                </form>
                                <br>
                            {/if}
                            <div class="row">
                                <div class="col-xs-9">
                                    <p>{htmlspecialchars(trim($comments[$comment]->message))}
                                        {if !empty($comments[$comment]->image)}
                                            <a href="{htmlspecialchars($comments[$comment]->image)}" target="_blank">
                                                <img class="thumbnail commentImage"
                                                     src="{htmlspecialchars($comments[$comment]->image)}">
                                            </a>
                                        {/if}
                                    </p>
                                </div>
                                <div class="col-xs-3">
                                    <span class="date sub-text">{htmlspecialchars($comments[$comment]->displayName)}
                                        op {htmlspecialchars($comments[$comment]->creationDate)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                {/if}
            {/for}
        </div>
    {else}
        <h3>Helaas is het gastenboek nog leeg.</h3>
    {/if}
</div>

{if $isAdmin}
    <div id="infoGuestbook" class="modal fade"
         role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Informatie gastenboek beheren</h4>
                </div>
                <div class="modal-body">
                    <h5>Gastenboek beheren</h5>
                    <p>Hier worden alle reacties op vervulde wensen weergegeven die door een moderator zijn goedgekeurt
                        voor het gastenboek.</p>
                    <p>
                        <button type="submit" class="btn btn-default btn-small">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                        Hiermee word een reactie uit het gastenboek verwijderd. Dit verwijderd <b>NIET</b> de hele
                        reactie.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default infoLeft"
                            data-dismiss="modal">Sluiten
                    </button>
                </div>
            </div>
        </div>
    </div>
{/if}