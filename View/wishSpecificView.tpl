{*/***}
{** Created by PhpStorm.*}
{** User: Max*}
{** Date: 08/03/2016*}
{** Time: 20:40*}
{**/*}

<div id="wishcontaier" class="container">

    {if isset($wishError)}
        <div class="form-error" id="err">Error: {htmlspecialchars($wishError)}</div>
    {else}
        <div id="err"></div>
    {/if}
    <div class="col-lg-12">
        <h1 class="text-center">{htmlspecialchars($selectedWish->title)}</h1>
    </div>

    <div class="col-log-12 small-margin-bot">
        <ul class="nav nav-pills">

            {if isset($previousPage)}
                <li>
                    <button class="btn btn-default button-color-green">
                        <a href="http://{$previousPage}">Go Back</a>
                    </button>
                </li>
            {/if}

            {if !$selectedWish->completed}
                <li>
                    <form method="post">
                        <button class="btn btn-default button-color-green"
                                formaction="/Wishes/match/wish_id={$wish->id}"
                                type="submit">
                            Match
                        </button>
                    </form>
                </li>
            {/if}
        </ul>
    </div>

    <div class="row">

        <div class="col-sm-3 form-group">

            <div class="row">
                <label class="col-sm-4">Datum: </label>
                <div class="col-sm-8">{$selectedWish->contentDate}</div>
            </div>

            <div class="row">
                <label class="col-sm-4">Wenser: </label>
                <div class="col-sm-8">{htmlspecialchars($selectedWish->user->displayName)}</div>
            </div>

            <div class="row">
                <label class="col-sm-4">Status: </label>
                <div class="col-sm-8">{$selectedWish->status}</div>
            </div>

            <div class="row">
                <label class="col-sm-4">Plaats: </label>
                <div class="col-sm-8">{htmlspecialchars($selectedWish->user->city)}</div>
            </div>


        </div>

        <div class="col-sm-6">

            <p>{htmlspecialchars($selectedWish->content)}</p>

        </div>

    </div>


    {if isset($error)}
        <div class="form-error" id="err">Error: {htmlspecialchars($error)}</div>
    {else}
        <div id="err"></div>
    {/if}
    <div id="error"></div>

</div>