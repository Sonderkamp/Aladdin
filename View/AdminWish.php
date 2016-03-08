<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: simon-->
<!-- * Date: 8-3-2016-->
<!-- * Time: 17:51-->
<!-- */-->


<div class="container">
    <div class="row">
        <div class=" col-xs-12 col-lg-2">
            <h5>Wensen Beheer</h5>


<form action="/AdminWish/" method="get">
            <table>
                <tr>
                    <td>
                        <a>
                            {if {$smarty.get.action} eq 'requested' || !isset($smarty.get.action)}
                            <button type="submit" class="btn btn-primary side-button" formaction="/AdminWish/">
                                {else}
                                <button type="submit" class="btn btn-default side-button" formaction="/AdminWish/">
                                {/if}
                                <span class="glyphicon glyphicon-align-justify"></span> Aangevraagd
                            </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a>
                            {if {$smarty.get.action} eq 'open'}
                            <button type="submit" class="btn btn-primary side-button" formaction="/AdminWish/action=open">
                                {else}
                                <button type="submit" class="btn btn-default side-button" formaction="/AdminWish/action=open">
                                    {/if}
                                <span class="glyphicon glyphicon-align-justify"></span> Gepubliseerd
                            </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a>
                            {if {$smarty.get.action} eq 'matched'}
                            <button type="submit" class="btn btn-primary side-button" formaction="/AdminWish/action=matched">
                                {else}
                                <button type="submit" class="btn btn-default side-button" formaction="/AdminWish/action=matched">
                                    {/if}
                                <span class="glyphicon glyphicon-ok"></span> Match gevonden
                            </button>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>
                        <a>
                            {if {$smarty.get.action} eq 'current'}
                            <button type="submit" class="btn btn-primary side-button" formaction="/AdminWish/action=current">
                                {else}
                                <button type="submit" class="btn btn-default side-button" formaction="/AdminWish/action=current">
                                    {/if}
                                <span class="glyphicon glyphicon-ok"></span> Wordt vervuld
                            </button>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>
                        <a>
                            {if {$smarty.get.action} eq 'done'}
                            <button type="submit" class="btn btn-primary side-button" formaction="/AdminWish/action=done">
                                {else}
                                <button type="submit" class="btn btn-default side-button" formaction="/AdminWish/action=done">
                                    {/if}
                                <span class="glyphicon glyphicon-ok"></span> Vervulde Wensen
                            </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a>
                            {if {$smarty.get.action} eq 'denied'}
                            <button type="submit" class="btn btn-primary side-button" formaction="/AdminWish/action=denied">
                                {else}
                                <button type="submit" class="btn btn-default side-button" formaction="/AdminWish/action=denied">
                                    {/if}
                                <span class="glyphicon glyphicon-remove"></span> Geweigerde Wensen
                            </button>
                        </a>
                    </td>
                </tr>

            </table>
</form>
        </div>
        <div class="col-lg-10">


            <table class="table">
                <thead>
                <tr>
                    <th>Gebruiker</th>
                    <th>Wens</th>
                    <th>Land</th>
                    <th>Plaats</th>
                    {if {$smarty.get.action} eq 'requested' || !isset($smarty.get.action) || {$smarty.get.action} eq 'changed'}
                    <th width="1%">Accepteren</th>
                    <th width="1%">Afwijzen</th>
                    <th width="1%">Profiel</th>
                    {/if}
                </tr>
                </thead>
                <tbody>



                {foreach from=$reqwishes item=wish item=i}

                <tr>
                    <form action="/AdminWish/action=accept" method="get">
                    <td>{$i.user}</td>
                    <td>{$i.content}</td>
                    <td>{$i.country}</td>
                    <td>{$i.city}</td>
                    <td>
                        <input type="hidden" value={$i.wishid}  name="wishid">
                        {if {$smarty.get.action} eq 'requested' || !isset($smarty.get.action) || {$smarty.get.action} eq 'changed'}
                        <button type="submit" formaction="AdminWish/action=accept">Accepteren</button>
                    </td>
                    <td>
                        <button type="submit" formaction="AdminWish/action=deny?user="{$i.user}">Afwijzen</button>
                    </td>
                    <td>
                        <button type="submit" formaction="AdminWish/action=profile">Profiel</button>
                    </td>
                        {/if}
                </tr>

</form>
                {/foreach}

                </tbody>
            </table>
        </div>
    </div>
</div>

