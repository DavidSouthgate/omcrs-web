<?php
use PHPUnit\Framework\TestCase;

/**
 * @covers BreadcrumbTest
 */
final class BreadcrumbTest extends TestCase
{

    public function testBreadcrumb(){
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem("title", "baseUrl");

        $this->assertEquals(
            $breadcrumbs->count(),
            1
        );

        $this->assertEquals(
            $breadcrumbs->getItems(),
            array(new BreadcrumbItem('title', 'baseUrl'))
        );
    }
}
