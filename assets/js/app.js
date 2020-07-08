/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/ecussons.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');
require('../js/typeahead.js/typeahead.bundle.js');
window.Bloodhound = require('../js/typeahead.js/bloodhound.min.js');

$( document ).ready(function() {
    var states = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: '/search?q=%QUERY',
            wildcard: '%QUERY'
        }
    });

    $('.typeahead').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'states',
            display: 'name',
            templates: {
                empty: [
                    '<div class="empty-message">',
                    'Aucun résultat.',
                    '</div>'
                ].join('\n'),
                suggestion: function(data) {
                    return '<p class="has-background-white" class="is-size-7"><span class="is-size-7"><strong>' + data.name + '</strong></span><span class="is-size-9 is-block">'+data.arbo+'</span></p>';
                }
            },
            source: states
        });

    $('.typeahead').bind('typeahead:select', function(ev, suggestion) {
        window.location = suggestion.url ;
    });
});