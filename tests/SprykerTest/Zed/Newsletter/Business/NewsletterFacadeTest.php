<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Newsletter\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterTypeTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriberQuery;
use Orm\Zed\Newsletter\Persistence\SpyNewsletterType;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Newsletter\Business\NewsletterBusinessFactory;
use Spryker\Zed\Newsletter\Business\NewsletterFacade;
use Spryker\Zed\Newsletter\Dependency\Facade\NewsletterToMailInterface;
use Spryker\Zed\Newsletter\NewsletterDependencyProvider;
use Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Newsletter
 * @group Business
 * @group Facade
 * @group NewsletterFacadeTest
 * Add your own group annotations below this line
 */
class NewsletterFacadeTest extends Unit
{
    /**
     * @var string
     */
    public const TEST_TYPE1 = 'TEST_TYPE1';

    /**
     * @var string
     */
    public const TEST_TYPE2 = 'TEST_TYPE2';

    /**
     * @var \Spryker\Zed\Newsletter\Business\NewsletterFacade
     */
    protected $newsletterFacade;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setNewsletterFacade();
        $this->setTestNewsletterTypes();
    }

    public function testSubscribeWithSingleOptInShouldSucceed(): void
    {
        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();
        $request->setNewsletterSubscriber($subscriber);

        $this->addTestType1ToSubscriptionRequest($request);
        $this->addTestType2ToSubscriptionRequest($request);

        $response = $this->newsletterFacade->subscribeWithSingleOptIn($request);

        foreach ($response->getSubscriptionResults() as $result) {
            $this->assertTrue($result->getIsSuccess(), (string)$result->getErrorMessage());
        }
    }

    public function testSubscribeWithSingleOptInFailsWhenEmailIsInvalid(): void
    {
        // Assign
        $subscriptionRequestTransfer = (new NewsletterSubscriptionRequestTransfer())
            ->setNewsletterSubscriber($this->createInvalidSubscriber());

        $this->addTestType1ToSubscriptionRequest($subscriptionRequestTransfer);
        $this->addTestType2ToSubscriptionRequest($subscriptionRequestTransfer);

        // Act
        $actualResult = $this->newsletterFacade->subscribeWithSingleOptIn($subscriptionRequestTransfer);

        // Assert
        foreach ($actualResult->getSubscriptionResults() as $result) {
            $this->assertFalse($result->getIsSuccess());
        }
    }

    public function testSubscribeForAlreadySubscribedTypeShouldFail(): void
    {
        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();
        $request->setNewsletterSubscriber($subscriber);

        $this->addTestType1ToSubscriptionRequest($request);

        $this->newsletterFacade->subscribeWithSingleOptIn($request);

        $response = $this->newsletterFacade->subscribeWithSingleOptIn($request);

        $this->assertFalse($response->getSubscriptionResults()[0]->getIsSuccess());
    }

    public function testSubscribeWithDoubleOptInShouldSucceed(): void
    {
        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();
        $request->setNewsletterSubscriber($subscriber);

        $this->addTestType1ToSubscriptionRequest($request);
        $this->addTestType2ToSubscriptionRequest($request);

        $response = $this->newsletterFacade->subscribeWithDoubleOptIn($request);

        foreach ($response->getSubscriptionResults() as $result) {
            $this->assertTrue($result->getIsSuccess(), (string)$result->getErrorMessage());
        }
    }

    public function testSubscribeWithDoubleOptInFailsWhenEmailIsInvalid(): void
    {
        // Assign
        $subscriptionRequestTransfer = (new NewsletterSubscriptionRequestTransfer())
            ->setNewsletterSubscriber($this->createInvalidSubscriber());

        $this->addTestType1ToSubscriptionRequest($subscriptionRequestTransfer);
        $this->addTestType2ToSubscriptionRequest($subscriptionRequestTransfer);

        // Act
        $actualResult = $this->newsletterFacade->subscribeWithDoubleOptIn($subscriptionRequestTransfer);

        // Assert
        foreach ($actualResult->getSubscriptionResults() as $result) {
            $this->assertFalse($result->getIsSuccess());
        }
    }

    public function testApproveDoubleOptInSubscriberShouldSucceed(): void
    {
        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();
        $request->setNewsletterSubscriber($subscriber);

        $this->addTestType1ToSubscriptionRequest($request);

        $this->newsletterFacade->subscribeWithDoubleOptIn($request);

        $response = $this->newsletterFacade->approveDoubleOptInSubscriber($subscriber);

        $this->assertTrue($response->getIsSuccess(), (string)$response->getErrorMessage());
    }

    public function testApproveNonExistentDoubleOptInSubscriberShouldFail(): void
    {
        $subscriber = $this->createSubscriber();

        $response = $this->newsletterFacade->approveDoubleOptInSubscriber($subscriber);

        $this->assertFalse($response->getIsSuccess());
    }

    public function testUnsubscribeFromTypesShouldSucceed(): void
    {
        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();
        $request->setNewsletterSubscriber($subscriber);

        $this->addTestType1ToSubscriptionRequest($request);
        $this->addTestType2ToSubscriptionRequest($request);

        $this->newsletterFacade->subscribeWithSingleOptIn($request);

        $response = $this->newsletterFacade->unsubscribe($request);

        foreach ($response->getSubscriptionResults() as $result) {
            $this->assertTrue($result->getIsSuccess(), (string)$result->getErrorMessage());
        }
    }

    public function testUnsubscribeFromNotSubscribedTypesShouldFail(): void
    {
        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();
        $request->setNewsletterSubscriber($subscriber);

        $this->addTestType1ToSubscriptionRequest($request);

        $this->newsletterFacade->subscribeWithSingleOptIn($request);

        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();

        $request->setNewsletterSubscriber($subscriber);
        $this->addTestType2ToSubscriptionRequest($request);

        $response = $this->newsletterFacade->unsubscribe($request);

        foreach ($response->getSubscriptionResults() as $result) {
            $this->assertFalse($result->getIsSuccess(), (string)$result->getErrorMessage());
        }
    }

    public function testCheckSubscriptionForSubscribedTypesShouldSucceed(): void
    {
        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();
        $request->setNewsletterSubscriber($subscriber);

        $this->addTestType1ToSubscriptionRequest($request);

        $this->newsletterFacade->subscribeWithSingleOptIn($request);

        $response = $this->newsletterFacade->checkSubscription($request);

        $result = $response->getSubscriptionResults()[0];
        $this->assertTrue($result->getIsSuccess(), (string)$result->getErrorMessage());
    }

    public function testCheckSubscriptionForNotSubscribedTypesShouldFail(): void
    {
        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();
        $request->setNewsletterSubscriber($subscriber);

        $this->addTestType1ToSubscriptionRequest($request);

        $response = $this->newsletterFacade->checkSubscription($request);

        $result = $response->getSubscriptionResults()[0];
        $this->assertFalse($result->getIsSuccess());
    }

    public function testAssignCustomerToExistingSubscriber(): void
    {
        $newsletterSubscriberTransfer = $this->createSubscriber();

        $subscriberQuery = SpyNewsletterSubscriberQuery::create();
        $subscriberQuery->filterByEmail($newsletterSubscriberTransfer->getEmail());
        $subscriberQuery->filterBySubscriberKey($newsletterSubscriberTransfer->getSubscriberKey());
        $subscriberEntity = $subscriberQuery->findOneOrCreate();
        if ($subscriberEntity->getFkCustomer()) {
            $subscriberEntity->setFkCustomer(null);
        }
        $subscriberEntity->save();

        $customerQuery = SpyCustomerQuery::create();
        $customerQuery->filterByEmail($newsletterSubscriberTransfer->getEmail());
        $customerQuery->filterByCustomerReference('123');
        $customer = $customerQuery->findOneOrCreate();
        $customer->save();

        $newsletterSubscriberTransfer->setFkCustomer($customer->getIdCustomer());

        $result = $this->newsletterFacade->assignCustomerToExistingSubscriber($newsletterSubscriberTransfer);
        $this->assertTrue($result);

        $queryContainer = new NewsletterQueryContainer();
        $subscriberEntity = $queryContainer->querySubscriber()->filterByEmail($newsletterSubscriberTransfer->getEmail())->findOne();

        $this->assertSame($customer->getIdCustomer(), $subscriberEntity->getFkCustomer());
    }

    protected function setNewsletterFacade(): void
    {
        $this->newsletterFacade = new NewsletterFacade();
        $container = new Container();
        $newsletterDependencyProvider = new NewsletterDependencyProvider();
        $newsletterDependencyProvider->provideBusinessLayerDependencies($container);

        $mailFacadeMock = $this->getMockBuilder(NewsletterToMailInterface::class)->getMock();
        $container[NewsletterDependencyProvider::FACADE_MAIL] = $mailFacadeMock;

        $newsletterBusinessFactory = new NewsletterBusinessFactory();
        $newsletterBusinessFactory->setContainer($container);

        $this->newsletterFacade->setFactory($newsletterBusinessFactory);
    }

    protected function setTestNewsletterTypes(): void
    {
        $typeEntity = new SpyNewsletterType();
        $typeEntity->setName(static::TEST_TYPE1);
        $typeEntity->save();

        $typeEntity = new SpyNewsletterType();
        $typeEntity->setName(static::TEST_TYPE2);
        $typeEntity->save();
    }

    protected function createSubscriber(): NewsletterSubscriberTransfer
    {
        $subscriber = new NewsletterSubscriberTransfer();
        $subscriber->setEmail('example@spryker.com');
        $subscriber->setSubscriberKey('example@spryker.com');

        return $subscriber;
    }

    protected function createInvalidSubscriber(): NewsletterSubscriberTransfer
    {
        $subscriber = new NewsletterSubscriberTransfer();
        $subscriber->setEmail('invalid<>example@spryker.com');
        $subscriber->setSubscriberKey('invalid<>example@spryker.com');

        return $subscriber;
    }

    protected function addTestType1ToSubscriptionRequest(NewsletterSubscriptionRequestTransfer $request): void
    {
        $type1 = new NewsletterTypeTransfer();
        $type1->setName(static::TEST_TYPE1);

        $request->addSubscriptionType($type1);
    }

    protected function addTestType2ToSubscriptionRequest(NewsletterSubscriptionRequestTransfer $request): void
    {
        $type2 = new NewsletterTypeTransfer();
        $type2->setName(static::TEST_TYPE2);

        $request->addSubscriptionType($type2);
    }
}
