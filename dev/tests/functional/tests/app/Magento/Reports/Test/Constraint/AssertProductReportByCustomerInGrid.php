<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reports\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Customer\Test\Fixture\CustomerInjectable;
use Magento\Reports\Test\Page\Adminhtml\CustomerReportReview;
use Magento\Review\Test\Constraint\AssertProductReviewInGrid;
use Magento\Review\Test\Fixture\ReviewInjectable;
use Magento\Review\Test\Page\Adminhtml\ReviewIndex;
use Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertProductReportByCustomerInGrid
 * Check that Customer review is displayed in grid
 */
class AssertProductReportByCustomerInGrid extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that Customer review is displayed in grid
     *
     * @param ReviewIndex $reviewIndex
     * @param ReviewInjectable $review
     * @param AssertProductReviewInGrid $assertProductReviewInGrid
     * @param CustomerReportReview $customerReportReview
     * @param CustomerInjectable $customer
     * @param CatalogProductSimple $product
     * @param string $gridStatus
     * @return void
     */
    public function processAssert(
        ReviewIndex $reviewIndex,
        ReviewInjectable $review,
        AssertProductReviewInGrid $assertProductReviewInGrid,
        CustomerReportReview $customerReportReview,
        CustomerInjectable $customer,
        CatalogProductSimple $product = null,
        $gridStatus = ''
    ) {
        $filter = $assertProductReviewInGrid->prepareFilter($product, $review->getData(), $gridStatus);

        $customerReportReview->open();
        $customerReportReview->getGridBlock()->openReview($customer);
        $reviewIndex->getReviewGrid()->search($filter);
        unset($filter['visible_in']);
        \PHPUnit_Framework_Assert::assertTrue(
            $reviewIndex->getReviewGrid()->isRowVisible($filter, false),
            'Customer review is absent in Review grid.'
        );
    }

    /**
     * Text success exist review in grid on product reviews tab
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer review is present in grid on product reviews tab.';
    }
}
