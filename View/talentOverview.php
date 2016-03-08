
<!--Created by PhpStorm.-->
<!--User: Joost-->
<!--Date: 27-2-2016-->
<!--Time: 21:48-->

<div class="container">
    <h5>Toegevoegde talenten overzicht</h5>

    <div class="col-md-2">
        <ul class="nav nav-pills nav-stacked">
            <li class="active"><a href="/talents/action=added_talents">Toegevoegde talenten</a></li>
            <li><a href="/talents/action=all_talents">Alle talenten</a></li>
            <li><a href="/talents/action=add_talent">Talent aanvragen</a></li>
        </ul>
    </div>

    <div class="col-md-10">
        {if $number_of_talents le 3}
        <div class="alert alert-warning">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Waarschuwing!</strong> U moet minimaal 3 talenten hebben.
        </div>
        {/if}

        <table class="table">
            <thead>
                <tr>
                    <th>Toegevoegde talenten</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$user_talents item=talent}
                <tr>
                    <td class="col-xs-12 col-sm-12 col-md-12 col-lg-12">{$talent -> name}</td>
                    <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                        {if $number_of_talents le 3}
                        <button type="button" class="btn btn-inbox disabled btn-sm">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                        {else}
                        <button type="button" class="btn btn-inbox btn-sm" data-toggle="modal" data-target="#myModal{preg_replace('/\s+/', '', $talent->name)}">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                        {/if}
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
        {if $user_talents_number gt 1}
        <div class="center">
            <nav>
                <ul class="pagination">
                    {if $current_user_talent_number le 1}
                    <li class="disabled">
                        <a href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    {else}
                    <li>
                        <a href="/talents/action=added_talents/show_added_talents={$current_user_talent_number - 1}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    {/if}

                    {for $number=1 to $user_talents_number}
                    {if $number eq $current_user_talent_number}
                    <li class="active">
                        <a href="#">{$number}</a>
                    </li>
                    {else}
                    <li>
                        <a href="/talents/action=added_talents/show_added_talents={$number}">{$number}</a>
                    </li>
                    {/if}
                    {/for}

                    {if $current_user_talent_number ge $user_talents_number}
                    <li class="disabled">
                        <a href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    {else}
                    <li>
                        <a href="/talents/action=added_talents/show_added_talents={$current_user_talent_number + 1}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    {/if}
                </ul>
            </nav>
        </div>
        {/if}
    </div>
</div>

<!-- Modal Remove-->
{foreach from=$user_talents item=talent}
<div id="myModal{preg_replace('/\s+/', '', $talent->name)}" class="modal fade" role="dialog">
  <div class="modal-dialog">

      <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Talent verwijderen</h4>
            </div>
            <div class="modal-body">
                <p>
                    Weet u zeker dat u het talent "{$talent->name}" wilt verwijderen?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Sluiten</button>
                <form action="/talents" method="post">
                    <input type="hidden" name="remove_id" value="{$talent->id}"/>
                    <button type="submit" name="submit" class="btn btn-inbox info">
                        <span class="glyphicon glyphicon-remove"></span> Verwijderen
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
{/foreach}