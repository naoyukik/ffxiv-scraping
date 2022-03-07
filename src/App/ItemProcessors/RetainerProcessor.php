<?php

namespace App\ItemProcessors;

use RoachPHP\ItemPipeline\ItemInterface;
use RoachPHP\ItemPipeline\Processors\ItemProcessorInterface;
use RoachPHP\Support\Configurable;

class RetainerProcessor implements ItemProcessorInterface
{
    use Configurable;

    /**
     * @param  ItemInterface  $item
     * @return ItemInterface
     */
    public function processItem(ItemInterface $item): ItemInterface
    {
        // TODO: DBへの保存
        return $item;
    }
}
