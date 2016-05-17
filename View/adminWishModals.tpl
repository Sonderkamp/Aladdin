<div class="dropdown">
    <a href="#"
       class="dropdown-toggle"
       data-toggle="dropdown"
       role="button"
       aria-haspopup="true"
       aria-expanded="false">
        <span class="glyphicon glyphicon-chevron-down"></span>
    </a>
    <ul class="dropdown-menu small-dropdown-menu">

        <li>
            <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#wishModal{$wish->id}">
                <span class="glyphicon glyphicon-eye-open"></span>
            </button>
        </li>

        <li>
            <form class='noPadding' method="post">
                <button class="btn btn-sm"
                        formaction="/AdminWish/action=accept"
                        value="{$wish->id}"
                        type="submit"
                        name="wish_id">
                    <span class="glyphicon glyphicon-ok"></span>
                </button>
            </form>
        </li>

        <li>
            <form class='noPadding' method="post">
                <button class="btn btn-sm"
                        formaction="/AdminWish/action=refuse"
                        value="{$wish->id}"
                        type="submit"
                        name="wish_id">
                    <span class="glyphicon glyphicon-remove"></span>
                </button>
            </form>
        </li>

        <li>
            <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#profileModal{$wish->id}">
                <span class="glyphicon glyphicon-user"></span>
            </button>
        </li>

        <li>
            <form class='noPadding' method="post">
                <button class="btn btn-sm"
                        formaction="/AdminWish/action=delete"
                        value="{$wish->id}"
                        type="submit"
                        name="wish_id">
                    <span class="glyphicon glyphicon glyphicon-trash"></span>
                </button>
            </form>
        </li>

        <li>
            <form class='noPadding' method="post">
                <button class="btn btn-sm"
                        formaction="/AdminWish/action=revert"
                        value="{$wish->id}"
                        type="submit"
                        name="wish_id">
                    <span class="glyphicon glyphicon glyphicon-refresh"></span>
                </button>
            </form>
        </li>
    </ul>
</div>

<div id="wishModal{$wish->id}" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Wens overzicht</h4>
            </div>
            <div class="modal-body">

                <div class="form-group">

                    <div class="row">
                        <label class="col-sm-4">Id: </label>
                        <div class="col-sm-8">{htmlspecialchars($wish->id)}</div>
                    </div>

                    <div class="row">
                        <label class="col-sm-4">Title: </label>
                        <div class="col-sm-8">{htmlspecialcharsWithNL($wish->title)}</div>
                    </div>

                    <div class="row">
                        <label class="col-sm-4">Datum: </label>
                        <div class="col-sm-8">{$wish->contentDate}</div>
                    </div>

                    <div class="row">
                        <label class="col-sm-4">Wenser: </label>
                        <div class="col-sm-8">{htmlspecialcharsWithNL($wish->user->displayName)}</div>
                    </div>

                    <div class="row">
                        <label class="col-sm-4">Status: </label>
                        <div class="col-sm-8">{$wish->status}</div>
                    </div>

                    <div class="row">
                        <label class="col-sm-4">Plaats: </label>
                        <div class="col-sm-8">{htmlspecialcharsWithNL($wish->user->city)}</div>
                    </div>

                </div>
            </div>
            <strong>Content: </strong>
            <p>{htmlspecialcharsWithNL($wish->content)}</p>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Sluiten
                </button>
            </div>
        </div>
    </div>
</div>

<div id="profileModal{$wish->id}" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <span class="glyphicon glyphicon-user"></span><h4 class="modal-title">Wenser overzicht</h4>
            </div>

            <div class="modal-body">

                <div class="row">
                    <label class="col-sm-4">Naam: </label>
                    <div class="col-sm-8">{htmlspecialchars($wish->user->name)}{" "}{htmlspecialchars($wish->user->surname)}</div>
                </div>

                <div class="row">
                    <label class="col-sm-4">Email: </label>
                    <div class="col-sm-8">{htmlspecialchars($wish->user->email)}</div>
                </div>

                <div class="row">
                    <label class="col-sm-4">Gender: </label>
                    <div class="col-sm-8">{htmlspecialchars($wish->user->gender)}</div>
                </div>

                <div class="row">
                    <label class="col-sm-4">Adres: </label>
                    <div class="col-sm-8">{htmlspecialcharsWithNL($wish->user->address)}</div>
                </div>

                <div class="row">
                    <label class="col-sm-4">Plaats: </label>
                    <div class="col-sm-8">{$wish->user->city}</div>
                </div>

                <div class="row">
                    <label class="col-sm-4">Postcode: </label>
                    <div class="col-sm-8">{$wish->user->postalcode}</div>
                </div>

                <div class="row">
                    <label class="col-sm-4">Land: </label>
                    <div class="col-sm-8">{htmlspecialcharsWithNL($wish->user->country)}</div>
                </div>

                <div class="row">
                    <label class="col-sm-4">Geboortedatum: </label>
                    <div class="col-sm-8">{htmlspecialcharsWithNL($wish->user->dob)}</div>
                </div>

                <div class="row">
                    <label class="col-sm-4">Handicap: </label>
                    <div class="col-sm-8">{htmlspecialchars($wish->user->handicap)}</div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Sluiten
                </button>
            </div>
        </div>
    </div>
</div>
