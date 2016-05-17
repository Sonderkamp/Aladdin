<div class="container">
    <form action="/survey/submit" method="post">
        {foreach from=$questions item=$value}
            <div class="thumbnail">
                <div class="caption">
                    <div class="row btn-text">
                        <h4>{$value->questionContent}</h4>
                        <div class="row answer-container">
                        {foreach from=$value->answers item=$answerValue}
                            <label class="radio-inline col-xs-6">
                                <input type="radio" name="Question-{$value->id}" value="{$answerValue->answerContent}">{$answerValue->answerContent}
                            </label>
                        {/foreach}
                        </div>
                    </div>
                </div>
            </div>
        {/foreach}
        <div class="row">
            <div class="col-xs-12">
                <div class="col-centered">
                    <button type="submit" class="btn btn-primary">
                        Bevestig
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>