<?php
namespace Botamp\Botamp\Helper;


class OrderHelper
{
  private $objectManager;
  private $order;

  public function __construct($order, $objectManager) {
    $this->order = $order;
    $this->objectManager = $objectManager;
  }

  public function getOrderMeta() {
    $address = $this->order->getShippingAddress() === null ? $this->order->getBillingAddress() : $this->order->getShippingAddress();

    return [
      'recipient_name' => $this->order->getCustomerName(),
      'order_number' => $this->order->getRealOrderId(),
      'currency' => $this->order->getOrderCurrencyCode(),
      'payment_method' => $this->order->getPayment()->getMethodInstance()->getTitle(),
      'order_url' => $this->getOrderFrontendUrl($this->order),
      'timestamp' => strtotime($this->order->getCreatedAt()),
      'address' => [
        'street_1' => $address->getStreet()[0],
        'street_2' => $address->getStreet()[0],
        'city' => $address->getCity(),
        'postal_code' => $address->getPostcode(),
        'state' => $address->getRegion(),
        'country' => $address->getCountryId(),
      ],
      'elements' => $this->getOrderElements($this->order),
      'summary' => [
        'subtotal' => $this->order->getSubtotal(),
        'shipping_cost' => $this->order->getShippingAmount(),
        'total_tax' => $this->order->getTaxAmount(),
        'total_cost' => $this->order->getGrandTotal(),
      ],
      'adjustments' => $this->getOrderAdjustments($this->order)
    ];
  }

  protected function getOrderElements() {
    $elements = [];
    foreach($this->order->getAllItems() as $item) {
      $elements[] = [
        'title' => $item->getName(),
        'subtitle' => '',
        'quantity' => (int)$item->getQtyOrdered(),
        'price' => $item->getPrice(),
        'currency' => $this->order->getOrderCurrencyCode(),
        // 'image_url' => $this->getProductImageUrl($item->getProduct()),
      ];
    }

    return $elements;
  }

  protected function getOrderAdjustments() {
    $adjustments = [];
    if(($adjustment = $this->order->getAdjustmentNegative()) !== null) {
      $adjustments[] = [
        'name' => 'Negative Adjustment',
        'amount' => $adjustment
      ];
    }
    if(($adjustment = $this->order->getAdjustmentPositive()) !== null) {
      $adjustments[] = [
        'name' => 'Positive Adjustment',
        'amount' => $adjustment
      ];
    }

    return $adjustments;
  }

  protected function getOrderFrontendUrl() {
    $urlModel = $this->objectManager->create('Magento\Framework\Url');
    return $urlModel->getUrl('sales/order/view', ['order_id' => $this->order->getId()]);
  }
}
