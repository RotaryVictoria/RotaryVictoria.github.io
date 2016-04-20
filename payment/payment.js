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
    var monthlyConfig, yearlyConfig, masterConfig, initiationConfig,
        stripeMonth, stripeYear, stripeInitiation,
        stripeToClassSelector;

    masterConfig = {
        // key: 'pk_test_4tURHGoBPy9cryJxpXql7zX0',
        key: 'pk_live_kbw7MfDr5mZmhc7XQPfqYstI',
        panelLabel: 'Subscribe',
        allowRememberMe: false,
        image: "https://victoriarotary.com/assets/img/RotaryMoE_Black.png",
        token: uploadToken,
    };
    monthlyConfig = {
        name: "Monthly",
        description: "Monthly Subscription ($25 per month)"
    };
    yearlyConfig = {
        name: "Yearly",
        description: "Yearly Subscription ($300 per year)."
    };
    initiationConfig = {
      name: "Membership Initiation",
      description: "A one time payment",
      amount: 3000,
    };

    $.extend(monthlyConfig, masterConfig);
    $.extend(yearlyConfig, masterConfig);
    $.extend(initiationConfig, masterConfig);

    initiationConfig.panelLabel = 'Pay'; //urg

    stripeMonth = StripeCheckout.configure(monthlyConfig);
    stripeYear = StripeCheckout.configure(yearlyConfig);
    stripeInitiation = StripeCheckout.configure(initiationConfig);

    stripeToClassSelector = {
        'stripe-year' : stripeYear,
        'stripe-month' : stripeMonth,
        'stripe-initiation' : stripeInitiation,
    };

    $('body').on('click', '.stripe-subscribe', function(e){
        if ( $(this).hasClass('stripe-year')  ){
            window.selectedSubscriptionType = 'year';
            stripeYear.open();
        }
        else if ( $(this).hasClass('stripe-month')) {
            window.selectedSubscriptionType = 'month';
            stripeMonth.open();
        }
        else if ( $(this).hasClass('stripe-initiation')) {
            window.selectedSubscriptionType = 'initiation';
            stripeInitiation.open();
        }
        e.preventDefault();
    });
}

function uploadToken(token){
  var data = {
    email: token.email,
    stripeToken: token.id,
    subscriptionPlan: window.selectedSubscriptionType,
  }
  $.post('/payment/php/billing.php', data, function(response){
    // console.log(response);
    $('#js-user-email').text(token.email);
    $('#payment-success-modal').modal();
  });
}


