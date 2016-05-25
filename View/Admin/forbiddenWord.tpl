<div class="container">

    {* If succes message is not empty than show it *}
    {if !empty($successMessage)}
        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Succes!</strong> {$successMessage}
        </div>
    {/if}

    {* If error message is not empty than show it *}
    {if !empty($errorMessage)}
        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Error!</strong> {$errorMessage}
        </div>
    {/if}

    {* Add forbidden word *}
    <form class="col-xs-12 col-sm-12 col-md-6 col-lg-6" action="/forbiddenwords/action=addWord" method="get">
        <div class="form-group row">
            <label for="word" class="col-sm-2 form-control-label">Verboden woord</label>
            <div class="col-sm-10">
                <input type="text" name="newWord" class="form-control" id="word" placeholder="Verboden woord">
                <small class="text-muted">Dit is het verboden woord. Als dit woord voorkomt in een aanvraag bij wens of talent word deze aanvraag automatisch verwijderd.</small>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-add">Toevoegen</button>
            </div>
        </div>
    </form>

    {* Search forbidden word *}
    <form class="col-xs-12 col-sm-12 col-md-6 col-lg-6" action="/forbiddenwords/search" method="get">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <input class="form-control" name="search" placeholder="Zoek een verboden woord">
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <button type="submit" class="btn btn-primary">Zoek</button>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <a href="/forbiddenwords" class="btn btn-warning">Alle</a>
        </div>
    </form>

    {* if there is 1 word or more show table *}
    {* ELSE show no words found *}
    {if $wordsCount > 0}
        <h5 class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Verboden woorden</h5>
        <table class="table col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <thead>
                <tr>
                    <th>Word</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {* loop through all words and show them in the table *}
                {foreach from=$forbiddenWords item=word}
                    <tr>
                        <td class="col-xs-10 col-sm-10 col-md-10 col-lg-10">{htmlentities(trim($word),ENT_QUOTES)}</td>
                        <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                            <button type="button" class="btn btn-inbox btn-small" data-toggle="modal" data-target="#myModal{preg_replace('/\s+/', '', htmlentities(trim($word),ENT_QUOTES))}Edit">
                                <span class="glyphicon glyphicon-edit"></span>
                            </button>
                        </td>
                        <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                            <button type="button" class="btn btn-danger btn-small" data-toggle="modal" data-target="#myModal{preg_replace('/\s+/', '', htmlentities(trim($word),ENT_QUOTES))}Remove">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>

        {* if there are more than 10 words show pagination *}
        {if $wordsCount > 1}

            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                {* If pagination is not turned off show pagination *}
                {if $smarty.session.wordsPagination != "off"}
                    <div class="col-xs-offset-4">
                        <nav>
                            <ul class="pagination">

                                {* if page is smaller than or same as 1 disable the previous button *}
                                {* ELSE enable the previous button *}
                                {if $page <= 1}
                                    <li class="disabled">
                                        <a href="#" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                {else}
                                    <li>
                                        <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}wordsPage={$page - 1}" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                {/if}

                                {* If wordsCount is smaller than seven show normal pagination (1,2,3,4,5,6) *}
                                {* ELSE show pagination for larger (first,...,3,4,5,...,last) *}
                                {if $wordsCount < 7}

                                    {* Loop the number of wordsCount *}
                                    {for $number=1 to $wordsCount}

                                        {* if the number is the same as page disable the button *}
                                        {* ELSE enable the button *}
                                        {if $number == $page}
                                            <li class="active">
                                                <a href="#">{$number}</a>
                                            </li>
                                        {else}
                                            <li>
                                                <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}wordsPage={$number}">{$number}</a>
                                            </li>
                                        {/if}
                                    {/for}
                                {else}

                                    {* If page equals 1 disable the first button *}
                                    {* ELSE enable the first button *}
                                    {if 1 == $page}
                                        <li class="active">
                                            <a href="#">1</a>
                                        </li>
                                    {else}
                                        <li>
                                            <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}wordsPage=1">1</a>
                                        </li>
                                    {/if}

                                    {* If page is smaller than 4 show pagination like this (1,2,3,4,...,last) *}
                                    {if $page < 4}

                                        {* loop from 2 until 4 *}
                                        {for $number=2 to 4}

                                            {* If the number equals page than disable the button *}
                                            {* ELSE enable the button *}
                                            {if $number == $page}
                                                <li class="active">
                                                    <a href="#">{$number}</a>
                                                </li>
                                            {else}
                                                <li>
                                                    <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}wordsPage={$number}">{$number}</a>
                                                </li>
                                            {/if}
                                        {/for}

                                        <li class="disabled">
                                            <a href="#">...</a>
                                        </li>

                                    {* if page is greater than wordscount minus 3 (for example page is 8 and wordsCount(10) - 3 is 7) *}
                                    {elseif $page > ($wordsCount - 3)}

                                        <li class="disabled">
                                            <a href="#">...</a>
                                        </li>

                                        {* Loop through the last four numbers until the last minus one *}
                                        {for $number=($wordsCount - 3) to ($wordsCount - 1)}

                                            {* if number equals page disable the button *}
                                            {* ELSE enable the button *}
                                            {if $number == $page}
                                                <li class="active">
                                                    <a href="#">{$number}</a>
                                                </li>
                                            {else}
                                                <li>
                                                    <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}wordsPage={$number}">{$number}</a>
                                                </li>
                                            {/if}
                                        {/for}

                                    {* ELSE the pagination will be shown like this (first,...,3,4,5,...,last) *}
                                    {else}
                                        <li class="disabled">
                                            <a href="#">...</a>
                                        </li>

                                        {* Loop from page minus one until page plus one *}
                                        {for $number=($page - 1) to ($page + 1)}

                                            {* if number equals page than disable the button *}
                                            {* ELSE enable the button *}
                                            {if $number == $page}
                                                <li class="active">
                                                    <a href="#">{$number}</a>
                                                </li>
                                            {else}
                                                <li>
                                                    <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}wordsPage={$number}">{$number}</a>
                                                </li>
                                            {/if}
                                        {/for}
                                        <li class="disabled">
                                            <a href="#">...</a>
                                        </li>
                                    {/if}

                                    {* If wordsCount equals page disable the button *}
                                    {* ELSE enable the button *}
                                    {if $wordsCount == $page}
                                        <li class="active">
                                            <a href="#">{$number}</a>
                                        </li>
                                    {else}
                                        <li>
                                            <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}wordsPage={$page}">
                                                <span aria-hidden="true">{$wordsCount}</span>
                                            </a>
                                        </li>
                                    {/if}
                                {/if}

                                {* if page is greater than or the same as wordsCount disable the next button *}
                                {* ELSE enable the button *}
                                {if $page >= $wordsCount}
                                    <li class="disabled">
                                        <a href="#" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                {else}
                                    <li>
                                        <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}wordsPage={$page + 1}" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                {/if}
                            </ul>
                        </nav>
                    </div>
                {/if}
                <form class="pull-right"  action="/forbiddenwords" method="post">
                    <input type="hidden" value="{if $smarty.session.wordsPagination == "off"}on{else}off{/if}" name="pagination">
                    <input type="hidden" value="{if isset($smarty.get.wordsPage)}{$smarty.get.wordsPage}{else}1{/if}" name="page">
                    <button type="submit" class="btn btn-primary">Pagination {if $smarty.session.wordsPagination == "off"}aan{else}uit{/if}</button>
                </form>
            </div>
        {/if}
    {else}
        <h1 class="table col-xs-12 col-sm-12 col-md-12 col-lg-12">No words found!</h1>
    {/if}
