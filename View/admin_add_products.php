<html>
<div class="container" xmlns="http://www.w3.org/1999/html">

    {if isset($edit)}
    <form action="/admin2/action=edit_product" method="post">
        {else}
        <form action="/admin2/action=add_product" method="post">
            {/if}
            <div class="form-group row">
                <label class="col-sm-2 form-control-label">Productnaam:</label>
                <div class="col-sm-10">
                    {if isset($name)}
                    <input class="form-control" name="product_name" placeholder="Product naam" value={($name)}>
                    {else}
                    <input class="form-control" name="product_name" placeholder="Product naam">
                    {/if}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 form-control-label">Beschrijving kort:</label>
                <div class="col-sm-10">
                    {if isset($description_short)}
                    <input class="form-control" name="product_description_short" placeholder="Beschrijving kort"
                           value="{($description_short)}">
                    {else}
                    <input class="form-control" name="product_description_short" placeholder="Beschrijving kort">
                    {/if}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 form-control-label">Beschrijving lang:</label>
                <div class="col-sm-10">
                    {if isset($description_long)}
                    <input class="form-control" name="product_description_long" placeholder="Beschrijving lang"
                           value="{($description_long)}">
                    {else}
                    <input class="form-control" name="product_description_long" placeholder="Beschrijving lang">
                    {/if}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 form-control-label">Prijs:</label>
                <div class="col-sm-10">
                    {if isset($price)}
                    <input class="form-control" name="price" placeholder="Prijs van product"
                           value="{($price)}">
                    {else}
                    <input class="form-control" name="price" placeholder="Prijs van product">
                    {/if}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 form-control-label">Kies categorie</label>
                <div class="col-sm-10">
                    <select class="form-control" id="sel1" name="category">
                        {if isset($curCategorie)}
                        <option selected="selected">{$curCategorie}</option>
                        {/if}
                        {foreach from=$allCategories item=category}
                        <option>{$category -> getName()}</option>
                        {/foreach}
                    </select>
                </div>
            </div>

            {if isset($error)}
            <div class="form-error" id="err">{htmlspecialchars($error)}</div>
            {/if}

            <a>
                {if isset($productID)}
                <button name="productID" type="submit" class="btn btn-primary" value={$productID}>
                    Bevestig
                </button>
                {else}
                <button type="submit" class="btn btn-primary">
                    Bevestig
                </button>
                {/if}
            </a>

            <a href="/admin2/action=go_back">
                <button type=button class="btn btn-default side-button">
                    Terug
                </button>
            </a>
        </form>
</div>
</html>


