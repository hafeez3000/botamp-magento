define(
    ['jquery'],
    function ($) {
    var totalProducts = BOTAMP_PRODUCT_IDS.length;
    var countProductsProcessed = 0;
    var countProductsImported = 0;

    function updateStatus(response, error = false)
    {
      if (error === true) {
        $("#unexpected-error").html(BOTAMP_IMPORT_UNEXPECTED_ERROR + ' : ' + response.responseText);
        $("#results").hide();
        return;
      }
      countProductsProcessed += 1;
      if (response.status === 'success') {
        countProductsImported += 1;
        $('#results #success').append($('<p>' + response.name + ' ' + BOTAMP_IMPORT_SUCCESS_TEXT + '</p>'));
      } else if (response.status === 'error') {
        $('#results #errors').append($('<p>' + response.name + ' ' + BOTAMP_IMPORT_ERROR_TEXT + '</p>'));
      }
      $('#success-count').text(countProductsImported + '/' + totalProducts);
      $('#errors-count').text(countProductsProcessed - countProductsImported + '/' + totalProducts);
    }

    function importProduct(productId)
    {
      $.ajax({
        url: BOTAMP_IMPORT_AJAXURL,
        type: 'GET',
        showLoader: true,
        dataType: 'json',
        data: {id: productId},
        success: function (response) {
          updateStatus(response);
          if (countProductsProcessed < totalProducts) {
            importProduct(BOTAMP_PRODUCT_IDS.shift());
          }
        },
        error: function (response) {
          updateStatus(response, true);
        }
      });
    }

    return {importProduct: importProduct};
    }
);
