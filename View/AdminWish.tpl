<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: simon-->
<!-- * Date: 8-3-2016-->
<!-- * Time: 17:51-->
<!-- */-->


<div class="container">

    <h5>Wensen Beheer</h5>
    <div class="col-md-2">
        <ul class="nav nav-pills nav-stacked">
            {if $current_page eq 'requested' || $current_page eq 'accept' ||
            $current_page eq 'deny' || !isset($current_page)}
            <li class="active"><a href="#tab1" data-toggle="tab">Aangevraagde wensen</a></li>
            <li><a href="#tab2" data-toggle="tab">Gepubliseerd</a></li>
            <li><a href="#tab3" data-toggle="tab">Match gevonden</a></li>
            <li><a href="#tab4" data-toggle="tab">Wordt vervuld</a></li>
            <li><a href="#tab5" data-toggle="tab">Vervulde Wensen</a></li>
            <li><a href="#tab6" data-toggle="tab">Geweigerde Wensen</a></li>
            <li><a href="#tab7" data-toggle="tab">Verwijderde wensen</a></li>

            {elseif $current_page eq 'open' || $current_page eq 'delete' || $current_page eq 'redraw'}
            <li><a href="#tab1" data-toggle="tab">Aangevraagde wensen</a></li>
            <li class="active"><a href="#tab2" data-toggle="tab">Gepubliseerd</a></li>
            <li><a href="#tab3" data-toggle="tab">Match gevonden</a></li>
            <li><a href="#tab4" data-toggle="tab">Wordt vervuld</a></li>
            <li><a href="#tab5" data-toggle="tab">Vervulde Wensen</a></li>
            <li><a href="#tab6" data-toggle="tab">Geweigerde Wensen</a></li>
            <li><a href="#tab7" data-toggle="tab">Verwijderde wensen</a></li>

            {elseif $current_page eq 'matched'}
            <li><a href="#tab1" data-toggle="tab">Mijn Aangevraagd</a></li>
            <li><a href="#tab2" data-toggle="tab">Gepubliseerd</a></li>
            <li class="active"><a href="#tab3" data-toggle="tab">Match gevonden</a></li>
            <li><a href="#tab4" data-toggle="tab">Wordt vervuld</a></li>
            <li><a href="#tab5" data-toggle="tab">Vervulde Wensen</a></li>
            <li><a href="#tab6" data-toggle="tab">Geweigerde Wensen</a></li>
            <li><a href="#tab7" data-toggle="tab">Verwijderde wensen</a></li>

            {elseif $current_page eq 'current'}
            <li><a href="#tab1" data-toggle="tab">Mijn Aangevraagd</a></li>
            <li><a href="#tab2" data-toggle="tab">Gepubliseerd</a></li>
            <li><a href="#tab3" data-toggle="tab">Match gevonden</a></li>
            <li class="active"><a href="#tab4" data-toggle="tab">Wordt vervuld</a></li>
            <li><a href="#tab5" data-toggle="tab">Vervulde Wensen</a></li>
            <li><a href="#tab6" data-toggle="tab">Geweigerde Wensen</a></li>
            <li><a href="#tab7" data-toggle="tab">Verwijderde wensen</a></li>

            {elseif $current_page eq 'done'}
            <li><a href="#tab1" data-toggle="tab">Mijn Aangevraagd</a></li>
            <li><a href="#tab2" data-toggle="tab">Gepubliseerd</a></li>
            <li><a href="#tab3" data-toggle="tab">Match gevonden</a></li>
            <li><a href="#tab4" data-toggle="tab">Wordt vervuld</a></li>
            <li class="active"><a href="#tab5" data-toggle="tab">Vervulde Wensen</a></li>
            <li><a href="#tab6" data-toggle="tab">Geweigerde Wensen</a></li>
            <li><a href="#tab7" data-toggle="tab">Verwijderde wensen</a></li>

            {elseif $current_page eq 'denied'}
            <li><a href="#tab1" data-toggle="tab">Mijn Aangevraagd</a></li>
            <li><a href="#tab2" data-toggle="tab">Gepubliseerd</a></li>
            <li><a href="#tab3" data-toggle="tab">Match gevonden</a></li>
            <li><a href="#tab4" data-toggle="tab">Wordt vervuld</a></li>
            <li><a href="#tab5" data-toggle="tab">Vervulde Wensen</a></li>
            <li class="active"><a href="#tab6" data-toggle="tab">Geweigerde Wensen</a></li>
            <li><a href="#tab7" data-toggle="tab">Verwijderde wensen</a></li>

            {elseif $current_page eq 'deleted'}
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


    <div class="col-md-10">
        <span class="info"><button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal"> <span class="glyphicon glyphicon-info-sign"></span>
            </button></span>
        <div class="tab-content">

            {if $current_page == "requested"}
            <div class="tab-pane fade in active" id="tab1">
            {elseif $current_page == "open"  || $current_page eq 'redraw' || $current_page eq 'delete'}
            <div class="tab-pane" id="tab1">
            {elseif $current_page == "current"}
            <div class="tab-pane" id="tab1">
            {elseif $current_page == "matched"}
            <div class="tab-pane" id="tab1">
            {elseif $current_page == "done"}
            <div class="tab-pane" id="tab1">
            {elseif $current_page == "denied"}
            <div class="tab-pane" id="tab1">
            {elseif $current_page == "deleted"}
            <div class="tab-pane" id="tab1">
            {else}
            <div class="tab-pane fade in active" id="tab1">
            {/if}
                <table class="table">
                    <thead>
                    <tr>
                        <th>Gebruiker</th>
                        <th>Wens</th>
                        <th>Land</th>
                        <th>Plaats</th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$reqwishes item=wish item=i}
                    <tr>
                        <form action="/AdminWish/action=accept" method="post">
                            <td>{$i.display}</td>
                            <td>{$i.title|escape:"html"}</td>
                            <td>{$i.country}</td>
                            <td>{$i.city}</td>

                            <input type="hidden" value={$i.wishid} name="wishid">
                            <input type="hidden" value={$i.user} name="user">
                            <input type="hidden" value={$i.mdate|replace:' ':'%20'} name="mdate" step="1" >
                            <input type="hidden" value={$i.title|escape:"html"} name="wishtitle">
                            <input type="hidden" value={$i.display|escape:"html"} name="wishdisplay">
                            <input type="hidden" value={$i.content|escape:"html"} name="wishcontent">
                            <input type="hidden" value={$current_page} name="page">
                            <td>
                                <button type="button" class="btn btn-sm"
                                        data-toggle="modal"
                                        data-title="{$i.title|escape:"html"}"
                                        data-content="{$i.content|escape:"html"}"
                                        data-owner="{$i.display}"
                                        data-status="{$i.status}"
                                        data-city="{$i.city}"
                                        data-country="{$i.country}"
                                        data-date="{$i.mdate}"
                                        data-target="#checkModal">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </td>

                            <td>
                                <button type="submit" class="btn btn-sm" formaction="/AdminWish/action=accept"><span class="glyphicon glyphicon glyphicon-ok"></span></button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm"
                                        data-toggle="modal"
                                        data-id="{$i.wishid}"
                                        data-title="{$i.title|escape:"html"}"
                                        data-content="{$i.content|escape:"html"}"
                                        data-owner="{$i.display}"
                                        data-user="{$i.user}"
                                        data-mdate="{$i.mdate|replace:' ':'%20'}"
                                        data-target="#denyModal">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm"
                                        data-toggle="modal"
                                        data-display="{$i.display}"
                                        data-email="{$i.user}"
                                        data-address="{$i.address}"
                                        data-postalcode="{$i.postalcode}"
                                        data-country="{$i.country}"
                                        data-city="{$i.city}"
                                        data-target="#profileModal">
                                    <span class="glyphicon glyphicon glyphicon-user"></span>
                                </button>
                            </td>
                    </tr>

                    </form>
                    {/foreach}

                    </tbody>
                </table>
            </div>
            {if $current_page == "requested"}
            <div class="tab-pane" id="tab2">
            {elseif $current_page == "open"  || $current_page eq 'redraw' || $current_page eq 'delete'}
            <div class="tab-pane  fade in active" id="tab2">
            {elseif $current_page == "current"}
            <div class="tab-pane" id="tab2">
            {elseif $current_page == "matched"}
            <div class="tab-pane" id="tab2">
            {elseif $current_page == "done"}
            <div class="tab-pane" id="tab2">
            {elseif $current_page == "denied"}
            <div class="tab-pane" id="tab2">
            {elseif $current_page == "deleted"}
            <div class="tab-pane" id="tab2">
            {else}
            <div class="tab-pane fade in active" id="tab2">
            {/if}
                <table class="table">
                    <thead>
                    <tr>
                        <th>Gebruiker</th>
                        <th>Wens</th>
                        <th>Land</th>
                        <th>Plaats</th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$openwishes item=wish item=i}
                    <tr>
                        <form action="" method="post">
                            <td>{$i.display}</td>
                            <td>{$i.title|escape:"html"}</td>
                            <td>{$i.country}</td>
                            <td>{$i.city}</td>

                            <input type="hidden" value={$i.wishid} name="wishid">
                            <input type="hidden" value={$i.user} name="user">
                            <input type="hidden" value={$i.mdate|replace:' ':'%20'} name="mdate" step="1" >
                            <input type="hidden" value={$i.title|escape:"html"}} name="wishtitle">
                            <input type="hidden" value={$i.display} name="wishdisplay">
                            <input type="hidden" value={$i.content|escape:"html"} name="wishcontent">
                            <input type="hidden" value={$current_page} name="page">

                            <td>
                                <button type="button" class="btn btn-sm"
                                        data-toggle="modal"
                                        data-title="{$i.title|escape:"html"}"
                                        data-content="{$i.content|escape:"html"}"
                                        data-owner="{$i.display}"
                                        data-status="{$i.status}"
                                        data-city="{$i.city}"
                                        data-country="{$i.country}"
                                        data-date="{$i.mdate}"
                                        data-target="#checkModal">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm" formaction="/AdminWish/action=redraw"><span class="glyphicon glyphicon glyphicon-refresh"></button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm" formaction="/AdminWish/action=delete"><span class="glyphicon glyphicon glyphicon glyphicon-trash"></button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm"
                                        data-toggle="modal"
                                        data-display="{$i.display}"
                                        data-email="{$i.user}"
                                        data-address="{$i.address}"
                                        data-postalcode="{$i.postalcode}"
                                        data-country="{$i.country}"
                                        data-city="{$i.city}"
                                        data-target="#profileModal">
                                    <span class="glyphicon glyphicon glyphicon-user"></span>
                                </button>
                            </td>
                    </tr>

                    </form>
                    {/foreach}

                    </tbody>
                </table>
            </div>

            {if $current_page == "requested"}
            <div class="tab-pane" id="tab3">
            {elseif $current_page == "open"  || $current_page eq 'redraw' || $current_page eq 'delete'}
            <div class="tab-pane" id="tab3">
            {elseif $current_page == "current"}
            <div class="tab-pane"  fade in active id="tab3">
            {elseif $current_page == "matched"}
            <div class="tab-pane" id="tab3">
            {elseif $current_page == "done"}
            <div class="tab-pane" id="tab3">
            {elseif $current_page == "denied"}
            <div class="tab-pane" id="tab3">
            {elseif $current_page == "deleted"}
            <div class="tab-pane" id="tab3">
            {else}
            <div class="tab-pane fade in active" id="tab3">
            {/if}
                <table class="table">
                    <thead>
                    <tr>
                        <th>Gebruiker</th>
                        <th>Wens</th>
                        <th>Land</th>
                        <th>Plaats</th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$matchedwishes item=wish item=i}
                    <tr>
                        <form action="" method="post">
                            <td>{$i.display}</td>
                            <td>{$i.title|escape:"html"}</td>
                            <td>{$i.country}</td>
                            <td>{$i.city}</td>

                            <input type="hidden" value={$i.wishid} name="wishid">
                            <input type="hidden" value={$i.user} name="user">
                            <input type="hidden" value={$i.mdate|replace:' ':'%20'} name="mdate" step="1" >
                            <input type="hidden" value={$i.title|escape:"html"} name="wishtitle">
                            <input type="hidden" value={$i.display} name="wishdisplay">
                            <input type="hidden" value={$i.content|escape:"html"} name="wishcontent">
                            <input type="hidden" value={$current_page} name="page">
                            <td>
                                <button type="button" class="btn btn-sm"
                                        data-toggle="modal"
                                        data-title="{$i.title|escape:"html"}"
                                        data-content="{$i.content|escape:"html"}"
                                        data-owner="{$i.display}"
                                        data-status="{$i.status}"
                                        data-city="{$i.city}"
                                        data-country="{$i.country}"
                                        data-date="{$i.mdate}"
                                        data-target="#checkModal">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm"
                                        data-toggle="modal"
                                        data-display="{$i.display}"
                                        data-email="{$i.user}"
                                        data-address="{$i.address}"
                                        data-postalcode="{$i.postalcode}"
                                        data-country="{$i.country}"
                                        data-city="{$i.city}"
                                        data-target="#profileModal">
                                    <span class="glyphicon glyphicon glyphicon-user"></span>
                                </button>
                            </td>
                    </tr>



                    </form>
                    {/foreach}

                    </tbody>
                </table>
            </div>
            {if $current_page == "requested"}
            <div class="tab-pane" id="tab4">
            {elseif $current_page == "open"  || $current_page eq 'redraw' || $current_page eq 'delete'}
            <div class="tab-pane" id="tab4">
            {elseif $current_page == "current"}
            <div class="tab-pane" id="tab4">
            {elseif $current_page == "matched"}
            <div class="tab-pane" fade in active  id="tab4">
            {elseif $current_page == "done"}
            <div class="tab-pane" id="tab4">
            {elseif $current_page == "denied"}
            <div class="tab-pane" id="tab4">
            {elseif $current_page == "deleted"}
            <div class="tab-pane" id="tab4">
            {else}
            <div class="tab-pane fade in active" id="tab4">
            {/if}
                <table class="table">
                    <thead>
                    <tr>
                        <th>Gebruiker</th>
                        <th>Wens</th>
                        <th>Land</th>
                        <th>Plaats</th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$currentwishes item=wish item=i}
                    <tr>
                        <form action="" method="post">
                            <td>{$i.display}</td>
                            <td>{$i.title|escape:"html"}</td>
                            <td>{$i.country}</td>
                            <td>{$i.city}</td>

                            <input type="hidden" value={$i.wishid} name="wishid">
                            <input type="hidden" value={$i.user} name="user">
                            <input type="hidden" value={$i.mdate|replace:' ':'%20'} name="mdate" step="1" >
                            <input type="hidden" value={$i.title|escape:"html"} name="wishtitle">
                            <input type="hidden" value={$i.display} name="wishdisplay">
                            <input type="hidden" value={$i.content|escape:"html"} name="wishcontent">
                            <input type="hidden" value={$current_page} name="page">
                            <td>
                                <button type="button" class="btn btn-sm"
                                        data-toggle="modal"
                                        data-title="{$i.title|escape:"html"}"
                                        data-content="{$i.content|escape:"html"}"
                                        data-owner="{$i.display}"
                                        data-status="{$i.status}"
                                        data-city="{$i.city}"
                                        data-country="{$i.country}"
                                        data-date="{$i.mdate}"
                                        data-target="#checkModal">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm"
                                        data-toggle="modal"
                                        data-display="{$i.display}"
                                        data-email="{$i.user}"
                                        data-address="{$i.address}"
                                        data-postalcode="{$i.postalcode}"
                                        data-country="{$i.country}"
                                        data-city="{$i.city}"
                                        data-target="#profileModal">
                                    <span class="glyphicon glyphicon glyphicon-user"></span>
                                </button>
                            </td>
                    </tr>

                    </form>
                    {/foreach}

                    </tbody>
                </table>
            </div>
            {if $current_page == "requested"}
            <div class="tab-pane" id="tab5">
            {elseif $current_page == "open"  || $current_page eq 'redraw' || $current_page eq 'delete'}
            <div class="tab-pane" id="tab5">
            {elseif $current_page == "current"}
            <div class="tab-pane" id="tab5">
            {elseif $current_page == "matched"}
            <div class="tab-pane"   id="tab5">
            {elseif $current_page == "done"}
            <div class="tab-pane"  fade in active  id="tab5">
            {elseif $current_page == "denied"}
            <div class="tab-pane" id="tab5">
            {elseif $current_page == "deleted"}
            <div class="tab-pane" id="tab5">
            {else}
            <div class="tab-pane fade in active" id="tab5">
            {/if}
                <table class="table">
                    <thead>
                    <tr>
                        <th>Gebruiker</th>
                        <th>Wens</th>
                        <th>Land</th>
                        <th>Plaats</th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$donewishes item=wish item=i}
                    <tr>
                        <form action="" method="post">
                            <td>{$i.display}</td>
                            <td>{$i.title|escape:"html"}</td>
                            <td>{$i.country}</td>
                            <td>{$i.city}</td>

                            <input type="hidden" value={$i.wishid} name="wishid">
                            <input type="hidden" value={$i.user} name="user">
                            <input type="hidden" value={$i.mdate|replace:' ':'%20'} name="mdate" step="1" >
                            <input type="hidden" value={$i.title|escape:"html"} name="wishtitle">
                            <input type="hidden" value={$i.display} name="wishdisplay">
                            <input type="hidden" value={$i.content|escape:"html"} name="wishcontent">
                            <input type="hidden" value={$current_page} name="page">
                            <td>
                                <button type="button" class="btn btn-sm"
                                        data-toggle="modal"
                                        data-title="{$i.title|escape:"html"}"
                                        data-content="{$i.content|escape:"html"}"
                                        data-owner="{$i.display}"
                                        data-status="{$i.status}"
                                        data-city="{$i.city}"
                                        data-country="{$i.country}"
                                        data-date="{$i.mdate}"
                                        data-target="#checkModal">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm"
                                        data-toggle="modal"
                                        data-display="{$i.display}"
                                        data-email="{$i.user}"
                                        data-address="{$i.address}"
                                        data-postalcode="{$i.postalcode}"
                                        data-country="{$i.country}"
                                        data-city="{$i.city}"
                                        data-target="#profileModal">
                                    <span class="glyphicon glyphicon glyphicon-user"></span>
                                </button>
                            </td>
                    </tr>

                    </form>
                    {/foreach}

                    </tbody>
                </table>
            </div>
            {if $current_page == "requested"}
            <div class="tab-pane" id="tab6">
            {elseif $current_page == "open"  || $current_page eq 'redraw' || $current_page eq 'delete'}
            <div class="tab-pane" id="tab6">
            {elseif $current_page == "current"}
            <div class="tab-pane" id="tab6">
            {elseif $current_page == "matched"}
            <div class="tab-pane"  id="tab6">
            {elseif $current_page == "done"}
            <div class="tab-pane" id="tab6">
            {elseif $current_page == "denied"}
            <div class="tab-pane"  fade in active   id="tab6">
            {elseif $current_page == "deleted"}
            <div class="tab-pane" id="tab6">
            {else}
            <div class="tab-pane fade in active" id="tab6">
            {/if}
                <table class="table">
                    <thead>
                    <tr>
                        <th>Gebruiker</th>
                        <th>Wens</th>
                        <th>Land</th>
                        <th>Plaats</th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$deniedwishes item=wish item=i}
                    <tr>
                        <form action="" method="post">
                            <td>{$i.display}</td>
                            <td>{$i.title|escape:"html"}</td>
                            <td>{$i.country}</td>
                            <td>{$i.city}</td>

                            <input type="hidden" value={$i.wishid} name="wishid">
                            <input type="hidden" value={$i.user} name="user">
                            <input type="hidden" value={$i.mdate|replace:' ':'%20'} name="mdate" step="1" >
                            <input type="hidden" value={$i.title|escape:"html"} name="wishtitle">
                            <input type="hidden" value={$i.display} name="wishdisplay">
                            <input type="hidden" value={$i.content|escape:"html"} name="wishcontent">
                            <input type="hidden" value={$current_page} name="page">
                            <td>
                                <button type="button" class="btn btn-sm"
                                        data-toggle="modal"
                                        data-title="{$i.title|escape:"html"}"
                                        data-content="{$i.content|escape:"html"}"
                                        data-owner="{$i.display}"
                                        data-status="{$i.status}"
                                        data-city="{$i.city}"
                                        data-country="{$i.country}"
                                        data-date="{$i.mdate}"
                                        data-target="#checkModal">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm" formaction="/AdminWish/action=redraw"><span class="glyphicon glyphicon glyphicon-refresh"></button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm"
                                        data-toggle="modal"
                                        data-display="{$i.display}"
                                        data-email="{$i.user}"
                                        data-address="{$i.address}"
                                        data-postalcode="{$i.postalcode}"
                                        data-country="{$i.country}"
                                        data-city="{$i.city}"
                                        data-target="#profileModal">
                                    <span class="glyphicon glyphicon glyphicon-user"></span>
                                </button>
                            </td>
                    </tr>

                    </form>
                    {/foreach}

                    </tbody>
                </table>
            </div>
            {if $current_page == "requested"}
            <div class="tab-pane" id="tab7">
            {elseif $current_page == "open"  || $current_page eq 'redraw' || $current_page eq 'delete'}
            <div class="tab-pane" id="tab7">
            {elseif $current_page == "current"}
            <div class="tab-pane" id="tab7">
            {elseif $current_page == "matched"}
            <div class="tab-pane" id="tab7">
            {elseif $current_page == "done"}
            <div class="tab-pane" id="tab7">
            {elseif $current_page == "denied"}
            <div class="tab-pane" id="tab7">
            {elseif $current_page == "deleted"}
            <div class="tab-pane"  fade in active  id="tab7">
            {else}
            <div class="tab-pane fade in active" id="tab7">
            {/if}

                <table class="table">
                    <thead>
                    <tr>
                        <th>Gebruiker</th>
                        <th>Wens</th>
                        <th>Land</th>
                        <th>Plaats</th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                        <th width="1%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$deletedwishes item=wish item=i}
                    <tr>
                        <form action="" method="post">
                            <td>{$i.display}</td>
                            <td>{$i.title|escape:"html"}</td>
                            <td>{$i.country}</td>
                            <td>{$i.city}</td>

                            <input type="hidden" value={$i.wishid} name="wishid">
                            <input type="hidden" value={$i.user} name="user">
                            <input type="hidden" value={$i.mdate|replace:' ':'%20'} name="mdate" step="1" >
                            <input type="hidden" value={$i.title|escape:"html"} name="wishtitle">
                            <input type="hidden" value={$i.display} name="wishdisplay">
                            <input type="hidden" value={$i.content|escape:"html"} name="wishcontent">
                            <input type="hidden" value={$current_page} name="page">
                            <td>
                                <button type="button" class="btn btn-sm"
                                        data-toggle="modal"
                                        data-title="{$i.title|escape:"html"}"
                                        data-content="{$i.content|escape:"html"}"
                                        data-owner="{$i.display}"
                                        data-status="{$i.status}"
                                        data-city="{$i.city}"
                                        data-country="{$i.country}"
                                        data-date="{$i.mdate}"
                                        data-target="#checkModal">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm" formaction="/AdminWish/action=redraw"><span class="glyphicon glyphicon glyphicon-refresh"></button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm"
                                        data-toggle="modal"
                                        data-display="{$i.display}"
                                        data-email="{$i.user}"
                                        data-address="{$i.address}"
                                        data-postalcode="{$i.postalcode}"
                                        data-country="{$i.country}"
                                        data-city="{$i.city}"
                                        data-target="#profileModal">
                                    <span class="glyphicon glyphicon glyphicon-user"></span>
                                </button>
                            </td>
                    </tr>

                    </form>
                    {/foreach}

                    </tbody>
                </table>
            </div>
            </div>


                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Uitleg</h4>
                            </div>
                            <div class="modal-body">
                                <p>Hier vind u uitleg over de icoontjes die in het wensbeheer system voor komen:</p>
                                <p><button  class="btn btn-sm"><span class="glyphicon glyphicon-eye-open"></button></span> Opent een pagina waar je de bijbehorende wens in kan zien</p>
                                <p><button  class="btn btn-sm"><span class="glyphicon glyphicon glyphicon-ok"></button></span> Accepteert de wens</p>
                                <p><button  class="btn btn-sm"><span class="glyphicon glyphicon glyphicon-remove"></button></span> Weigert de wens</p>
                                <p><button  class="btn btn-sm"><span class="glyphicon glyphicon glyphicon-user"></button></span> Gaat naar profiel pagina van een gebruiker</p>
                                <p><button  class="btn btn-sm"><span class="glyphicon glyphicon glyphicon-refresh"></button></span> Gekozen wens gaat terug naar aangevraagde wensen</p>
                                <p><button  class="btn btn-sm"><span class="glyphicon glyphicon glyphicon glyphicon-trash"></button></span> Verwijderd wens</p>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                            </div>
                        </div>

                    </div>
                </div>
                {*<div id="profileModal"  class="modal fade" role="dialog">*}
                    {*<div class="modal-dialog">*}

                        {*<!-- Modal content-->*}
                        {*<div class="modal-content">*}
                            {*<div class="modal-header">*}
                                {*<button type="button" class="close" data-dismiss="modal">&times;</button>*}
                                {*<h4 class="modal-title">Gebruiker</h4>*}
                            {*</div>*}
                            {*<div class="modal-body">*}
                                {*<input type="text" name="user" value=""/>*}
                                {*<p>Hier vind U informatie over de gebruiker {$i.user}</p>*}
                                {*<p>INFO GEBRUIKER</p>*}
                                {*<p>INFO GEBRUIKER</p>*}
                                {*<p>INFO GEBRUIKER</p>*}
                                {*<p>INFO GEBRUIKER</p>*}
                                {*<p>INFO GEBRUIKER</p>*}
                                {*<p>INFO GEBRUIKER</p>*}


                            {*</div>*}
                            {*<div class="modal-footer">*}
                                {*<button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>*}
                            {*</div>*}
                        {*</div>*}

                    {*</div>*}
                {*</div>*}


                <div class="modal fade" id="profileModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Gebruiker informatie van <input type="" readonly style="background: transparent; border: transparent" name="display" value=""/></h4>
                            </div>
                            <div class="modal-body">
                                <form method="get">
                                <p>Email: <input type=""  readonly style="background: transparent; border: transparent" name="user" value=""/></p>
                                <p>Adres: <input type=""  disabled readonly style="background: transparent; border: transparent" name="address" value=""/></p>
                                <p>Postcode: <input type="" disabled readonly style="background: transparent; border: transparent" name="postalcode" value=""/></p>
                                <p>Land: <input type="" disabled readonly style="background: transparent; border: transparent" name="country" value=""/></p>
                                <p>Plaats: <input type="" disabled readonly style="background: transparent; border: transparent" name="city" value=""/></p>



                                <button type="submit" class="btn btn-sm" formaction="/ProfileCheck/"><span class="glyphicon glyphicon glyphicon-user"></span>Meer info</button>
                            </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    $('#profileModal').on('show.bs.modal', function(e) {
                        var display = $(e.relatedTarget).data('display');
                        $(e.currentTarget).find('input[name="display"]').val(display);
                        var email = $(e.relatedTarget).data('email');
                        $(e.currentTarget).find('input[name="user"]').val(email);
                        var address = $(e.relatedTarget).data('address');
                        $(e.currentTarget).find('input[name="address"]').val(address);
                        var postalcode = $(e.relatedTarget).data('postalcode');
                        $(e.currentTarget).find('input[name="postalcode"]').val(postalcode);
                        var country = $(e.relatedTarget).data('country');
                        $(e.currentTarget).find('input[name="country"]').val(country);
                        var city = $(e.relatedTarget).data('city');
                        $(e.currentTarget).find('input[name="city"]').val(city);
                    });
                </script>


                <div class="modal fade" id="checkModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Wens informatie</h4>
                            </div>
                            <div class="modal-body">

                                    Wens titel: <input type=""  readonly style="background: transparent; border: transparent" name="title" value=""/></p>
                                    <p>Wens eigenaar: <input type=""  readonly style="background: transparent; border: transparent" name="owner" value=""/></p>
                                    <p>Wens status: <input type=""  readonly style="background: transparent; border: transparent" name="status" value=""/></p>
                                    <p>Wens plaats: <input type=""  readonly style="background: transparent; border: transparent" name="city" value=""/></p>
                                    <p>Wens land: <input type=""  readonly style="background: transparent; border: transparent" name="country" value=""/></p>
                                    <p>Aanmaak datum: <input type="datetime"  readonly style="background: transparent; border: transparent" name="date" value=""/></p>
                                <p>Wens inhoud: <textarea    readonly style="background: transparent; border: transparent; width:100%;height: 150px;  resize: none; font-size: 13px;"  name="content" value=""></textarea></p>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>

                    $('#checkModal').on('show.bs.modal', function(e) {
                        var title = $(e.relatedTarget).data('title');
                        $(e.currentTarget).find('input[name="title"]').val(title);
                        var content = $(e.relatedTarget).data('content');
                        $(e.currentTarget).find('textarea[name="content"]').val(content);
                        var owner = $(e.relatedTarget).data('owner');
                        $(e.currentTarget).find('input[name="owner"]').val(owner);
                        var status = $(e.relatedTarget).data('status');
                        $(e.currentTarget).find('input[name="status"]').val(status);
                        var city = $(e.relatedTarget).data('city');
                        $(e.currentTarget).find('input[name="city"]').val(city);
                        var country = $(e.relatedTarget).data('country');
                        $(e.currentTarget).find('input[name="country"]').val(country);
                        var date = $(e.relatedTarget).data('date');
                        $(e.currentTarget).find('input[name="date"]').val(date);

                    });
                    </script>

                <div  class="modal fade" id="denyModal">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Wens weigeren</h4>
                            </div>
                            <div class="modal-body">

                                <p>Wens titel: <input type=""  readonly style="background: transparent; border: transparent" name="title" value=""/></p>
                                <p>Wens eigenaar: <input type=""  readonly style="background: transparent; border: transparent" name="owner" value=""/></p>
                                <p>Wens inhoud: <textarea    readonly style="background: transparent; border: transparent; width:100%;  resize: none; font-size: 13px;"  name="content" value=""></textarea></p>
                                <div id="myRadioGroup">
                                    <p>
                                        Handmatig<input type="radio" name="reason" checked="checked" value="1" />
                                    <div id="option1" class="desc">
                                        <form method="post">
                                        <input type="hidden" name="wishid" value=""/>
                                        <input type="hidden" name="mdate" value=""/>
                                        <input type="hidden" name="user" value=""/>
                                        Bericht titel: <input type="text" name="messagetitle" style="width:100%;resize: none;" value="Je wens is afgewezen"/></p>
                                    Bericht inhoud: <textarea name="message" style="height:200px; width:100%;resize: none;" value=""></textarea></p>
                                    <button type="submit" class="btn btn-sm" formaction="/AdminWish/action=deny" style="float: right"><span class="glyphicon glyphicon glyphicon-remove"></span>Weiger wens</button>
                                </form>
                                </div>
                                    </p>
                                    <p>
                                    18+ inhoud<input type="radio" name="reason" value="2"/>
                                <form method="post">
                                    <div id="option2" class="desc" style="display: none;">
                                        <input type="hidden" name="wishid" value=""/>
                                        <input type="hidden" name="mdate" value=""/>
                                        <input type="hidden" name="user" value=""/>
                                    Bericht titel: <input type="text" name="messagetitle" style="width:100%;resize: none;" value="Wens afgewezen reden: 18+ inhoud"/></p>
                                    Bericht inhoud: <textarea name="message" style="height:200px; width:100%;resize: none;" value="Wens afgewezen reden: 18+ inhoud"></textarea></p>
                                <button type="submit" class="btn btn-sm" formaction="/AdminWish/action=deny" style="float: right"><span class="glyphicon glyphicon glyphicon-remove"></span>Weiger wens</button>
                                </form>
                            </div>
                                    </p>
                                    <p>
                                    Agresieve inhoud<input type="radio" name="reason" value="3"/>
                            <form method="post">
                                    <div id="option3" class="desc" style="display: none;">
                                <input type="hidden" name="wishid" value=""/>
                                <input type="hidden" name="mdate" value=""/>
                                <input type="hidden" name="user" value=""/>
                                    Bericht titel: <input type="text" name="messagetitle" style="width:100%;resize: none;" value="Wens afgewezen reden: Agresieve inhoud"/></p>
                                    Bericht inhoud: <textarea name="message" style="height:200px; width:100%;resize: none;" value="Wens afgewezen reden: Agresieve inhoud"></textarea></p>
                            <button type="submit" class="btn btn-sm" formaction="/AdminWish/action=deny" style="float: right"><span class="glyphicon glyphicon glyphicon-remove"></span>Weiger wens</button>
                            </form>
                        </div>
                                    </p>
                                    <p>
                                    Geldzaken inhoud<input type="radio" name="reason" value="4"/>
                        <form method="post">
                                    <div id="option4" class="desc" style="display: none;">
                            <input type="hidden" name="wishid" value=""/>
                            <input type="hidden" name="mdate" value=""/>
                            <input type="hidden" name="user" value=""/>
                                    Bericht titel: <input type="text" name="messagetitle" style="width:100%;resize: none;" value="Wens afgewezen reden: Geldzaken inhoud"/></p>
                                    Bericht inhoud: <textarea name="message" style="height:200px; width:100%;resize: none;" value="Wens afgewezen reden: Geldzaken inhoud"></textarea></p>
                        <button type="submit" class="btn btn-sm" formaction="/AdminWish/action=deny" style="float: right"><span class="glyphicon glyphicon glyphicon-remove"></span>Weiger wens</button>
                        </form>
                    </div>

                </div>
                    <div class="modal-footer">
                        <p><br></p>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>

                </div>

                                    </p>
                    <script>
                        $(document).ready(function() {
                            $("input[name$='reason']").click(function() {
                                var test = $(this).val();

                                $("div.desc").hide();
                                $("#option" + test).show();
                            });
                        });


                    </script>
                                </div>


                            </div>

                        </div>



            </div>

                <script>

                    $('#denyModal').on('show.bs.modal', function(e) {
                        var id = $(e.relatedTarget).data('id');
                        $(e.currentTarget).find('input[name="wishid"]').val(id);
                        var title = $(e.relatedTarget).data('title');
                        $(e.currentTarget).find('input[name="title"]').val(title);
                        var content = $(e.relatedTarget).data('content');
                        $(e.currentTarget).find('textarea[name="content"]').val(content);
                        var owner = $(e.relatedTarget).data('owner');
                        $(e.currentTarget).find('input[name="owner"]').val(owner);
                        var mdate = $(e.relatedTarget).data('mdate');
                        $(e.currentTarget).find('input[name="mdate"]').val(mdate);
                        var user = $(e.relatedTarget).data('user');
                        $(e.currentTarget).find('input[name="user"]').val(user);

                    });
                </script>


