<video autoplay id="bgvid" loop muted poster="/Resources/Images/Coverr-flowers.jpg">
    <source src="/Resources/Images/Coverr-flowers.webm" type="video/webm">
    <source src="/Resources/Images/Coverr-flowers.mp4" type="video/mp4">
</video>

<div class="jumbotron" id="main-hero">

    <div class="container">

        <div class="col-md-12">

            <h1>Aladdin</h1>
            <h4><em>Facebook zonder face, maar met een hart.</em></h4>
            <br><br><br>
            <blockquote>
                <p>
                    Vreemden worden vrienden, wensen worden werkelijkheid, kennis en wijsheid worden gedeeld,
                    eenzaamheid
                    verminderd & de aarde wordt liefdevol.
                    <footer>Stichting Aladdin</footer>
            </blockquote>

            <div class="text-center">
                {if empty($user)}
                    <a class="btn btn-register start btn-lg" href="/Account/action=register">Registreer</a>
                {else}
                    <a class="btn btn-register start btn-lg" href="/Dashboard">Naar Dashboard</a>
                {/if}
            </div>
        </div>

    </div>
</div>
<div class="white">
    <div class="container bg">
        <div class="col-lg-6 col-md-4">

            <h3>Wat is Aladdin?</h3>
            <p>

                Stichting Aladdin wil dat iedereen op de wereld een beetje gelukkiger wordt.
                Daarom mag je via deze website drie hartenwensen indienen, die je altijd al hebt willen doen of
                meemaken.
                Daarnaast weet Aladdin dat iedereen talenten heeft. Ja, ook jij! Met jouw talenten kun je hartenwensen
                van
                anderen vervullen.
                Een leuke bijkomstigheid is dat je van geven zelf ook gelukkiger wordt. Je zet je eigen geluk in
                beweging.
                Dat is de kracht van Aladdin en zo werkt iedereen mee aan een wereldsprookje.Wil je meedoen, klik dan op
                een
                van de symbolen.
                (kinderen, volwassenen, ouderen, beperkten, bedrijven) Dan kun je jezelf inschrijven en een vragenlijst
                invullen.
                Je wensen en talenten worden verzameld in een database en je kunt meteen al zien of er een match is.
                Soms heb je wat meer geduld nodig voordat een wens vervuld is, maar wel kun je alvast wensen van anderen
                vervullen.
                Heb je een wens van een ander vervuld, dan mag je een extra wens indienen.
                Weet je niet zo goed wat je hartenwensen of talenten zijn, zoek dan bij de trefwoorden bij het hart of
                de
                ster.


            </p>
        </div>
        <div class="col-lg-6 col-md-8 text-center">
            <br>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/ZsO2gbE2f80" frameborder="0"
                    allowfullscreen></iframe>
        </div>
        </row>
    </div>
</div>
<div class="brown wrapper">
    <div class="container bg">
        <div class="col-xs-12 whiteText padd">

            <p class="text-center padd">

                <img class="graphic" src="/Resources/Images/Aladdin_Instructie_Graphic.png"/>

            <div class="text-center">
                {if empty($user)}
                    <a class="btn btn-register start btn-lg" href="/Account/action=register">Registreer</a>
                {else}
                    <a class="btn btn-register start btn-lg" href="/Dashboard">Naar Dashboard</a>
                {/if}
            </div>
            </p>

        </div>
        </row>
    </div>
</div>

<div class=" white">
    <div class="container bg">
        <div class="col-xs-12">

            <h5 class="text-center"><b>Sponsors</b></h5>
            <div class="text-center">
                {foreach $sponsors as $sponsor}
                    {if !empty($sponsor->image)}
                        {if !empty($sponsor->url)}
                        <a href="http://{$sponsor->url}" target="_blank" >
                            <img class=" sponsorImage " src="{$sponsor->image}" style="text-align: center">
                        </a>
                        {else}
                            <img class=" sponsorImage " src="{$sponsor->image}" style="text-align: center">
                        {/if}
                    {/if}
                {/foreach}
            </div>
        </div>
        </row>
    </div>
</div>


</div>
<script>
    // get the video
    var video = document.querySelector('#bgvid');
    // use the whole window and a *named function*
    window.addEventListener('touchstart', function videoStart() {
        video.play();
        // remove from the window and call the function we are removing
        this.removeEventListener('touchstart', videoStart);
    });

</script>

