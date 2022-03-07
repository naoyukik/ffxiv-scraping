<?php

namespace App\ItemProcessors;

use RoachPHP\ItemPipeline\ItemInterface;
use RoachPHP\ItemPipeline\Processors\ItemProcessorInterface;
use RoachPHP\Support\Configurable;

class MyCharacterProcessor implements ItemProcessorInterface
{
    use Configurable;

    /**
     * @param  ItemInterface  $item
     * @return ItemInterface
     */
    public function processItem(ItemInterface $item): ItemInterface
    {
        /** @var string $characterName */
        $characterName = $item->get('character_name');
        // TODO: DBへの保存をここに入れる
        return $item;
    }
}
