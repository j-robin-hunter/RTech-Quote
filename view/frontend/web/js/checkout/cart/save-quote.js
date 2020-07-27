/**
* Copyright © 2019 Roma Technology Limited. All rights reserved.
* See COPYING.txt for license details.
*/
define([
  'jquery',
  'uiComponent',
  'Magento_Ui/js/modal/confirm',
  'Magento_Customer/js/customer-data',
  'Magento_Customer/js/section-config',
  'Magento_Checkout/js/model/quote',
  'Magento_Checkout/js/model/cart/cache',
  'mage/url',
  'mage/translate'
],
function ($, Component, confirmation, customerData, sectionConfig, quote, cache, url, __) {
  'use strict';

  return Component.extend({
    buttonSelector: null,

    initialize: function () {
      this._super();
      var self = this;

      var cart = customerData.get('cart');
      $(self.buttonSelector).toggle(cart().summary_count > 0);
      cart.subscribe(
        function (value) {
          $(self.buttonSelector).toggle(value.summary_count > 0);
        }
      );

      $(this.buttonSelector).click(function () {
        if (!quote.shippingMethod()) {
          customerData.set('messages', {
            messages: [{
                text: __('The shipping method is missing. Select the shipping method and try again.'),
                type: 'notice'
            }]
        });
          return false;
        }
        confirmation({
          title: __('Save as Estimate'),
          content: __('Are you sure you want to save the Shopping Cart as an Estimate? This will clear your Cart.'),
          actions: {
            confirm: function () {
              var data = cache.get('cart-data');
              var action = 'quote/quote/save';
              var sections = sectionConfig.getAffectedSections(action);
              if (sections) {
                  customerData.invalidate(sections);
              }
              
              var page = url.build(action) + 
                '?shippingMethod=' + data.shippingCarrierCode + '_' + data.shippingMethodCode +
                '&countryId=' + data.address.countryId +
                '&regionId=' + data.address.regionId +
                '&regionCode=' + data.address.regionCode +
                '&region=' + data.address.region +
                '&postcode=' + data.address.postcode;
              window.location.href = page;
            }
          }
        })
      });
    },
  });
  }
);