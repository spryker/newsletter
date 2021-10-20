<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Newsletter\Dependency\Facade\NewsletterToGlossaryBridge;
use Spryker\Zed\Newsletter\Dependency\Facade\NewsletterToMailBridge;
use Spryker\Zed\Newsletter\Dependency\Service\NewsletterToUtilValidateServiceBridge;

/**
 * @method \Spryker\Zed\Newsletter\NewsletterConfig getConfig()
 */
class NewsletterDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_MAIL = 'mail facade';

    /**
     * @var string
     */
    public const FACADE_GLOSSARY = 'glossary facade';

    /**
     * @var string
     */
    public const SERVICE_UTIL_VALIDATE = 'SERVICE_UTIL_VALIDATE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container->set(static::FACADE_MAIL, function (Container $container) {
            return new NewsletterToMailBridge($container->getLocator()->mail()->facade());
        });

        $container = $this->addUtilValidateService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container->set(static::FACADE_MAIL, function (Container $container) {
            return new NewsletterToMailBridge($container->getLocator()->mail()->facade());
        });
        $container->set(static::FACADE_GLOSSARY, function (Container $container) {
            return new NewsletterToGlossaryBridge($container->getLocator()->glossary()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilValidateService(Container $container)
    {
        $container->set(static::SERVICE_UTIL_VALIDATE, function (Container $container) {
            return new NewsletterToUtilValidateServiceBridge($container->getLocator()->utilValidate()->service());
        });

        return $container;
    }
}
