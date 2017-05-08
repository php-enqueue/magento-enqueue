
Magento PHP Enqueue Integration
===============================

Install
-------
We use magento composer installer to install this module.
See more https://github.com/Cotya/magento-composer-installer

Example of `composer.json`:

```json
{
    "name": "magento/project",
    "description": "Magento Project",
    "repositories": [
      {
        "type": "vcs",
        "url": "git@github.com:AydinHassan/magento-community.git"
      }
    ],
    "require": {
        "magento-hackathon/magento-composer-installer": "~3.0",
        "aydin-hassan/magento-core-composer-installer" : "~1.0",
        "magento/magento" : "1.9.1.0",
        "enqueue/magento-enqueue": "*@dev"
    },
    "extra":{
        "magento-root-dir": "web/"
    }
}
```

Publish Message
---------------

```php

Mage::helper('enqueue')->send('async-job', 'payload');

```

Message Consumer Class
----------------------
```php
<?php

use Enqueue\Psr\PsrContext;
use Enqueue\Psr\PsrMessage;
use Enqueue\Psr\PsrProcessor;

class Acme_Module_Helper_Async_Job implements PsrProcessor
{
    public function process(PsrMessage $message, PsrContext $context)
    {
        // do job
        // $message->getBody() -> 'payload'

        return self::ACK;         // acknowledge message
        // return self::REJECT;   // reject message
        // return self::REQUEUE;  // requeue message
    }
}
```

Bind message processor to topic
-------------------------------

app/etc/local.xml
```xml
<config>
  <default>
    <enqueue>
      <processors>
        <async-job-processor>
          <topic>async-job</topic>
          <helper>acme/async_job</helper>
        </async-job-processor>
        <processor2>
          <topic>topic2</topic>
          <helper>magento-helper-name2</helper>
        </processor2>
      </processors>
    </enqueue>
  </default>
</config>
```

Run message consumer
--------------------
```bash
bash/> php shell/enqueue.php enqueue:consume -vvv --setup-broker
```

More console commands
---------------------
```bash
  enqueue:consume       [enq:c] A client's worker that processes messages. By default it connects to default queue. It select an appropriate message processor based on a message headers
  enqueue:produce       [enq:p] A command to send a message to topic
  enqueue:queues        [enq:m:q|debug:enqueue:queues] A command shows all available queues and some information about them.
  enqueue:setup-broker  [enq:sb] Creates all required queues
  enqueue:topics        [enq:m:t|debug:enqueue:topics] A command shows all available topics and some information about them.
```
