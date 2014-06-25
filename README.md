ipc
===

Simple ZF2-compatible interprocess communication library, using various mediums for communication.

**WARNING** This project is still under heavy development, and is not yet suitable for cloning or testing!

Provides
--------

### DbAdapter

DB adapter for sending and receiving messages. Requires zendframework/zend-db for DB communication.

### ApiAdapter

API adapter for sending and receiving messages. Requires markinjapan/rest-api-client for API communication.

### IpcEvent

Event containing message just sent or received (depending on context).