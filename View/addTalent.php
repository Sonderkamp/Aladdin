
<!--Created by PhpStorm.-->
<!--User: Joost-->
<!--Date: 7-3-2016-->
<!--Time: 21:43-->


<div class="container">
    <h5>Talent toevoegen</h5>
    <form class="col-xs-12 col-sm-12 col-md-12 col-lg-12" action="/talents" method="post">
        <div class="form-group row">
            <label for="name" class="col-sm-2 form-control-label">Naam talent</label>
            <div class="col-sm-10">
                <input type="text" name="talent_name" class="form-control" id="name" placeholder="Naam">
                <small class="text-muted">Dit is de naam van het talent. Deze naam moet voldoen aan de algemene voorwaarden.</small>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-add">Aanvragen</button>
            </div>
        </div>
    </form>
</div>