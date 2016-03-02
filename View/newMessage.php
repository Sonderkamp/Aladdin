<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 2-3-2016
 * Time: 00:39
 */
?>
<div class="container">


    <h1>{$title}</h1>
    <h3>{$folder}</h3>
    <div class="col-sm-3">
        <a href="\Inbox\action=new" class="btn btn-default active" style="width:100%">Nieuw Bericht</a><br>
        <br>

        <a href="\Inbox" class="btn btn-default" style="width:100%">Postvak in</a><br>

        <a href="\Inbox\folder=outbox" class="btn btn-default" style="width:100%">Postvak uit</a><br>

        <a href="\Inbox\folder=trash" class="btn btn-default" style="width:100%">Prullenbak</a><br>
        <br><br>
    </div>

    <div class="col-sm-9">
        {if isset($error)}
        <div id="err">
            <div class="form-error">Error: {htmlspecialchars($error)}</div>
        </div>
        {else}
        <p id="err"></p>
        {/if}

        <form id="form" role="form" method="post" action="/Inbox/action=new">

            <div class="form-group">
                <label for="rec">Aan:</label>
                <select name="recipient" class="form-control" id="rec" data-validation="required"
                        data-validation-error-msg="Kies een ontvanger.">
                    <option></option>
                    {foreach from=$names item=name}
                    <option>{$name}</option>
                    {/foreach}
                </select>
            </div>
            <br>
            <div id="subjectGroup" class="form-group">
                <label class="control-label">Onderwerp:</label>
                <input name="title" class="form-control" id="subject" placeholder="Onderwerp" data-validation="required"
                       data-validation-error-msg="vul een onderwerp in.">
            </div>
            <br>
            <div id="message" class="form-group ">
                <label class="control-label">Bericht:</label>
                <textarea data-validation="required" data-validation-error-msg="vul een bericht in." name="message"
                          id="messageField" style="max-height:300px; max-width: 100%;"
                          class="form-control" placeholder="Bericht.."></textarea>
            </div>
            <button id="button" class="btn btn-default">Verzenden</button>
        </form>
    </div>
</div>
<script>

    var $messages = $('#err');
    // http://www.formvalidator.net/
    $.validate({
        validateOnBlur: false,
        errorMessagePosition: $messages,
        onValidate: function () {
            $messages.empty();
        }
    });

</script>
