define(
  ['jquery', 'ko', 'uiComponent', 'underscore', 'Magento_Checkout/js/model/step-navigator'],
  function ($, ko, Component, _, stepNavigator) {
    'use strict';

    return Component.extend({
      defaults: {
        template: 'Botamp_Botamp/order_notifications_template'
      },
      isVisible: ko.computed(function(){
        console.log("got an update");
        stepNavigator.steps();
        // var currentStepIndex = stepNavigator.getActiveItemIndex();
        // var currentStep = stepNavigator.steps()[currentStepIndex];
        return window.location.hash === "#payment";
      }),
      initialize: function () {
        this._super();
        stepNavigator.registerStep(
          'order_notifications',
          null,
          'Order Notifications',
          this.isVisible,
          _.bind(this.navigate, this),
          25
        );

        return this;
      },
      navigate: function () {},
      navigateToNextStep: function () { stepNavigator.next(); }
    });
  }
);
