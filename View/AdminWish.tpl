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
                            <td>{$i.title}</td>
                            <td>{$i.country}</td>
                            <td>{$i.city}</td>

                            <input type="hidden" value={$i.wishid} name="wishid">
                            <input type="hidden" value={$i.user} name="user">
                            <input type="hidden" value={$i.mdate|replace:' ':'%20'} name="mdate" step="1" >
                            <input type="hidden" value={$i.title} name="wishtitle">
                            <input type="hidden" value={$i.display} name="wishdisplay">
                            <input type="hidden" value={$i.content} name="wishcontent">
                            <input type="hidden" value={$current_page} name="page">
                            <td>
                                <button formaction="/Wishes/wish_id={$i.wishid}" name="page" class="btn btn-sm" value="{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" type="sumbit">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm" formaction="/AdminWish/action=accept"><span class="glyphicon glyphicon glyphicon-ok"></span></button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm" formaction="/AdminWish/action=deny"><span class="glyphicon glyphicon glyphicon-remove"></span></button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm" formaction="/ProfileCheck/user={$i.user}"><span class="glyphicon glyphicon glyphicon-user"></span></button>
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
                            <td>{$i.title}</td>
                            <td>{$i.country}</td>
                            <td>{$i.city}</td>

                            <input type="hidden" value={$i.wishid} name="wishid">
                            <input type="hidden" value={$i.user} name="user">
                            <input type="hidden" value={$i.mdate|replace:' ':'%20'} name="mdate" step="1" >
                            <input type="hidden" value={$i.title} name="wishtitle">
                            <input type="hidden" value={$i.display} name="wishdisplay">
                            <input type="hidden" value={$i.content} name="wishcontent">
                            <input type="hidden" value={$current_page} name="page">

                            <td>
                                <button formaction="/Wishes/wish_id={$i.wishid}" name="page" class="btn btn-sm" value="{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" type="sumbit">
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
                                <button type="submit" class="btn btn-sm" formaction="/ProfileCheck/user={$i.user}"><span class="glyphicon glyphicon glyphicon-user"></span></button>
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
                            <td>{$i.title}</td>
                            <td>{$i.country}</td>
                            <td>{$i.city}</td>

                            <input type="hidden" value={$i.wishid} name="wishid">
                            <input type="hidden" value={$i.user} name="user">
                            <input type="hidden" value={$i.mdate|replace:' ':'%20'} name="mdate" step="1" >
                            <input type="hidden" value={$i.title} name="wishtitle">
                            <input type="hidden" value={$i.display} name="wishdisplay">
                            <input type="hidden" value={$i.content} name="wishcontent">
                            <input type="hidden" value={$current_page} name="page">
                            <td>
                                <button formaction="/Wishes/wish_id={$i.wishid}" name="editwishbtn" class="btn btn-sm" value="{$i.wishid}" type="sumbit">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm" formaction="/ProfileCheck/user={$i.user}"><span class="glyphicon glyphicon glyphicon-user"></span></button>
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
                            <td>{$i.title}</td>
                            <td>{$i.country}</td>
                            <td>{$i.city}</td>

                            <input type="hidden" value={$i.wishid} name="wishid">
                            <input type="hidden" value={$i.user} name="user">
                            <input type="hidden" value={$i.mdate|replace:' ':'%20'} name="mdate" step="1" >
                            <input type="hidden" value={$i.title} name="wishtitle">
                            <input type="hidden" value={$i.display} name="wishdisplay">
                            <input type="hidden" value={$i.content} name="wishcontent">
                            <input type="hidden" value={$current_page} name="page">
                            <td>
                                <button formaction="/Wishes/wish_id={$i.wishid}" name="page" class="btn btn-sm" value="{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" type="sumbit">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm" formaction="/ProfileCheck/user={$i.user}"><span class="glyphicon glyphicon glyphicon-user"></span></button>
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
            <div class="tab-pane" fade in active  id="tab5">
            {elseif $current_page == "done"}
            <div class="tab-pane" id="tab5">
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
                            <td>{$i.title}</td>
                            <td>{$i.country}</td>
                            <td>{$i.city}</td>

                            <input type="hidden" value={$i.wishid} name="wishid">
                            <input type="hidden" value={$i.user} name="user">
                            <input type="hidden" value={$i.mdate|replace:' ':'%20'} name="mdate" step="1" >
                            <input type="hidden" value={$i.title} name="wishtitle">
                            <input type="hidden" value={$i.display} name="wishdisplay">
                            <input type="hidden" value={$i.content} name="wishcontent">
                            <input type="hidden" value={$current_page} name="page">
                            <td>
                                <button formaction="/Wishes/wish_id={$i.wishid}" name="page" class="btn btn-sm" value="{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" type="sumbit">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm" formaction="/ProfileCheck/user={$i.user}"><span class="glyphicon glyphicon glyphicon-user"></span></button>
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
            <div class="tab-pane" fade in active  id="tab6">
            {elseif $current_page == "done"}
            <div class="tab-pane" id="tab6">
            {elseif $current_page == "denied"}
            <div class="tab-pane" id="tab6">
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
                            <td>{$i.title}</td>
                            <td>{$i.country}</td>
                            <td>{$i.city}</td>

                            <input type="hidden" value={$i.wishid} name="wishid">
                            <input type="hidden" value={$i.user} name="user">
                            <input type="hidden" value={$i.mdate|replace:' ':'%20'} name="mdate" step="1" >
                            <input type="hidden" value={$i.title} name="wishtitle">
                            <input type="hidden" value={$i.display} name="wishdisplay">
                            <input type="hidden" value={$i.content} name="wishcontent">
                            <input type="hidden" value={$current_page} name="page">
                            <td>
                                <button formaction="/Wishes/wish_id={$i.wishid}" name="page" class="btn btn-sm" value="{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" type="sumbit">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm" formaction="/AdminWish/action=redraw"><span class="glyphicon glyphicon glyphicon-refresh"></button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm" formaction="/ProfileCheck/user={$i.user}"><span class="glyphicon glyphicon glyphicon-user"></span></button>
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
            <div class="tab-pane" fade in active  id="tab7">
            {elseif $current_page == "done"}
            <div class="tab-pane" id="tab7">
            {elseif $current_page == "denied"}
            <div class="tab-pane" id="tab7">
            {elseif $current_page == "deleted"}
            <div class="tab-pane" id="tab7">
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
                            <td>{$i.title}</td>
                            <td>{$i.country}</td>
                            <td>{$i.city}</td>

                            <input type="hidden" value={$i.wishid} name="wishid">
                            <input type="hidden" value={$i.user} name="user">
                            <input type="hidden" value={$i.mdate|replace:' ':'%20'} name="mdate" step="1" >
                            <input type="hidden" value={$i.title} name="wishtitle">
                            <input type="hidden" value={$i.display} name="wishdisplay">
                            <input type="hidden" value={$i.content} name="wishcontent">
                            <input type="hidden" value={$current_page} name="page">
                            <td>
                                <button formaction="/Wishes/wish_id={$i.wishid}" name="page" class="btn btn-sm" value="{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" type="sumbit">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm" formaction="/AdminWish/action=redraw"><span class="glyphicon glyphicon glyphicon-refresh"></button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm" formaction="/ProfileCheck/user={$i.user}"><span class="glyphicon glyphicon glyphicon-user"></span></button>
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
                <div id="denyModal" class="modal fade" role="dialog">
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