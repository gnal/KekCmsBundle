<?php

namespace Msi\CmsBundle\Provider;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("msi_cms.block_provider")
 */
class BlockProvider
{
    private $blockManager;
    private $allPagesBlocks;

    /**
     * @DI\InjectParams({
     *     "blockManager" = @DI\Inject("msi_cms.block_manager")
     * })
     */
    public function __construct($blockManager)
    {
        $this->blockManager = $blockManager;
    }

    public function getAllPagesBlocks()
    {
        if (!$this->allPagesBlocks) {
            $results = $this->blockManager->getRepository()->findBy(
                [
                    'showOnAllPages' => true,
                ],
                [
                    'sort' => 'ASC',
                ]
            );

            $this->allPagesBlocks = $results;
        }

        return $this->allPagesBlocks;
    }
}
