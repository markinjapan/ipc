<?php

namespace MarkInJapan\Ipc\Adapter;

use Zend\Db\TableGateway\TableGateway;

class DbAdapter extends AbstractAdapter
{
    /**
     * Send IPC message
     * @param string $message
     * @return self
     */
    public function send($message)
    {
        $this->_last_send = $message;

        $this->_gateway->update(array(
            $this->_send_identifier => $this->_last_send,
        ), array(
            'id' => $this->_channel,
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
        $message = $this->_gateway->fetch(array(
            'id' => $this->_channel,
        ));

        $this->_last_recv = $message[$this->_recv_identifier];

        $this->getEventManager()->trigger('ipc.received', $this, array('message' => $this->_last_recv));

        return $this->_last_recv;
    }
}