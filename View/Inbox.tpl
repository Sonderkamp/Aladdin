<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 2-3-2016
 * Time: 00:39
 */
?>
<div class="container">


    {if !isset($search)}
        {assign var="search" value=""}
    {else}
        {assign var="search" value=htmlspecialchars($search)}
    {/if}

    <h3>{$title}</h3>

    {if isset($search) && $search != ""}
        <h4>{$folder} - {$search}</h4>
    {else}
        <h4>{$folder}</h4>
    {/if}

    <div class="col-sm-3">
        <a href="/Inbox/p={$page[0]}/action=new" class="btn btn-default" style="width:100%">Nieuw Bericht</a><br>
        <br>
        {if isset($in)}
            <a href="/Inbox" class="btn btn-default active" style="width:100%">Postvak in</a>
            <br>
        {else}
            <a href="/Inbox" class="btn btn-default" style="width:100%">Postvak in</a>
            <br>
        {/if}
        {if isset($out)}
            <a href="/Inbox/folder=outbox" class="btn btn-default active" style="width:100%">Postvak uit</a>
            <br>
        {else}
            <a href="/Inbox/folder=outbox" class="btn btn-default" style="width:100%">Postvak uit</a>
            <br>
        {/if}
        {if isset($trash)}
            <a href="/Inbox/folder=trash" class="btn btn-default active" style="width:100%">Prullenbak</a>
            <br>
        {else}
            <a href="/Inbox/folder=trash" class="btn btn-default" style="width:100%">Prullenbak</a>
            <br>
        {/if}
        <br>
    </div>

    <div class="col-sm-9">
        <span class="info"><button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">
                <span class="glyphicon glyphicon-info-sign"></span>
            </button></span>
        <div class="hidden-sm hidden-md hidden-lg"><br><br></div>
        <form class="user-control form-inline" action="/Inbox/folder={$folderShortcut}" method="get">
            <input class="form-control white" value="{$search}" placeholder="Zoek Criteria" name="search" type="text">
            <button class="form-control btn-inbox" type="submit">Zoek</button>
        </form>

        <br><br>
        {if isset($error)}
            <div id="err">
                <div class="form-error">Error: {htmlspecialchars($error)}</div>
            </div>
        {else}
            <p id="err"></p>
        {/if}
        {foreach from=$messages item=message}
        {if $message->adminSender}
        <div class="panel panel-default adminMessage">
            {else}
            <div class="panel panel-default overflowhidden">
                {/if}
                <div class="panel-body">
                    <a href="/Inbox/folder={$folderShortcut}/p={$page[0]}/message={$message->id}"
                       class="title">{htmlspecialchars($message->title)}</a> <span
                            class="info">
                    {if isset($out)}
                        {$message->receiver}
                        {else}
                        {if $message->adminSender}
                            <span class="glyphicon glyphicon-eye-open"></span>
                            {$message->sender}
                        {else}
                            {$message->sender}
                        {/if}
                    {/if}<br>{$message->date}</span>
                    <br>
                    <span>{$message->content}</span><br><br>
                </div>
                <div class="panel-footer">
                    <a href="/Inbox/folder={$folderShortcut}/p={$page[0]}/message={$message->id}" class="btn btn-inbox">Openen</a>
                    {if isset($trash)}
                        <form class=noPadding action="/Inbox/folder={$folderShortcut}/p={$page[0]}" method="post">
                            <input type="hidden" name="delete" value="{$message->id}"/>
                            <button type="submit" class="btn btn-inbox">Permanent Verwijderen</button>
                        </form>
                        <form class=noPadding action="/Inbox/folder={$folderShortcut}/p={$page[0]}" method="post">
                            <input type="hidden" name="reset" value="{$message->id}"/>
                            <button type="submit" class="btn btn-inbox">Terugzetten</button>
                        </form>
                    {else}
                        <form class=noPadding action="/Inbox/folder={$folderShortcut}/p={$page[0]}" method="post">
                            <input type="hidden" name="trash" value="{$message->id}"/>
                            <button type="submit" class="btn btn-inbox">Verwijderen</button>
                        </form>
                    {/if}
                    {if !$message->adminSender}
                        <form class=noPadding action="/Inbox/folder={$folderShortcut}/p={$page[0]}" method="post">
                            <input type="hidden" name="reply" value="{$message->id}"/>
                            <button type="submit" class="btn btn-inbox">Beantwoorden</button>
                        </form>
                    {/if}
                    {if isset($message->links)}
                        {foreach from=$message->links item=link}
                            {if $link->action == "Talent"}
                                <a href="/Talents" class="btn btn-inbox">Mijn Talenten</a>
                            {else if $link->action == "Wens"}
                                <a href="/Wishes/wish_id={$link->content}" class="btn btn-inbox">Bekijk wens</a>
                            {else if $link->action == "PaginaLink"}
                                <a href="{$link->content}" class="btn btn-inbox">Naar Pagina</a>
                            {else if $link->action == "Bericht"}
                                <a href="/Inbox/message={$link->content}" class="btn btn-inbox">Naar Pagina</a>
                            {/if}
                        {/foreach}
                    {/if}
                </div>
            </div>
            {/foreach}
            {if (count($messages) == 0)}
                <div class="well">
                    <span>Geen berichten.</span>
                </div>
            {/if}
            {if $page[0] > 1 && $page[0] != $page[1]}
            <span><a href="/Inbox/folder={$folderShortcut}/p={$page[0]  - 1}?search={$search}" class="btn btn-default">Vorige</a><a
                        href="/Inbox/folder={$folderShortcut}/p={$page[0]   + 1}?search={$search}"
                        class="btn btn-default">Volgende</a></span><span class="info">Pagina {$page[0]}
                / {$page[1]}</span>
            {else if $page[0] > 1}
            <span><a href="/Inbox/folder={$folderShortcut}/p={$page[0]  - 1}?search={$search}" class="btn btn-default">Vorige</a><span
                        class="info">Pagina {$page[0]} / {$page[1]}</span>
                {else if $page[1] > 1}
                <span><a href="/Inbox/folder={$folderShortcut}/p={$page[0]  + 1}?search={$search}"
                         class="btn btn-default">Volgende</a></span><span class="info">Pagina 1 / {$page[1]}</span>
                {else}
                <span class="info">Pagina 1</span>
                {/if}
                <br><br>
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
                        Je hebt onder het bericht ook acties naar het snel beantwoorden en het verwijderen van dit
                        bericht.
                    </p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                </div>
            </div>

        </div>
    </div>