<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Newsletter\Persistence\NewsletterPersistenceFactory getFactory()
 */
class NewsletterQueryContainer extends AbstractQueryContainer implements NewsletterQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $email
     * @param string $newsletterTypeName
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriptionQuery
     */
    public function querySubscriptionByEmailAndNewsletterTypeName($email, $newsletterTypeName)
    {
        /** @var \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriptionQuery $subscriptionQuery */
        $subscriptionQuery = $this->querySubscription()
            ->joinSpyNewsletterType()
            ->useSpyNewsletterTypeQuery()
                ->filterByName($newsletterTypeName)
            ->endUse()
            ->joinSpyNewsletterSubscriber()
            ->useSpyNewsletterSubscriberQuery()
                ->filterByEmail($email)
            ->endUse()
            ->setIgnoreCase(true);

        return $subscriptionQuery;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $subscriberKey
     * @param string $newsletterTypeName
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriptionQuery
     */
    public function querySubscriptionBySubscriberKeyAndNewsletterTypeName($subscriberKey, $newsletterTypeName)
    {
        /** @var \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriptionQuery $subscriptionQuery */
        $subscriptionQuery = $this->querySubscription()
            ->joinSpyNewsletterType()
            ->useSpyNewsletterTypeQuery()
                ->filterByName($newsletterTypeName)
            ->endUse()
            ->joinSpyNewsletterSubscriber()
            ->useSpyNewsletterSubscriberQuery()
                ->filterBySubscriberKey($subscriberKey)
            ->endUse();

        return $subscriptionQuery;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCustomer
     * @param string $newsletterTypeName
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriptionQuery
     */
    public function querySubscriptionByIdCustomerAndNewsletterTypeName($idCustomer, $newsletterTypeName)
    {
        /** @var \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriptionQuery $subscriptionQuery */
        $subscriptionQuery = $this->querySubscription()
            ->joinSpyNewsletterType()
            ->useSpyNewsletterTypeQuery()
                ->filterByName($newsletterTypeName)
            ->endUse()
            ->joinSpyNewsletterSubscriber()
            ->useSpyNewsletterSubscriberQuery()
                ->filterByFkCustomer($idCustomer)
            ->endUse();

        return $subscriptionQuery;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriberQuery
     */
    public function querySubscriberByIdCustomer($idCustomer)
    {
        return $this->querySubscriber()
            ->filterByFkCustomer($idCustomer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $email
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriberQuery
     */
    public function querySubscriberByEmail($email)
    {
        return $this->querySubscriber()
            ->filterByEmail($email);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriberQuery
     */
    public function querySubscriber()
    {
        return $this->getFactory()->createNewsletterSubscriberQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriptionQuery
     */
    public function querySubscription()
    {
        return $this->getFactory()->createNewsletterSubscriptionQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterTypeQuery
     */
    public function queryNewsletterType()
    {
        return $this->getFactory()->createNewsletterTypeQuery();
    }
}
