
<!--Created by PhpStorm.-->
<!--User: Joost-->
<!--Date: 10-3-2016-->
<!--Time: 09:37-->
<div class="container">
    <div id="rootwizard">
        <h5>Talenten beheren</h5>
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#tab1" data-toggle="tab">Talenten beheren</a></li>
                <li><a href="#tab2" data-toggle="tab">Aanvragen talenten</a></li>
            </ul>
        </div>
        <div class="col-md-10">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab1">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Alle talenten</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$all_talents item=talent}
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
                    {if $all_talent_number > 1}
                    <div>
                        <nav>
                            <ul class="pagination">
                                {if $current_all_talents_number <= 1}
                                <li class="disabled">
                                    <a href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                {else}
                                <li>
                                    <a href="/talents/admin_a={$current_all_talents_number - 1}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                {/if}

                                {for $number=1 to $all_talent_number}
                                {if $number == $current_all_talents_number}
                                <li class="active">
                                    <a href="#">{$number}</a>
                                </li>
                                {else}
                                <li>
                                    <a href="/talents/admin_a={$number}">{$number}</a>
                                </li>
                                {/if}
                                {/for}

                                {if $current_all_talents_number >= $all_talent_number}
                                <li class="disabled">
                                    <a href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                {else}
                                <li>
                                    <a href="/talents/admin_a={$current_all_talents_number + 1}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                {/if}
                            </ul>
                        </nav>
                    </div>
                    {/if}
                </div>

                <div class="tab-pane" id="tab2">
                    talent aanvragen
                </div>
            </div>
        </div>
    </div>
</div>