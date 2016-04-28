<video autoplay id="bgvid" loop muted poster="/Resources/Images/Coverr-flowers.jpg">
    <source src="/Resources/Images/Coverr-flowers.webm" type="video/webm">
    <source src="/Resources/Images/Coverr-flowers.mp4" type="video/mp4">
</video>

<div class="jumbotron" id="main-hero">

    <div class="container">

        <div class="col-md-12">

            <h1>Aladdin</h1>
            <h4><em>Wat is jouw hartenwens?</em></h4>
            <br><br><br>
            <blockquote>
                <p>
                    Korte samenvatting of een mooie quote. <br> Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Maecenas imperdiet est sit amet neque interdum luctus. Fusce interdum eros non massa efficitur
                    congue id ut urna.
                    <footer>Source</footer>
            </blockquote>

            <div class="text-center">
                <a class="btn btn-default start btn-lg" href="/Account/action=register">Registreer</a>
            </div>
        </div>

    </div>
</div>
<div class=" white">
    <div class="container bg">
        <div class="col-xs-12">

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
        <div class="col-xs-12 col-sm-6  col-sm-offset-3 buttongrid homebuttons">
            <br><br>
            <row>
                <div class="col-xs-12 col-sm-6 col-md-4 buttongroup homebuttons">
                    <a href="/"> <img class="thumbnail" width="100%" src="/Resources/Images/Icons/bedrijven100.jpg">
                    </a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 buttongroup homebuttons">
                    <a href="/"> <img class="thumbnail" width="100%" src="/Resources/Images/Icons/beperkten100.jpg"></a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 buttongroup homebuttons">
                    <a href="/"> <img class="thumbnail" width="100%"
                                      src="/Resources/Images/Icons/grootouders100.jpg"></a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 buttongroup homebuttons">
                    <a href="/"> <img class="thumbnail" width="100%"
                                      src="/Resources/Images/Icons/man%20vrouw100.jpg"></a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 buttongroup homebuttons">
                    <a href="/"> <img class="thumbnail" width="100%" src="/Resources/Images/Icons/ster%20100.jpg"></a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 buttongroup homebuttons">
                    <a href="/"> <img class="thumbnail" width="100%" src="/Resources/Images/Icons/hart100.jpg"></a>
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
        console.log('first touch');
        // remove from the window and call the function we are removing
        this.removeEventListener('touchstart', videoStart);
    });

</script>

