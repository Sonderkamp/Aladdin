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
            <div class="col-md-2">
                <ul class="nav nav-pills nav-stacked">
                    {if {$smarty.get.action} eq 'requested' || {$smarty.get.action} eq 'accept' ||
                    {$smarty.get.action} eq 'deny' || !isset($smarty.get.action)}
                    <li class="active"><a href="#tab1" data-toggle="tab">Mijn Aangevraagd</a></li>
                    <li><a href="#tab2" data-toggle="tab">Gepubliseerd</a></li>
                    <li><a href="#tab3" data-toggle="tab">Match gevonden</a></li>
                    <li><a href="#tab4" data-toggle="tab">Wordt vervuld</a></li>
                    <li><a href="#tab5" data-toggle="tab">Vervulde Wensen</a></li>
                    <li><a href="#tab6" data-toggle="tab">Geweigerde Wensen</a></li>
                    <li><a href="#tab7" data-toggle="tab">Verwijderde wensen</a></li>

                    {elseif {$smarty.get.action} eq 'open' || {$smarty.get.action} eq 'delete' || {$smarty.get.action} eq 'redraw'}
                    <li><a href="#tab1" data-toggle="tab">Mijn Aangevraagd</a></li>
                    <li class="active"><a href="#tab2" data-toggle="tab">Gepubliseerd</a></li>
                    <li><a href="#tab3" data-toggle="tab">Match gevonden</a></li>
                    <li><a href="#tab4" data-toggle="tab">Wordt vervuld</a></li>
                    <li><a href="#tab5" data-toggle="tab">Vervulde Wensen</a></li>
                    <li><a href="#tab6" data-toggle="tab">Geweigerde Wensen</a></li>
                    <li><a href="#tab7" data-toggle="tab">Verwijderde wensen</a></li>

                    {elseif {$smarty.get.action} eq 'matched'}
                    <li><a href="#tab1" data-toggle="tab">Mijn Aangevraagd</a></li>
                    <li><a href="#tab2" data-toggle="tab">Gepubliseerd</a></li>
                    <li class="active"><a href="#tab3" data-toggle="tab">Match gevonden</a></li>
                    <li><a href="#tab4" data-toggle="tab">Wordt vervuld</a></li>
                    <li><a href="#tab5" data-toggle="tab">Vervulde Wensen</a></li>
                    <li><a href="#tab6" data-toggle="tab">Geweigerde Wensen</a></li>
                    <li><a href="#tab7" data-toggle="tab">Verwijderde wensen</a></li>

                    {elseif {$smarty.get.action} eq 'current'}
                    <li><a href="#tab1" data-toggle="tab">Mijn Aangevraagd</a></li>
                    <li><a href="#tab2" data-toggle="tab">Gepubliseerd</a></li>
                    <li><a href="#tab3" data-toggle="tab">Match gevonden</a></li>
                    <li class="active"><a href="#tab4" data-toggle="tab">Wordt vervuld</a></li>
                    <li><a href="#tab5" data-toggle="tab">Vervulde Wensen</a></li>
                    <li><a href="#tab6" data-toggle="tab">Geweigerde Wensen</a></li>
                    <li><a href="#tab7" data-toggle="tab">Verwijderde wensen</a></li>

                    {elseif {$smarty.get.action} eq 'done'}
                    <li><a href="#tab1" data-toggle="tab">Mijn Aangevraagd</a></li>
                    <li><a href="#tab2" data-toggle="tab">Gepubliseerd</a></li>
                    <li><a href="#tab3" data-toggle="tab">Match gevonden</a></li>
                    <li><a href="#tab4" data-toggle="tab">Wordt vervuld</a></li>
                    <li class="active"><a href="#tab5" data-toggle="tab">Vervulde Wensen</a></li>
                    <li><a href="#tab6" data-toggle="tab">Geweigerde Wensen</a></li>
                    <li><a href="#tab7" data-toggle="tab">Verwijderde wensen</a></li>

                    {elseif {$smarty.get.action} eq 'denied'}
                    <li><a href="#tab1" data-toggle="tab">Mijn Aangevraagd</a></li>
                    <li><a href="#tab2" data-toggle="tab">Gepubliseerd</a></li>
                    <li><a href="#tab3" data-toggle="tab">Match gevonden</a></li>
                    <li><a href="#tab4" data-toggle="tab">Wordt vervuld</a></li>
                    <li><a href="#tab5" data-toggle="tab">Vervulde Wensen</a></li>
                    <li class="active"><a href="#tab6" data-toggle="tab">Geweigerde Wensen</a></li>
                    <li><a href="#tab7" data-toggle="tab">Verwijderde wensen</a></li>

                    {elseif {$smarty.get.action} eq 'deleted'}
                    <li><a href="#tab1" data-toggle="tab">Mijn Aangevraagd</a></li>
                    <li><a href="#tab2" data-toggle="tab">Gepubliseerd</a></li>
                    <li><a href="#tab3" data-toggle="tab">Match gevonden</a></li>
                    <li><a href="#tab4" data-toggle="tab">Wordt vervuld</a></li>
                    <li><a href="#tab5" data-toggle="tab">Vervulde Wensen</a></li>
                    <li><a href="#tab6" data-toggle="tab">Geweigerde Wensen</a></li>
                    <li class="active"><a href="#tab7" data-toggle="tab">Verwijderde wensen</a></li>







                    {else}
                    <li class="active"><a href="#tab1" data-toggle="tab">Mijn Aangevraagd</a></li>
                    <li><a href="#tab2" data-toggle="tab">Gepubliseerd</a></li>
                    <li><a href="#tab3" data-toggle="tab">Match gevonden</a></li>
                    <li><a href="#tab4" data-toggle="tab">Wordt vervuld</a></li>
                    <li><a href="#tab5" data-toggle="tab">Vervulde Wensen</a></li>
                    <li><a href="#tab6" data-toggle="tab">Geweigerde Wensen</a></li>
                    <li><a href="#tab7" data-toggle="tab">Verwijderde wensen</a></li>
                    {/if}
                </ul>
            </div>

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

