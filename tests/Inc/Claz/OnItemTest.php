<?php
/**
 * @name OnItemTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 */

namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class OnItemTest
 * @package Inc\Claz
 */
class OnItemTest extends TestCase
{

    public function testOnItemClass()
    {
        try {
            $oi = new OnItem(true, 'db_field', "=", 10, false, 'OR');
            $cnt = 0;
            $keyPairs = [];
            $stmt = $oi->build($cnt, $keyPairs);
            static::assertEquals("(`db_field` = :db_field_000  OR ", $stmt, 'OnItem build test failed');
            static::assertEquals(1, $cnt, 'OnItem cnt test failed');
            static::assertEquals(10, $keyPairs[':db_field_000'], 'OnItem keyPairs test failed');
        } catch (PdoDbException $pde) {
            static::assertTrue(false, "testOnItemCLass() Unexpected error thrown. Error: {$pde->getMessage()}");
        }
    }
}
