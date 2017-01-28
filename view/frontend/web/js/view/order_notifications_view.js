define(
  ['ko', 'uiComponent'],
  function (ko, Component) {
    'use strict';

    return Component.extend({
      defaults: {
        template: 'Botamp_Botamp/order_notifications_template'
      },
      appId: ko.observable(window.checkoutConfig.botampPageAttributes.appId),
      pageId: ko.observable(window.checkoutConfig.botampPageAttributes.pageId),
      ref: ko.observable(window.checkoutConfig.botampPageAttributes.ref),
      initialize: function () {
        this._super();
        setTimeout(initMessengerPlugin(), 0);
        return this;
      }
    });
  }
);

function initMessengerPlugin() {
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
