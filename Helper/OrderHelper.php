<?php
namespace Botamp\Botamp\Helper;


class OrderHelper
{
  protected $urlModel;
  protected $productHelper;

  public function __construct(
    \Magento\Framework\Url $urlModel,
    \Botamp\Botamp\Helper\ProductHelper $productHelper
  ) {
    $this->urlModel = $urlModel;
    $this->productHelper = $productHelper;
  }

  public function getOrderMeta($order) {
    $address = $order->getShippingAddress() === null ? $order->getBillingAddress() : $order->getShippingAddress();

    return [
      'recipient_name' => $order->getCustomerName(),
      'order_number' => $order->getRealOrderId(),
      'currency' => $order->getOrderCurrencyCode(),
      'payment_method' => $order->getPayment()->getMethodInstance()->getTitle(),
      'order_url' => $this->getOrderFrontendUrl($order),
      'timestamp' => strtotime($order->getCreatedAt()),
      'address' => [
        'street_1' => $address->getStreet()[0],
        'street_2' => $address->getStreet()[0],
        'city' => $address->getCity(),
        'postal_code' => $address->getPostcode(),
        'state' => $address->getRegion(),
        'country' => $address->getCountryId(),
      ],
      'elements' => $this->getOrderElements($order),
      'summary' => [
        'subtotal' => $order->getSubtotal(),
        'shipping_cost' => $order->getShippingAmount(),
        'total_tax' => $order->getTaxAmount(),
        'total_cost' => $order->getGrandTotal(),
      ],
      'adjustments' => $this->getOrderAdjustments($order)
    ];
  }

  protected function getOrderElements($order) {
    $elements = [];
    foreach($order->getAllItems() as $item) {
      $elements[] = [
        'title' => $item->getName(),
        'subtitle' => '',
        'quantity' => (int)$item->getQtyOrdered(),
        'price' => $item->getPrice(),
        'currency' => $order->getOrderCurrencyCode(),
        // 'image_url' => $this->getProductImageUrl($item->getProduct()),
      ];
    }

    return $elements;
  }

  protected function getOrderAdjustments($order) {
    $adjustments = [];
    if(($adjustment = $order->getAdjustmentNegative()) !== null) {
      $adjustments[] = [
        'name' => 'Negative Adjustment',
        'amount' => $adjustment
      ];
    }
    if(($adjustment = $order->getAdjustmentPositive()) !== null) {
      $adjustments[] = [
        'name' => 'Positive Adjustment',
        'amount' => $adjustment
      ];
    }

    return $adjustments;
  }

  protected function getOrderFrontendUrl($order) {
    return $this->urlModel->getUrl('sales/order/view', ['order_id' => $order->getId()]);
  }
}