</div>

{* Modal for remove forbidden word *}
{foreach from=$forbiddenWords item=word}
    <div id="myModal{preg_replace('/\s+/', '', htmlentities(trim($word),ENT_QUOTES))}Remove" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Wilt u het verboden woord "{htmlentities(trim($word),ENT_QUOTES)}" definitief verwijderen?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Sluiten</button>

                    <form action="/forbiddenwords/action=removeWord" method="get">
                        <input type="hidden" value="{htmlentities(trim($word),ENT_QUOTES)}" name="word">

                        <button type="submit" class="btn btn-danger btn-small">
                            <span class="glyphicon glyphicon-trash"></span> verwijderen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
{/foreach}

{* Modal for editing a forbidden word *}
{foreach from=$forbiddenWords item=word}
    <div id="myModal{preg_replace('/\s+/', '', htmlentities(trim($word),ENT_QUOTES))}Edit" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Het verboden woord "{htmlentities(trim($word),ENT_QUOTES)}" wijzigen:</h4>
                </div>

                <form action="/forbiddenwords/action=editWord" method="get">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="edit{preg_replace('/\s+/', '', htmlentities(trim($word),ENT_QUOTES))}" class="col-sm-2 form-control-label">Verboden woord</label>
                            <div class="col-sm-10">
                                <input type="text" name="editedWord" class="form-control" id="edit{preg_replace('/\s+/', '', htmlentities(trim($word),ENT_QUOTES))}" placeholder="Verboden woord" value="{htmlentities(trim($word),ENT_QUOTES)}">
                                <small class="text-muted">Dit is het verboden woord. Als dit woord voorkomt in een aanvraag bij wens of talent word deze aanvraag automatisch verwijderd.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Sluiten</button>

                        <input type="hidden" value="{htmlentities(trim($word),ENT_QUOTES)}" name="oldWord">

                        <button type="submit" class="btn btn-inbox btn-small">
                            <span class="glyphicon glyphicon-edit"></span> wijzigen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
{/foreach}