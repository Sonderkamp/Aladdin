<div class="container">

    {if !empty($successMessage)}
        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Succes!</strong> {$successMessage}
        </div>
    {/if}

    {if !empty($errorMessage)}
        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Error!</strong> {$errorMessage}
        </div>
    {/if}

    <form class="col-xs-12 col-sm-12 col-md-6 col-lg-6" action="/forbiddenwords" method="post">
        <div class="form-group row">
            <label for="word" class="col-sm-2 form-control-label">Verboden woord</label>
            <div class="col-sm-10">
                <input type="text" name="add_forbidden_word" class="form-control" id="word" placeholder="Verboden woord">
                <small class="text-muted">Dit is het verboden woord. Als dit woord voorkomt in een aanvraag bij wens of talent word deze aanvraag automatisch verwijderd.</small>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-add">Toevoegen</button>
            </div>
        </div>
    </form>

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

        {if $wordsCount > 1}

            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                {if $smarty.session.wordsPagination != "off"}
                    <div class="col-xs-offset-4">
                        <nav>
                            {if $wordsCount < 7}
                                <ul class="pagination">
                                    {if $page <= 1}
                                        <li class="disabled">
                                            <a href="#" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    {else}
                                        <li>
                                            <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}words_page={$page - 1}" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    {/if}

                                    {for $number=1 to $wordsCount}
                                        {if $number == $page}
                                            <li class="active">
                                                <a href="#">{$number}</a>
                                            </li>
                                        {else}
                                            <li>
                                                <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}words_page={$number}">{$number}</a>
                                            </li>
                                        {/if}
                                    {/for}

                                    {if $page >= $wordsCount}
                                        <li class="disabled">
                                            <a href="#" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    {else}
                                        <li>
                                            <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}words_page={$page + 1}" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    {/if}
                                </ul>
                            {else}
                                <ul class="pagination">
                                    {if $page <= 1}
                                        <li class="disabled">
                                            <a href="#" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    {else}
                                        <li>
                                            <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}words_page={$page - 1}" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    {/if}
                                    {if 1 == $page}
                                        <li class="active">
                                            <a href="#">1</a>
                                        </li>
                                    {else}
                                        <li>
                                            <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}words_page=1">1</a>
                                        </li>
                                    {/if}
                                    {if $page < 4}
                                        {for $number=2 to 4}
                                            {if $number == $page}
                                                <li class="active">
                                                    <a href="#">{$number}</a>
                                                </li>
                                            {else}
                                                <li>
                                                    <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}words_page={$number}">{$number}</a>
                                                </li>
                                            {/if}
                                        {/for}
                                        <li class="disabled">
                                            <a href="#">...</a>
                                        </li>
                                    {elseif $page > ($wordsCount - 3)}
                                        <li class="disabled">
                                            <a href="#">...</a>
                                        </li>
                                        {for $number=($wordsCount - 3) to ($wordsCount - 1)}
                                            {if $number == $page}
                                                <li class="active">
                                                    <a href="#">{$number}</a>
                                                </li>
                                            {else}
                                                <li>
                                                    <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}words_page={$number}">{$number}</a>
                                                </li>
                                            {/if}
                                        {/for}
                                    {else}
                                        <li class="disabled">
                                            <a href="#">...</a>
                                        </li>
                                        {for $number=($page - 1) to ($page + 1)}
                                            {if $number == $page}
                                                <li class="active">
                                                    <a href="#">{$number}</a>
                                                </li>
                                            {else}
                                                <li>
                                                    <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}words_page={$number}">{$number}</a>
                                                </li>
                                            {/if}
                                        {/for}
                                        <li class="disabled">
                                            <a href="#">...</a>
                                        </li>
                                    {/if}
                                    {if $wordsCount == $page}
                                        <li class="active">
                                            <a href="#">{$number}</a>
                                        </li>
                                    {else}
                                        <li>
                                            <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}words_page={$number_of_words}">
                                                <span aria-hidden="true">{$wordsCount}</span>
                                            </a>
                                        </li>
                                    {/if}
                                    {if $page >= $wordsCount}
                                        <li class="disabled">
                                            <a href="#" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    {else}
                                        <li>
                                            <a href="/ForbiddenWords/{if !empty($smarty.get.search)}search={$smarty.get.search}/{/if}words_page={$page + 1}" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    {/if}
                                </ul>
                            {/if}
                        </nav>
                    </div>
                {/if}
                <form class="pull-right"  action="/forbiddenwords" method="post">
                    <input type="hidden" value="{if $smarty.session.wordsPagination == "off"}on{else}off{/if}" name="pagination">
                    <button type="submit" class="btn btn-primary">Pagination {if $smarty.session.wordsPagination == "off"}aan{else}uit{/if}</button>
                </form>
            </div>
        {/if}
    {else}
        <h1 class="table col-xs-12 col-sm-12 col-md-12 col-lg-12">No words found!</h1>
    {/if}
</div>

<!-- Modal remove word-->
{foreach from=$forbiddenWords item=word}
    <div id="myModal{preg_replace('/\s+/', '', htmlentities(trim($word),ENT_QUOTES))}Remove" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Wilt u het verboden woord "{htmlentities(trim($word),ENT_QUOTES)}" definitief verwijderen?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Sluiten</button>

                    <form action="/forbiddenwords" method="post">
                        <input type="hidden" value="{htmlentities(trim($word),ENT_QUOTES)}" name="remove_forbidden_word">

                        <button type="submit" class="btn btn-danger btn-small">
                            <span class="glyphicon glyphicon-trash"></span> verwijderen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
{/foreach}

<!-- Modal edit word-->
{foreach from=$forbiddenWords item=word}
    <div id="myModal{preg_replace('/\s+/', '', htmlentities(trim($word),ENT_QUOTES))}Edit" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Het verboden woord "{htmlentities(trim($word),ENT_QUOTES)}" wijzigen:</h4>
                </div>

                <form action="/forbiddenwords" method="post">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="edit{preg_replace('/\s+/', '', htmlentities(trim($word),ENT_QUOTES))}" class="col-sm-2 form-control-label">Verboden woord</label>
                            <div class="col-sm-10">
                                <input type="text" name="edit_forbidden_word_new" class="form-control" id="edit{preg_replace('/\s+/', '', htmlentities(trim($word),ENT_QUOTES))}" placeholder="Verboden woord" value="{htmlentities(trim($word),ENT_QUOTES)}">
                                <small class="text-muted">Dit is het verboden woord. Als dit woord voorkomt in een aanvraag bij wens of talent word deze aanvraag automatisch verwijderd.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Sluiten</button>

                        <input type="hidden" value="{htmlentities(trim($word),ENT_QUOTES)}" name="edit_forbidden_word_old">

                        <button type="submit" class="btn btn-inbox btn-small">
                            <span class="glyphicon glyphicon-edit"></span> wijzigen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
{/foreach}