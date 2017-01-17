define(
  ['jquery', 'ko', 'uiComponent', 'underscore', 'Magento_Checkout/js/model/step-navigator'],
  function ($, ko, Component, _, stepNavigator) {
    'use strict';

    return Component.extend({
      defaults: {
        template: 'Botamp_Botamp/order_notifications_template'
      },
      isVisible: ko.observable(window.checkoutConfig.orderNotificationsEnabled),
      appId: ko.observable(window.checkoutConfig.botampPageAttributes.appId),
      pageId: ko.observable(window.checkoutConfig.botampPageAttributes.pageId),
      ref: ko.observable(window.checkoutConfig.botampPageAttributes.ref),
      initialize: function () {
        this._super();
        stepNavigator.registerStep(
          'order_notifications',
          null,
          'Order Notifications',
          this.isVisible,
          _.bind(this.navigate, this),
          5
        );

        initFacebookPlugin();

        return this;
      },
      navigate: function () {},
      navigateToNextStep: function () { stepNavigator.next(); }
    });
  }
);

function initFacebookPlugin() {
  window.fbAsyncInit = function() {
    FB.init({
      appId      : window.checkoutConfig.botampPageAttributes.appId,
      xfbml      : true,
      version    : 'v2.6'
    });
  };

  (function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = '//connect.facebook.net/en_US/sdk.js';
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
}
