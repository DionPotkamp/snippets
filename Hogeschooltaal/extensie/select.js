document.addEventListener('DOMContentLoaded', function() {
    // document.getElementById('Select').addEventListener('click', selecteerHetGoedeAntwoord);

    /*
     * Waar de magie plaatsvind
     * In het object 'json' staan alle vragen die je voor de huidige oefening/toets gaat krijgen.
     * In het object 'de_vraag' staat oa het ID van de huidige vraag op je scherm.
     * Als eerste zoek ik door json heen naar de huidige vraag aan de hand van het ID in de_vraag.
     * Voorbeeld van de huidige json vraag:
     * 0:
     *     vraag:
     *         code1: "Basis_A"
     *         code2: "1"
     *         code3: "4"
     *         id: 4329
     *         id_niveau: 2
     *         id_vraag_type: 2
     *         tekst: "<p class='opdracht'>Welk werkwoord in de volgende zin is een infinitief?</p><br><p class='vraag'>Tot overmaat van ramp kende de keeper een zwak moment waardoor Ronaldo 3-2 kon scoren.</p>"
     * 1:
     *     0: {antwoord: {id: 8881, tekst: "kende", type: 2}}
     *     1: {antwoord: {id: 8882, tekst: "kon", type: 2}}
     *     2:
     *         antwoord:
     *             id: 8883
     *             tekst: "scoren"
     *             type: 1
     * json[1][2].antwoord.type staat voor of het een correct antwoord is of niet. 1 = goed, 2 = fout.
     * json[1][2].antwoord.tekst staat voor het correcte antwoord.
     * Als het een meerkeuze vraag is gebruik ik het ID om de juiste knop te vinden.
     * Als er iets ingevuld moet worden dan gebruik ik de tekst
     */
    // Create new script element
    function laadSimulateDrag() {
        if (document.getElementById('JSimulate') === null) {
            const script = document.createElement('script');
            script.id = 'JSimulate';
            script.src = 'https://rawgit.com/jquery/jquery-ui/1-11-stable/external/jquery-simulate/jquery.simulate.js';
            // Append to the `head` element
            document.head.appendChild(script);
        }
        if (document.getElementById('Jui') === null) {
            const script = document.createElement('script');
            script.id = 'Jui';
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js';
            // Append to the `head` element
            document.head.appendChild(script);
        }
    }
    // laadSimulateDrag();

    function simulateDrag(sleep_blok, target) {
       let draggable = sleep_blok.draggable(),
            droppable = target.droppable(),

            droppableOffset = droppable.offset(),
            draggableOffset = draggable.offset(),
            dx = droppableOffset.left - draggableOffset.left,
            dy = droppableOffset.top - draggableOffset.top;

        draggable.simulate("drag", {
            dx: dx,
            dy: dy
        });
    }

    function gaNaarDeVolgendeVraag() {
        setTimeout(function () {
            $('#next').trigger('click');
        }, 300);
    }
    let old_verder_vraag = verder_vraag;
    verder_vraag = function () {
        old_verder_vraag.apply(this, arguments);
        gaNaarDeVolgendeVraag();
    };
    function geefHetGoedeAntwoord() {
        for (x in json) {
            if (json[x][0].vraag.id === de_vraag.id) {
                for (a in json[x][1]) {
                    if (json[x][1][a].antwoord.type === 1) {
                        // Juiste antwoord gevonden
                        // Meerkeuze
                        if (de_vraag.id_vraag_type === 2 || de_vraag.id_vraag_type === 15) {
                            document.getElementById(json[x][1][a].antwoord.id).style.backgroundColor = "lightgreen";
                            setTimeout(function () {
                                $('#'+json[x][1][a].antwoord.id).trigger('click');
                            }, 300);
                        }
                        // Invulvraag
                        if (de_vraag.id_vraag_type === 1 || de_vraag.id_vraag_type === 16) {
                            document.getElementById('vraagstelling').querySelector('input').value = json[x][1][a].antwoord.tekst;
                            setTimeout(function () {
                                $('#verstuur_antwoord').trigger('click');
                            }, 300);
                        }
                        // Verbeter de zin
                        if (de_vraag.id_vraag_type === 9) {
                            document.getElementById('antwoord_verbeter').value = json[x][1][a].antwoord.tekst;
                            setTimeout(function () {
                                $('#verstuur_antwoord').trigger('click');
                            }, 300);
                        }
                        // Slepen
                        if (de_vraag.id_vraag_type === 13) {
                            let target = document.getElementById('vraagstelling').querySelector('span.reciever');
                            let sleep_blokken = document.getElementById('vraagstelling').querySelectorAll('span.drag_me');
                            let antwoord = json[x][1][a].antwoord.tekst;

                            for (x in sleep_blokken) {
                                if (sleep_blokken[x].outerText === antwoord) {
                                    sleep_blokken[x].style.backgroundColor = "lightgreen";
                                    // simulateDrag(sleep_blokken[x], target);
                                }
                            }
                            // setTimeout(function () {
                            //     $('#verstuur_antwoord').trigger('click');
                            // }, 300);
                        }
                        return;
                    }
                }
            }
        }
    }
    geefHetGoedeAntwoord();
    /*
     * In go_vraag word de huidige vraag verwerkt en op het scherm gezet.
     * Deze Kopieer ik en voeg hier mijn functie aan toe.
     */
    let old_go_vraag = go_vraag;
    go_vraag = function () {
        old_go_vraag.apply(this, arguments);
        geefHetGoedeAntwoord();
    };

})
