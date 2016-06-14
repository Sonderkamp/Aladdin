<div class="container">
    {if count($comments) > 0}
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            {for $comment=0 to count($comments) step 2}
                {if !empty($comments[$comment])}
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {if $isAdmin}
                                <form action="/guestbook/action=removeComment" method="post">
                                    <input type="hidden" name="wishId" value="{$comments[$comment]->wishId}" />
                                    <input type="hidden" name="creationDate" value="{$comments[$comment]->dbDate}" />
                                    <input type="hidden" name="username" value="{$comments[$comment]->displayName}" />
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
                                                <img class="thumbnail commentImage" src="{htmlspecialchars($comments[$comment]->image)}">
                                            </a>
                                        {/if}
                                    </p>
                                </div>
                                <div class="col-xs-3">
                                    <span class="date sub-text">{htmlspecialchars($comments[$comment]->displayName)} op {htmlspecialchars($comments[$comment]->creationDate)}</span>
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
                                    <input type="hidden" name="wishId" value="{$comments[$comment]->wishId}" />
                                    <input type="hidden" name="creationDate" value="{$comments[$comment]->dbDate}" />
                                    <input type="hidden" name="username" value="{$comments[$comment]->displayName}" />
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
                                                <img class="thumbnail commentImage" src="{htmlspecialchars($comments[$comment]->image)}">
                                            </a>
                                        {/if}
                                    </p>
                                </div>
                                <div class="col-xs-3">
                                    <span class="date sub-text">{htmlspecialchars($comments[$comment]->displayName)} op {htmlspecialchars($comments[$comment]->creationDate)}</span>
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