<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\UrlRewrite\Test\Constraint;

use Magento\UrlRewrite\Test\Page\Adminhtml\UrlRewriteIndex;
use Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertUrlRewriteSaveMessage
 * Assert that url rewrite success message is displayed
 */
class AssertUrlRewriteSaveMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    const SUCCESS_MESSAGE = 'The URL Rewrite has been saved.';

    /**
     * Assert that url rewrite success message is displayed
     *
     * @param UrlRewriteIndex $index
     * @return void
     */
    public function processAssert(UrlRewriteIndex $index)
    {
        $actualMessage = $index->getMessagesBlock()->getSuccessMessages();
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_MESSAGE,
            $actualMessage,
            'Wrong success message is displayed.'
            . "\nExpected: " . self::SUCCESS_MESSAGE
            . "\nActual: " . $actualMessage
        );
    }

    /**
     * Url rewrite success message is displayed
     *
     * @return string
     */
    public function toString()
    {
        return 'Url rewrite success message is displayed.';
    }
}
