<?php

namespace Msi\CmsBundle\Provider;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("msi_cms.config_provider")
 */
class ConfigProvider
{
    private $configManager;
    private $config;

    /**
     * @DI\InjectParams({
     *     "configManager" = @DI\Inject("msi_cms.config_manager")
     * })
     */
    public function __construct($configManager)
    {
        $this->configManager = $configManager;
    }

    public function getConfig()
    {
        if (!$this->config) {
            $config = $this->configManager->getRepository()->findAll();

            if (!count($config)) {
                $class = $this->configManager->getClass();
                $config = new $class;
            } else {
                $config = $config[0];
            }

            $this->config = $config;
        }

        return $this->config;
    }
}
