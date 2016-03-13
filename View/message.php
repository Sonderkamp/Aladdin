<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 2-3-2016
 * Time: 00:39
 */
?>
<div class="container">


    {if isset($search)}
    <h1>{$title} - {$search}</h1>
    {else}
    <h1>{$title}</h1>
    {/if}
    <h3>{$folder}</h3>

    {if !isset($search)}
    {assign var="search" value=""}
    {/if}

    <div class="col-sm-3 hidden-xs">
        <a href="\Inbox\p={$page}\action=new" class="btn btn-default" style="width:100%">Nieuw Bericht</a><br>
        <br>
        {if isset($in)}
        <a href="\Inbox\p={$page}" class="btn btn-default active" style="width:100%">Postvak in</a><br>
        {else}
        <a href="\Inbox\p={$page}" class="btn btn-default" style="width:100%">Postvak in</a><br>
        {/if}
        {if isset($out)}
        <a href="\Inbox\folder=outbox\p={$page}" class="btn btn-default active" style="width:100%">Postvak uit</a><br>
        {else}
        <a href="\Inbox\folder=outbox\p={$page}" class="btn btn-default" style="width:100%">Postvak uit</a><br>
        {/if}
        {if isset($trash)}
        <a href="\Inbox\folder=trash\p={$page}" class="btn btn-default active" style="width:100%">Prullenbak</a><br>
        {else}
        <a href="\Inbox\folder=trash\p={$page}" class="btn btn-default" style="width:100%">Prullenbak</a><br>
        {/if}
        <br><br>
    </div>

    <div class="col-sm-9 ">
          <span class="info"><button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal"> <span class="glyphicon glyphicon-info-sign"></span>
              </button></span>
        <div class="hidden-xs">
            <form class="user-control form-inline" action="/Inbox/folder={$folderShortcut}/p={$page}" method="get">
                <input class="form-control white" value="{$search}" placeholder="Zoek Criteria" name="search"
                       type="text">
                <button class="form-control btn-inbox" type="submit">Zoek</button>
            </form>
        </div>

        <br><br>
        {if isset($error)}
        <div id="err">
            <div class="form-error">Error: {htmlspecialchars($error)}</div>
        </div>
        {else}
        <p id="err"></p>
        {/if}
        {if $message->adminSender}
            <div class="panel panel-default adminMessage">
        {else}
            <div class="panel panel-default">
        {/if}
            <div class="panel-body message">
                <p>
                    <span class="h3 title">{$message->title}</span>
                    {if $message->adminSender}
                     <span class="info">Van: <span class="glyphicon glyphicon-eye-open"></span> {$message->sender}</span>
                    {else}
                    <span class="info">Van: {$message->sender} </span>
                    {/if}
                    <br><span class="info">Naar: {$message->receiver} </span>
                    <br><span class="info">{$message->date} </span>
                </p>

                <br>
                <span>{$message->content}</span><br><br>
            </div>
            <div class="panel-footer">
                {if $message->folder == "trash"}
                <form class=noPadding action="\Inbox\folder={$folderShortcut}\p={$page}" method="post">
                    <input type="hidden" name="delete" value="{$message->id}"/>
                    <button type="submit" class="btn btn-inbox">Permanent Verwijderen</button>
                </form>
                <form class=noPadding action="\Inbox\folder={$folderShortcut}\p={$page}" method="post">
                    <input type="hidden" name="reset" value="{$message->id}"/>
                    <button type="submit" class="btn btn-inbox">Terugzetten</button>
                </form>
                {else}
                <form class=noPadding action="\Inbox\folder={$folderShortcut}\p={$page}" method="post">
                    <input type="hidden" name="trash" value="{$message->id}"/>
                    <button type="submit" class="btn btn-inbox">Verwijderen</button>
                </form>
                {/if}
                <form class=noPadding action="\Inbox\folder={$folderShortcut}\p={$page}" method="post">
                    <input type="hidden" name="reply" value="{$message->id}"/>
                    <button type="submit" class="btn btn-inbox">Beantwoorden</button>
                </form>
                <span class="info"><a class="btn btn-inbox">Rapporteren</a></span>
            {if isset($message->links)}
                {foreach from=$message->links item=link}
                    {if $link->action == "Talent"}
                        <a href="/Talents" class="btn btn-inbox">Mijn Talenten</a>
                    {else if $link->action == "Wens"}
                        <a href="/WishLINKTODO" class="btn btn-inbox">Bekijk wens</a>
                    {else if $link->action == "PaginaLink"}
                        <a href="{$link->content}" class="btn btn-inbox">Naar Pagina</a>
                    {else if $link->action == "Bericht"}
                        <a href="/Inbox/p={$page}/message={$link->content}" class="btn btn-inbox">Naar Bericht</a>
                    {/if}
                {/foreach}
            {/if}
            </div>
        </div>
        <!--<span><button>Volgende Pagina</button></span><span class="info">Pagina 1</span> -->
    </div>

    <div class="col-xs-12 hidden-sm hidden-md hidden-lg">
        {if isset($in)}
        <a href="\Inbox" class="btn btn-default active" style="width:100%">Postvak in</a><br>
        {else}
        <a href="\Inbox" class="btn btn-default" style="width:100%">Postvak in</a><br>
        {/if}
        {if isset($out)}
        <a href="\Inbox\folder=outbox" class="btn btn-default active" style="width:100%">Postvak uit</a><br>
        {else}
        <a href="\Inbox\folder=outbox" class="btn btn-default" style="width:100%">Postvak uit</a><br>
        {/if}
        {if isset($trash)}
        <a href="\Inbox\folder=trash" class="btn btn-default active" style="width:100%">Prullenbak</a><br>
        {else}
        <a href="\Inbox\folder=trash" class="btn btn-default" style="width:100%">Prullenbak</a><br>
        {/if}
        <br>
        <a href="\Inbox\action=new" class="btn btn-default" style="width:100%">Nieuw Bericht</a><br>
        <br>
        <form class="user-control form-inline" action="/Inbox/folder={$folderShortcut}" method="get">
            <input class="form-control white" value="{$search}" placeholder="Zoek Criteria" name="search"
                   type="text">
            <button class="form-control btn-inbox" type="submit">Zoek</button>
        </form>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Inbox</h4>
            </div>
            <div class="modal-body">
                <p>Dit is uw persoonlijke berichtencentrum. Hier vindt u twee soorten berichten:</p>
                <div class="panel panel-default adminMessage">
                    <div class="panel-body">
                        <a href="#" class="title">Titel</a> <span
                            class="info">
                             <span class="glyphicon glyphicon-eye-open"></span> Moderator
                   <br>2 maart 2016</span>
                        <br>
                        <span>Uw talentaanvraag voor "Docent" is geaccepteerd.</span><br><br>

                        <a class="btn btn-inbox">Verwijderen</a>
                        <a class="btn btn-inbox">Beantwoorden</a>
                    </div>
                </div>

                <p>
                    Hierboven ziet u een bericht van een moderator. Deze zijn bruin gekleurd. Ook heeft de moderator
                    een klein oog-icoon naast zijn naam staan.
                </p>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="#" class="title">Hallo {$user->displayName}</a> <span
                            class="info">
                             M. de Vogel
                   <br>2 maart 2016</span>
                        <br>
                        <span>Ik heb jouw wensaanvraag gezien, En volgens mij kan ik jou hiermee helpen...</span><br><br>

                        <a class="btn btn-inbox">Verwijderen</a>
                        <a class="btn btn-inbox">Beantwoorden</a>
                    </div>
                </div>

                <p>
                    Hierboven ziet u een bericht van een gebruiker. Deze zijn wit.
                </p>
                <p>
                    Je kan de berichten volledig bekijken door op de titel van het bericht te klikken.
                    Hier heeft u ook de mogelijkheid om berichten te rapporteren of om gebruikers te blokkeren.
                    Je hebt onder het bericht ook acties naar het snel beantwoorden en het verwijderen van dit bericht.
                </p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
            </div>
        </div>

    </div>
</div>