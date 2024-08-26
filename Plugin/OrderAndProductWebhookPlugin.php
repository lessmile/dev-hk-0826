<?php
namespace Vendor\Module\Plugin;

use Magento\Sales\Model\Order;
use Magento\Catalog\Model\Product;
use Magento\Framework\HTTP\Client\Curl;

class OrderAndProductWebhookPlugin
{
    protected $curl;

    public function __construct(Curl $curl)
    {
        $this->curl = $curl;
    }

    public function afterSave($subject, $result)
    {
        // 针对订单对象的逻辑
        if ($subject instanceof Order) {
            if ($subject->getIsObjectNew() || $subject->hasDataChanges()) {
                $data = ['order_id' => $subject->getId(), 'status' => $subject->getStatus()];
                $webhookUrl = 'https://enocx9j9jmkxc.x.pipedream.net/magento/dev/webhooks';
                $this->curl->post($webhookUrl, json_encode($data));
            }
        }

        // 针对商品对象的逻辑
        if ($subject instanceof Product) {
            if ($subject->getIsObjectNew() || $subject->hasDataChanges()) {
                $data = ['product_id' => $subject->getId(), 'sku' => $subject->getSku()];
                $webhookUrl = 'https://enocx9j9jmkxc.x.pipedream.net/magento/dev/webhooks';
                $this->curl->post($webhookUrl, json_encode($data));
            }
        }

        return $result;
    }
}
