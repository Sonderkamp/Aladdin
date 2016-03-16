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

            <div class="row">
                <label class="col-sm-4">Datum: </label>
                <div class="col-sm-8">{$selectedWish->date}</div>
            </div>

            <div class="row">
                <label class="col-sm-4">Wenser: </label>
                <div class="col-sm-8">{$selectedWish->userDisplayName}</div>
            </div>

            <div class="row">
                <label class="col-sm-4">Status: </label>
                <div class="col-sm-8">{$selectedWish->status}</div>
            </div>

            <div class="row">
                <label class="col-sm-4">Stad: </label>
                <div class="col-sm-8">{$selectedWish->userCity}</div>
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