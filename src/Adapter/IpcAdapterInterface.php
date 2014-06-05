<?php

namespace MarkInJapan\Ipc\Adapter;

interface IpcAdapterInterface
{
    /**
     * Send IPC message
     * @param string $message
     * @return self
     */
    public function send($message);

    /**
     * Receive IPC message
     * @return string
     */
    public function receive();

    /**
     * Get last "send" message
     * @return string
     */
    public function getLastSend();

    /**
     * Get last "receive" message
     * @return string
     */
    public function getLastReceive();
}