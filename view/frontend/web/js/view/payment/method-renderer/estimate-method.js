/**
* Copyright 2019 © Roma Technology Ltd. All rights reserved.
* See COPYING.txt for license details.
**/
define([
  'Magento_Checkout/js/view/payment/default'
], function (Component) {
  'use strict';

  return Component.extend({
    defaults: {
      template: 'RTech_Quote/payment/estimate'
    },

    getInformation: function () {
      return window.checkoutConfig.payment.information[this.item.method];
    },
    
    getBaseUrl: function() {
      return require.s.contexts._.config.baseUrl;
    }
  });
});