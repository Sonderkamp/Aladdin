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
        <h1 class="text-center">{$selectedWish->title}</h1>
    </div>

    <div class="row">

        <div class="col-sm-3 form-group">

            {if isset($previousPage)}
                <a href="http://{$previousPage}"><button class="btn btn-sm">
                Go back
                </button></a>
            {/if}

            <div class="row">
                <label class="col-sm-4">Datum: </label>
                <div class="col-sm-8">{$selectedWish->contentDate}</div>
            </div>

            <div class="row">
                <label class="col-sm-4">Wenser: </label>
                <div class="col-sm-8">{$selectedWish->user}</div>
            </div>

            <div class="row">
                <label class="col-sm-4">Status: </label>
                <div class="col-sm-8">{$selectedWish->status}</div>
            </div>

            <div class="row">
                <label class="col-sm-4">Stad: </label>
                <div class="col-sm-8">Misschien de stad van de user?</div>
            </div>

        </div>

        <div class="col-sm-6">

            <p>{$selectedWish->content}</p>

        </div>

    </div>


    {if isset($error)}
        <div class="form-error" id="err">Error: {htmlspecialchars($error)}</div>
    {else}
        <div id="err"></div>
    {/if}
    <div id="error"></div>

</div>