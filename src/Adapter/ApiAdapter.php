<?php

namespace MarkInJapan\Ipc\Adapter;

use Rej\RestApiClient\ApiGateway;

class ApiAdapter extends AbstractAdapter
{
    /**
     * Send IPC message
     * @param string $message
     * @return self
     */
    public function send($message)
    {
        $this->_last_send = $message;

        $this->_gateway->update($this->_channel, array(
            $this->_send_identifier => $this->_last_send,
        ));

        $this->getEventManager()->trigger('ipc.sent', $this, array('message' => $this->_last_send));

        return $this;
    }

    /**
     * Receive IPC message
     * @return string
     */
    public function receive()
    {
        $message = $this->_gateway->fetch($this->_channel);

        $this->_last_recv = $message[$this->_recv_identifier];

        $this->getEventManager()->trigger('ipc.received', $this, array('message' => $this->_last_recv));

        return $this->_last_recv;
    }
}