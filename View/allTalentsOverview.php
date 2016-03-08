<!---->
<!--Created by PhpStorm.-->
<!--User: Joost-->
<!--Date: 8-3-2016-->
<!--Time: 18:06-->
<div class="container">
    <h5>Alle talenten overzicht</h5>
    <div class="col-md-2">
        <ul class="nav nav-pills nav-stacked">
            <li><a href="/talents/action=added_talents">Toegevoegde talenten</a></li>
            <li class="active"><a href="/talents/action=all_talents">Alle talenten</a></li>
            <li><a href="/talents/action=add_talent">Talent aanvragen</a></li>
        </ul>
    </div>
    <div class="col-md-10">
        <div>
            <table class="table">
                <thead>
                <tr>
                    <th>Alle talenten</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                {foreach from=$talents item=talent}
                <tr>
                    <td class="col-xs-12 col-sm-12 col-md-12 col-lg-12">{$talent -> name}</td>
                    <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                        <form action="/talents" method="post">
                            <input type="hidden" name="add_id" value="{$talent->id}"/>
                            <button type="submit" name="submit" class="btn btn-add btn-sm">
                                <span class="glyphicon glyphicon-ok"></span>
                            </button>
                        </form>
                    </td>
                </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
        {if $talent_number gt 1}
        <div>
            <nav>
                <ul class="pagination">
                    {if $current_talent_number le 1}
                    <li class="disabled">
                        <a href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    {else}
                    <li>
                        <a href="/talents/action=all_talents/show_talents={$current_talent_number - 1}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    {/if}

                    {for $number=1 to $talent_number}
                    {if $number eq $current_talent_number}
                    <li class="active">
                        <a href="#">{$number}</a>
                    </li>
                    {else}
                    <li>
                        <a href="/talents/action=all_talents/show_talents={$number}">{$number}</a>
                    </li>
                    {/if}
                    {/for}

                    {if $current_talent_number ge $talent_number}
                    <li class="disabled">
                        <a href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    {else}
                    <li>
                        <a href="/talents/action=all_talents/show_talents={$current_talent_number + 1}" aria-label="Next">
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