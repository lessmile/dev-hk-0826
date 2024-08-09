<?php
namespace Wtftt\Webhook\Plugin;

use Magento\Sales\Model\Order;
use Magento\Framework\HTTP\Client\Curl;

class OrderPlugin
{
    protected $curl;

    public function __construct(Curl $curl)
    {
        $this->curl = $curl;
    }

    public function afterSave(Order $subject, $result)
    {
        if ($subject->getIsObjectNew() || $subject->dataHasChangedFor('status')) {
            $data = ['order_id' => $subject->getId(), 'status' => $subject->getStatus()];
            $webhookUrl = 'https://enocx9j9jmkxc.x.pipedream.net/magento/dev/webhooks';
            $this->curl->post($webhookUrl, json_encode($data));
        }
        return $result;
    }
}
