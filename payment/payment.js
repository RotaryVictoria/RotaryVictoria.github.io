/**
 * TODO - Update to production keys.
 *
 * Pick a pricing table - Tenzin?
 *   http://tympanus.net/Development/PricingTablesInspiration/
 */


 // google.charts.load('current', {packages: ['corechart', 'piechart']});
 // google.charts.setOnLoadCallback(createPieChart);

$(document).on('ready', init);

function init(){
    registerStripeButtonHandlers();
    // createPieChart();
}

function registerStripeButtonHandlers(){
    var monthlyConfig, yearlyConfig, masterConfig, 
        stripeMonth, stripeYear, stripeToClassSelector;

    masterConfig = {
        key: 'pk_test_NH2D3rWVe1X79l6RHHGpeJPi',
        panelLabel: 'Subscribe',
        allowRememberMe: false,
        image: "http://159.203.203.183/assets/img/RotaryMoE_Black.png",
    };
    monthlyConfig = {
        name: "Monthly",
        description: "Monthly Subscription ($25 per month)"
    };
    yearlyConfig = {
        name: "Yearly (save 10%)",
        description: "Yearly Subscription ($270 per year)."
    };

    $.extend(monthlyConfig, masterConfig);
    $.extend(yearlyConfig, masterConfig);

    stripeMonth = StripeCheckout.configure(monthlyConfig);
    stripeYear = StripeCheckout.configure(yearlyConfig);

    stripeToClassSelector = {
        'stripe-year' : stripeYear,
        'stripe-month' : stripeMonth,
    };

    $('body').on('click', '.stripe-subscribe', function(e){
        if ( $(this).hasClass('stripe-year')  ){
            stripeYear.open();
        }
        else if ( $(this).hasClass('stripe-month')) {
            stripeMonth.open();
        }
        e.preventDefault();
    });
}


function createPieChart(){
    var data = google.visualization.arrayToDataTable([
      ['Language', 'Speakers (in millions)'],
      ['Assamese', 13], ['Bengali', 83], ['Bodo', 1.4],
      ['Dogri', 2.3], ['Gujarati', 46], ['Hindi', 300],
      ['Kannada', 38], ['Kashmiri', 5.5], ['Konkani', 5],
      ['Maithili', 20], ['Malayalam', 33], ['Manipuri', 1.5],
      ['Marathi', 72], ['Nepali', 2.9], ['Oriya', 33],
      ['Punjabi', 29], ['Sanskrit', 0.01], ['Santhali', 6.5],
      ['Sindhi', 2.5], ['Tamil', 61], ['Telugu', 74], ['Urdu', 52]
    ]);

    var options = {
      title: 'Indian Language Use',
      legend: 'none',
      pieSliceText: 'label',
      slices: {  4: {offset: 0.2},
                12: {offset: 0.3},
                14: {offset: 0.4},
                15: {offset: 0.5},
      },
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
    chart.draw(data, options);
}

