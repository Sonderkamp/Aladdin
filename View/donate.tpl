<div class="container">

    <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">


        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title text-center">Doneren</div>
            </div>

            <div class="panel-body">
                {if isset($error)}
                    <div class="alert alert-danger" id="err">Error: {htmlspecialchars($error)}</div>
                {else}
                    <p id="err"></p>
                {/if}
                {if isset($success)}
                    <div id="suc">
                        <div class="form-error form-success">{htmlspecialchars($success)}</div>
                    </div>
                {else}
                    <div id="suc">
                    </div>
                {/if}
                <br>
                In principe streven we ernaar dat de wensen zonder geld gerealiseerd kunnen worden door elkaar.
                In sommige gevallen kan het noodzakelijk zijn om geld te hebben voor het vervullen van bijzondere
                wensen.
                Daarom zoeken wij donateurs. <br><br>
                <form action="/Donate/action=add" name="form" id="form" class="form-horizontal"
                      enctype="multipart/form-data"
                      method="POST">


                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="name" class="form-control" type="text" name="name"
                               placeholder="Naam"
                               value="{if isset($user)}{$user->name} {$user->surname}{/if}" {if isset($user)} readonly{/if}>
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-euro"></i></span>
                        <input id="quantity" class="form-control" type="number" name="quantity" min="3" step="0.01"
                               max="999999.99"
                               placeholder="Bedrag" onblur="validate()">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                        <input id="description" class="form-control" type="text" name="description"
                               placeholder="Beschrijving">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">Anoniem</span>
                        <input id="user" type="checkbox" name="anonymous">
                    </div>

                    <div class="form-group">
                        <!-- Button -->
                        <div class="col-sm-12 controls">
                            <button type="submit" href="#" class="btn btn-primary pull-right"><i
                                        class="glyphicon glyphicon-ok"></i> Doneer
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>

    Number.prototype.formatMoney = function (c, d, t) {
        var n = this,
                c = isNaN(c = Math.abs(c)) ? 2 : c,
                d = d == undefined ? "." : d,
                t = t == undefined ? "," : t,
                s = n < 0 ? "-" : "",
                i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
                j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };

    function validate() {


        var n = +($('input[name=quantity]').val());

        $('input[name=quantity]').val(n.formatMoney(2, '.', ''));


    }


</script>