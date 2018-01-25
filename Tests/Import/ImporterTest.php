<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avro\CsvBundle\Tests\Import;

use Avro\CaseBundle\Util\CaseConverter;
use Avro\CsvBundle\Import\Importer;
use Avro\CsvBundle\Tests\TestEntity2;
use Avro\CsvBundle\Util\Reader;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Test importer class.
 */
class ImporterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string[]
     */
    protected $fields;
    /**
     * @var Importer
     */
    protected $importer;
    /**
     * @var ObjectManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManager;

    /**
     * Setup test class.
     */
    public function setUp()
    {
        $fields = ['id', 'field1', 'field2'];
        $assocs = ['assoc1'];
        $this->fields = array_merge($fields, $assocs);
        $caseConverter = new CaseConverter();
        $reader = new Reader();
        $metadata = $this->getMockForAbstractClass('Doctrine\ORM\Mapping\ClassMetadataInfo', [], '', false, true, true, ['hasField', 'hasAssociation', 'getAssociationMapping']);
        $metadata->expects($this->any())
            ->method('hasField')
            ->will($this->returnCallback(function ($value) use ($fields) {
                return in_array($value, $fields);
            }));
        $metadata->expects($this->any())
            ->method('hasAssociation')
            ->will($this->returnCallback(function ($value) use ($assocs) {
                return in_array($value, $assocs);
            }));
        $metadata->expects($this->any())
            ->method('getAssociationMapping')
            ->with('assoc1')
            ->will($this->returnValue([
                'targetEntity' => 'Avro\CsvBundle\Tests\TestEntity2',
                'type' => \Doctrine\ORM\Mapping\ClassMetadataInfo::ONE_TO_ONE
            ]));
        $this->objectManager = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->objectManager->expects($this->any())
            ->method('getClassMetadata')
            ->will($this->returnValue($metadata));
        $dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $dispatcher->expects($this->any())
            ->method('dispatch')
            ->will($this->returnValue('true'));

        $this->importer = new Importer($reader, $dispatcher, $caseConverter, $this->objectManager, 5);

        $this->importer->init(__DIR__.'/../import.csv', 'Avro\CsvBundle\Tests\TestEntity', ',', 'title');
    }

    /**
     * Test import.
     */
    public function testImport()
    {
        $testEntity2 = new \Avro\CsvBundle\Tests\TestEntity2();
        $objectRepository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $objectRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => '1'])
            ->will($this->returnValue($testEntity2));
        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->with(TestEntity2::class)
            ->will($this->returnValue($objectRepository));
        $this->assertEquals(
            true,
            $this->importer->import($this->fields)
        );

        $this->assertEquals(
            3,
            $this->importer->getImportCount()
        );
    }
}
