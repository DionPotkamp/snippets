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
    function geefHetGoedeAntwoord() {
        for (x in json) {
            if (json[x][0].vraag.id === de_vraag.id) {
                for (a in json[x][1]) {
                    if (json[x][1][a].antwoord.type === 1) {
                        // Juiste antwoord gevonden
                        // Meerkeuze
                        if (de_vraag.id_vraag_type === 2 || de_vraag.id_vraag_type === 15) {
                            document.getElementById(json[x][1][a].antwoord.id).style.backgroundColor = "lightgreen";
                        }
                        // Invulvraag
                        if (de_vraag.id_vraag_type === 1 || de_vraag.id_vraag_type === 16) {
                            document.getElementById('vraagstelling').querySelector('input').value = json[x][1][a].antwoord.tekst;
                        }
                        // Verbeter de zin
                        if (de_vraag.id_vraag_type === 9) {
                            document.getElementById('antwoord_verbeter').value = json[x][1][a].antwoord.tekst;
                        }
                        // Hiermee trigger je de volgende vraag
                        // setTimeout(function () {
                        //     $('#next').trigger('click');
                        // }, 300);
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
