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
                                {if {$smarty.get.action} eq 'requested' || {$smarty.get.action} eq 'accept' ||
                                {$smarty.get.action} eq 'deny' || !isset($smarty.get.action)}
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
                                {if {$smarty.get.action} eq 'open' || {$smarty.get.action} eq 'delete' || {$smarty.get.action} eq 'redraw'}
                                <button type="submit" class="btn btn-primary side-button"
                                        formaction="/AdminWish/action=open">
                                    {else}
                                    <button type="submit" class="btn btn-default side-button"
                                            formaction="/AdminWish/action=open">
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
                                <button type="submit" class="btn btn-primary side-button"
                                        formaction="/AdminWish/action=matched">
                                    {else}
                                    <button type="submit" class="btn btn-default side-button"
                                            formaction="/AdminWish/action=matched">
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
                                <button type="submit" class="btn btn-primary side-button"
                                        formaction="/AdminWish/action=current">
                                    {else}
                                    <button type="submit" class="btn btn-default side-button"
                                            formaction="/AdminWish/action=current">
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
                                <button type="submit" class="btn btn-primary side-button"
                                        formaction="/AdminWish/action=done">
                                    {else}
                                    <button type="submit" class="btn btn-default side-button"
                                            formaction="/AdminWish/action=done">
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
                                <button type="submit" class="btn btn-primary side-button"
                                        formaction="/AdminWish/action=denied">
                                    {else}
                                    <button type="submit" class="btn btn-default side-button"
                                            formaction="/AdminWish/action=denied">
                                        {/if}
                                        <span class="glyphicon glyphicon-remove"></span> Geweigerde Wensen
                                    </button>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a>
                                {if {$smarty.get.action} eq 'deleted'}
                                <button type="submit" class="btn btn-primary side-button"
                                        formaction="/AdminWish/action=deleted">
                                    {else}
                                    <button type="submit" class="btn btn-default side-button"
                                            formaction="/AdminWish/action=deleted">
                                        {/if}
                                        <span class="glyphicon glyphicon-remove"></span> Verwijderde wensen
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
                    {if {$smarty.get.action} eq 'requested' || !isset($smarty.get.action) || {$smarty.get.action} eq
                    'changed' ||  {$smarty.get.action} eq 'accept' || {$smarty.get.action} eq 'deny'}
                    <th width="1%">Accepteren</th>
                    <th width="1%">Afwijzen</th>
                    <th width="1%">Profiel</th>
                    {/if}
                    {if {$smarty.get.action} eq 'open'  || {$smarty.get.action} eq 'redraw' || {$smarty.get.action} eq 'delete'}
                    <th width="1%">Terug naar aangevraagd</th>
                    {/if}
                    {if {$smarty.get.action} eq 'matched' || {$smarty.get.action} eq 'current' || {$smarty.get.action}
                    eq 'open'  || {$smarty.get.action} eq 'redraw' || {$smarty.get.action} eq 'delete'}
                    <th width="1%">Verwijder</th>
                    {/if}
                </tr>
                </thead>
                <tbody>


                {foreach from=$reqwishes item=wish item=i}

                <tr>
                    <form action="/AdminWish/action=accept" method="post">
                        <td>{$i.display}</td>
                        <td>{$i.content}</td>
                        <td>{$i.country}</td>
                        <td>{$i.city}</td>

                            <input type="hidden" value={$i.wishid} name="wishid">
                            <input type="hidden" value={$i.user} name="user">
                            <input type="hidden" value={$i.mdate|replace:' ':'%20'} name="mdate" step="1" >
{if !isset($i.wishid)}
                     hi
                        {/if}
                            {if {$smarty.get.action} eq 'requested' || !isset($smarty.get.action) ||
                            {$smarty.get.action} eq 'changed'  ||  {$smarty.get.action} eq 'accept' || {$smarty.get.action} eq 'deny'}
                        <input type="hidden" value={$i.title} name="wishtitle">
                        <input type="hidden" value={$i.display} name="wishdisplay">
                        <input type="hidden" value={$i.content} name="wishcontent">
                        <td>
                            <button type="submit" formaction="/AdminWish/action=accept">Accepteren</button>
                        </td>
                        <td>
                            <button type="submit" formaction="/AdminWish/action=deny"
                            ">Afwijzen</button>
                        </td>
                        <td>
                            <button type="submit"  formaction="/ProfileCheck/user={$i.user}">Profiel</button>
                        </td>
                        {/if}

                        {if {$smarty.get.action} eq 'open' || {$smarty.get.action} eq 'redraw' || {$smarty.get.action} eq 'delete'}

                        <td>
                            <button type="submit" formaction="/AdminWish/action=redraw"
                            ">Aangevraagd</button>
                        </td>
                        {/if}

                        {if {$smarty.get.action} eq 'matched' || {$smarty.get.action} eq 'current'||
                        {$smarty.get.action} eq 'open' || {$smarty.get.action} eq 'redraw' || {$smarty.get.action} eq 'delete'}

                        <td>
                            <button type="submit" formaction="/AdminWish/action=delete"
                            ">Verwijder</button>
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

