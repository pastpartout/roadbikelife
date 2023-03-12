$(document).ready(function(){
    var options = {

    };

    options.ui = {
    //                container: "#pwd-container",
        showVerdictsInsideProgressBar: true,
        showErrors: true,
        colorClasses: ["danger", "warning", "success", "success", "success", "success"],
        progressExtraCssClasses: 'mt-2 bg-light',
        lng: 'de',

    };
    options.rules = {
        activated: {
            wordTwoCharacterClasses: false,
            wordRepetitions: true
        },
        // raisePower:1.8,
        scores: {
            wordNotEmail: -100,
            wordMinLength: -100,
            wordMaxLength: -50,
            wordInvalidChar: -100,
            wordSimilarToUsername: -100,
            wordSequences: -25,
            wordTwoCharacterClasses: 2,
            wordRepetitions: -100,
            wordLowercase: 1,
            wordUppercase: 3,
            wordOneNumber: 3,
            wordThreeNumbers: 5,
            wordOneSpecialChar: 3,
            wordTwoSpecialChar: 5,
            wordUpperLowerCombo: 2,
            wordLetterNumberCombo: 2,
            wordLetterNumberCharCombo: 2
        }
    };
    options.common = {
        usernameField: '[name="jform[email1]"]',
        minChar: 4,
        onLoad: function () {
            $('.progress,.error-list').addClass('hide');
        },
        onKeyUp: function (evt, data) {
           var length = $(evt.target).val().length;
           if(length > 0) {
               $('.progress,.error-list').removeClass('hide');
           } else {
               $('.progress,.error-list').addClass('hide');
           }
        },
        onScore: function (options, word, totalScoreCalculated) {

            if(totalScoreCalculated > 14) {
                $("button[type='submit']").removeClass('disabled');
                // $("button[type='submit']").attr('title', "" );
                $(':password').closest('form').on("submit",function(e){
                    $(':password').closest('form').off('submit');
                    $(':password').closest('form').submit();
                    // return true;
                })
            } else {
                // $("button[type='submit']").attr('title', "Das Passwort ist nicht sicher genug" );
                $("button[type='submit']").addClass('disabled');
                $("button[type='submit']").attr('title', "" );
                $(':password').closest('form').on("submit",function(e){
                    e.preventDefault();
                })
            }
            return totalScoreCalculated;
        },


    };




    i18next.init({
        lng: 'de', // evtl. use language-detector https://github.com/i18next/i18next-browser-languageDetector
        resources: { // evtl. load via xhr https://github.com/i18next/i18next-xhr-backend
            de: {
                translation: {
                    "wordMinLength": "Das Passwort ist zu kurz",
                    "wordMaxLength": "Das Passwort ist zu lang",
                    "wordInvalidChar": "Das Passwort Enthält ein ungültiges Zeichen",
                    "wordNotEmail": "Das Passwort darf die E-Mail Adresse nicht enthalten",
                    "wordSimilarToUsername": "Das Passwort darf die E-Mail-Adresse nicht enthalten",
                    "wordTwoCharacterClasses": "Bitte Buchstaben und Ziffern verwenden",
                    "wordRepetitions": "Zu viele Wiederholungen",
                    "wordSequences": "Das Passwort enthält Zeichensequenzen",
                    "errorList": "Fehler:",
                    "veryWeak": "Passwortsicherheit sehr schwach",
                    "weak": "Passwortsicherheit schwach",
                    "normal": "Passwortsicherheit normal",
                    "medium": "Passwortsicherheit mittel",
                    "strong": "Passwortsicherheit stark",
                    "veryStrong": "Passwortsicherheit sehr stark"
                }


            }
        }
    }, function(err, t) {
        $('#jform_password1').pwstrength(options);
    });

    $(document).on('click',"button.disabled",function(){
        $(this).tooltip('show');
    });
});

