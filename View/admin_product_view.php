<html>

<div class="container" xmlns="http://www.w3.org/1999/html">

    <div class="form-group row">
        {if isset($product_name)}
        <label class="col-sm-2 form-control-label">Product naam:</label>
        <div class="col-sm-10">
            <input class="form-control" value={($product_name)} readonly>
        </div>
        {/if}
    </div>

    <div class="form-group row">
        <label class="col-sm-2 form-control-label">Product beschrijving kort:</label>
        {if isset($product_description_short)}
        <div class="col-sm-10">
            <input class="form-control" value={($product_description_short)} readonly>
        </div>
        {/if}
    </div>

    <div class="form-group row">
        {if isset($product_description_long)}
        <label class="col-sm-2 form-control-label">Product beschrijving lang:</label>
        <div class="col-sm-10">
            <input class="form-control" value={($product_description_long)} readonly>
        </div>
        {/if}
    </div>

    <div class="form-group row">
        {if isset($product_price)}
        <label class="col-sm-2 form-control-label">Product prijs:</label>
        <div class="col-sm-10">
            <input class="form-control" value={($product_price)} readonly>
        </div>
        {/if}
    </div>

    <div class="form-group row">
        {if isset($product_category)}
        <label class="col-sm-2 form-control-label">Product categorie: </label>
        <div class="col-sm-10">
            <input class="form-control" value={($product_category)} readonly>
        </div>
        {/if}
    </div>


    <a href="/admin2/action=go_back">
        <button type=button class="btn btn-primary side-button">
            Terug
        </button>
    </a>
</div>

</html>