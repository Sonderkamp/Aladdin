<?php
/**
 * Created by PhpStorm.
 * User: MevlutOzdemir
 * Date: 02-03-16
 * Time: 22:29
 */
?>

<img src="/Resources/Images/banner.jpg" class="img-responsive width background">
<div id="wishcontaier" class="container" xmlns="http://www.w3.org/1999/html">

    <div class="container">
     <span class="info infoButtonMargin">
            <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#InfoModal">
                <span class="glyphicon glyphicon-info-sign"></span>
            </button>
            </span>
    </div>
    {if isset($wishError)}
        <div class="form-error" id="err">{htmlspecialchars($wishError)}</div>
    {else}
        <div id="err"></div>
    {/if}

    {if isset($edit)}
    <form action="/wishes/action=editWish" method="post">
        {else}
        <form action="/wishes/action=addWish" method="post">
            {/if}
            <div class="form-group row">
                <label class="col-sm-2 form-control-label">Titel:</label>
                <div class="col-sm-10">
                    <input class="form-control" name="title" maxlength="45"
                           placeholder="Wat is uw wens" {if isset($wishtitle)} value="{($wishtitle)}" {/if}>
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label class="col-sm-2 form-control-label">Beschrijf je wens:<br><br>
                    Wat is er praktisch gezien nodig om deze wens in vervulling te laten gaan?<br><br>
                    Waar zou je zelf voor kunnen zorgen?
                </label>
                
                <div class="col-sm-10">
                    {*<textarea id="text2" placeholder="." rows="10"></textarea>​*}
                    <textarea class="form-control" rows="7" name="description"
                              placeholder="Uitgebreide wens beschrijving">{if isset($description)}{($description)}{/if}</textarea>
                    {*{else}*}
                    {*<textarea class="form-control" rows="5" name="description"*}
                    {*placeholder="Beschrijf hier uw wens uitgebreid"></textarea>*}
                    {*{/if}*}
                </div>
            </div>

            <br>
            <div class="form-group row authors-list">
                <label class="col-sm-2 form-control-label">Kernwoorden:</label>
                <div class="col-sm-10">

                    <input class="form-control" name="tag" placeholder="Kernwoorden"
                           {if isset($tag)}value="{($tag)}{/if}">
                    {*{else}*}
                    {*<input class="form-control" name="tag" placeholder="Tag"">*}
                    {*{/if}*}
                    <small class="text-muted-primary">voorbeelden: #voetbal #boer.</small>
                </div>
            </div>


            {if isset($tagerror)}
                <div class="form-error" id="err">Error: {htmlspecialchars($tagerror)}</div>
            {/if}

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


            <a href="/Wishes/action=back">
                <button type=button class="btn btn-default side-button">
                    Terug
                </button>
            </a>
        </form>

</div>

<div id="InfoModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Statistieken</h4>
            </div>
            <div class="modal-body">

                <p>
                    Op deze pagina kun u een nieuwe wens toevoegen of een bestaand wens wijzigen.<br><br>
                    <a><b>Titel</b></a><br>
                    - De titel van de wens<br><br>

                    <a><b>Uitgebreide wens beschrijving:</b></a><br>
                    - Beschrijf hier zo uitgebreid mogelijk uw wens, denk hierbij aan wat u wilt, hoe dit gedaan kan
                    worden en op welke plaats<br><br>

                    <a><b>Kernwoorden</b></a><br>
                    - Kernwoorden zijn woorden waarmee wij uw wensen kunnen koppelen aan potentiele vervullers.<br>
                    - Vul hier zoveel mogelijk kernwoorden in die met uw wens te maken hebben<br>
                    - Kernwoorden beginnen met een hashtag (#) op de toetsenbord kunt u dit doen met SHIFT+3<br><br>

                    <a><b>Voorbeeld</b></a><br>
                    <b>Titel:</b> Ballonvaart<br>
                    <b>Beschrijving:</b> Ik wil met mijn gezien een ballonvaart het liefst in de buurt van
                    Noord-Brabant. We zijn een gezin van 4, mijn kinderen zijn 6 en 8 jaar oud. Ik hoop dat er iemand is
                    die mijn wens kan vervullen.<br>
                    <b>Kernwoorden:</b> #ballonvaart #luchtballon


                </p>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Sluiten
                    </button>
                </div>
            </div>
        </div>
    </div>
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

