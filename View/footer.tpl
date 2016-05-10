
<div class="footer navbar-fixed-bottom hidden-xs hidden-sm">
        {*<p class="quote">Ik ben te oud om alleen maar te spelen, Te jong om zonder wensen te zijn. <span class="source">- Faust I (1801)</span></p>*}
    {if empty($smarty.session.quote->content)}
        <p class="quote">Ik ben te oud om alleen maar te spelen, Te jong om zonder wensen te zijn.<span class="source">~Faust I (1801)</span></p>
    {/if}
        <p class="quote">{htmlspecialchars($smarty.session.quote->content)}<span class="source">-{htmlspecialchars($smarty.session.quote->source)}</span></p>
</div>

    </body>
</html>