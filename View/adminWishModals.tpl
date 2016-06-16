<div id="refuseModal{$wish->id}" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Wens weigeren</h5>
            </div>

            <div class="modal-body">
                <form action="/AdminWish/action=refuseWish" method="post">
                    <p>Weet je zeker dat je deze wens wilt weigeren?</p>
                    <br>
                    <div class="row">
                        <div class="col-xs-3">
                            Reden:
                        </div>
                        <div class="col-xs-9">
                            <input type="text" required name="Reason" title="reason">
                        </div>
                    </div>
                    <br>
                    <div class="row">

                        <div class="col-md-6 col-md-offset-3 row">
                            <div class="col-xs-6">
                                <input type="hidden" name="Id" value="{$wish->id}">
                                <button type="submit" class="btn btn-confirm btn-default">
                                    Ja
                                </button>
                            </div>
                            <div class="col-xs-6">
                                <button type="button" class="btn btn-confirm btn-default" data-dismiss="modal">
                                    Nee
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="acceptModal{$wish->id}" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Wens accepteren</h5>
            </div>

            <div class="modal-body">
                <form action="/AdminWish/action=acceptWish" method="post">
                    <p>Weet je zeker dat je deze wens wilt accepteren?</p>
                    <br>
                    <div class="row">
                        <div class="col-xs-3">
                            Reden:
                        </div>
                        <div class="col-xs-9">
                            <input type="text" required name="Reason" title="reason">
                        </div>
                    </div>
                    <br>
                    <div class="row">

                        <div class="col-md-6 col-md-offset-3 row">
                            <div class="col-xs-6">
                                    <input type="hidden" name="Id" value="{$wish->id}">
                                    <button type="submit" class="btn btn-confirm btn-default">
                                        Ja
                                    </button>
                            </div>
                            <div class="col-xs-6">
                                <button type="button" class="btn btn-confirm btn-default" data-dismiss="modal">
                                    Nee
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
