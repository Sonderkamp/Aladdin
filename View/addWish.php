<?php
/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 02-03-16
 * Time: 22:29
 */
?>


<div id="wishcontaier" class="container" xmlns="http://www.w3.org/1999/html">

    {if isset($wishError)}
    <div class="form-error" id="err">{htmlspecialchars($wishError)}</div>
    {else}
    <div id="err"></div>
    {/if}

    {if isset($edit)}
    <form action="/wishes/action=editwish" method="get">
        {else}
        <form action="/wishes/action=addwish" method="get">
            {/if}
            <div class="form-group row">
                <label class="col-sm-2 form-control-label">Title:</label>
                <div class="col-sm-10">
                    {if isset($wishtitle)}
                    <input class="form-control" name="title" placeholder="Wat is uw wens" value="{($wishtitle)}">
                    {else}
                    <input class="form-control" name="title" placeholder="Wat is uw wens">
                    {/if}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 form-control-label">Uitgebreide wens beschrijving:</label>
                <div class="col-sm-10">
                    {if isset($description)}
            <textarea class="form-control" rows="5" name="description"
                      placeholder="Beschrijf hier uw wens uitgebreid">{($description)}</textarea>
                    {else}
            <textarea class="form-control" rows="5" name="description"
                      placeholder="Beschrijf hier uw wens uitgebreid"></textarea>
                    {/if}
                </div>
            </div>

            <div class="form-group row authors-list">
                <label class="col-sm-2 form-control-label">Tags:</label>
                <div class="col-sm-10">
                <select class="form-control">

                    {foreach from=$allTags item=tag}
                    <option>{$tag}</option>
                    {/foreach}

                </select>
                    </div>
            </div>

            <div class="form-group row authors-list">
                <label class="col-sm-2 form-control-label">Of typ zelf een tag in:</label>
                <div class="col-sm-10">
                    <input class="form-control" name="tag" placeholder="Tag">
                    <small class="text-muted-primary">voorbeelden: #voetbal #boer.</small>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 form-control-label">Plaats:</label>
                <div class="col-sm-10">
                    {if isset($city)}
                    <input class="form-control" value="{($city)}" name="city"
                           placeholder="De plaats waar u de wens wilt vervullen">
                    <small class="text-muted-primary">Niet verplicht.</small>
                </div>
                {else}
                <input class="form-control" name="city" placeholder="De plaats waar u de wens wilt vervullen">
                <small class="text-muted-primary">Niet verplicht.</small>
            </div>
            {/if}
</div>

<div class="form-group row">
    <label class="col-sm-2 form-control-label">Land:</label>
    {if isset($country)}
    <div class="col-sm-10">
        <input class="form-control" name="country" data-validation="country"
               placeholder="Het land waarin u de wens wilt vervullen" value="{($country)}">
    </div>
    {else}
    <div class="col-sm-10">
        <input class="form-control" name="country" data-validation="country"
               placeholder="Het land waarin u de wens wilt vervullen" value="Netherlands">
    </div>
    {/if}
</div>


{if isset($error)}
<div class="form-error" id="err">Error: {htmlspecialchars($error)}</div>
{else}
<div id="err"></div>
{/if}
<div id="error"></div>

<a>
    <button type="submit" class="btn btn-primary">
        Bevestig
    </button>
</a>

<a href="/Wishes/action=go_back">
    <button type=button class="btn btn-default side-button">
        Terug
    </button>
</a>
</form>

</div>


<script>
    // http://www.formvalidator.net/
    $.validate({
        modules: 'location, security, date',
        onModulesLoaded: function () {
            $('input[name="country"]').suggestCountry();
        }
    });

</script>

