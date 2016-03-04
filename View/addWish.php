<?php
/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 02-03-16
 * Time: 22:29
 */
?>


<div id="wishcontaier" class="container">

    {if isset($wishError)}
    <div class="form-error" id="err">Error: {htmlspecialchars($wishError)}</div>
    {else}
    <div id="err"></div>
    {/if}

    <form action="/wishes/action=addwish" method="get">

        <div class="form-group row">
            <label class="col-sm-2 form-control-label">Title:</label>
            <div class="col-sm-10">
                <input class="form-control" name="title" placeholder="Wat is uw wens">
            </div>
        </div>

        <div class="form-group">
            <label>Uitgebreide wens beschrijving:</label>
            <textarea class="form-control" rows="5" name="description"
                      placeholder="Beschrijf hier uw wens uitgebreid"></textarea>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 form-control-label">Tags:</label>
            <div class="col-sm-10">
                <input class="form-control" name="tag" placeholder="Tags" >
                <small class="text-muted-primary">voorbeelden: #parachutespringen #schaken.</small>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 form-control-label">Plaats:</label>
            <div class="col-sm-10">
                <input class="form-control" name="city" placeholder="De plaats waar u de wens wilt vervullen">
                <small class="text-muted-primary">Niet verplicht.</small>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 form-control-label">Land:</label>
            <div class="col-sm-10">
                <input class="form-control" name="country" data-validation="country"
                       placeholder="Het land waarin u de wens wilt vervullen" value="Netherlands">
            </div>
        </div>


        {if isset($error)}
        <div class="form-error" id="err">Error: {htmlspecialchars($error)}</div>
        {else}
        <div id="err"></div>
        {/if}
        <div id="error"></div>

        <button type="submit" class="btn btn-primary">Bevestig</button>
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