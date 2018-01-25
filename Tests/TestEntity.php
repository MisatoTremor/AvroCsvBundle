<?php

namespace Avro\CsvBundle\Tests;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TestEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column
     */
    protected $field1;

    /**
     * @ORM\Column
     */
    protected $field2;

    /**
     * @ORM\OneToOne(targetEntity="Avro\CsvBundle\Tests\TestEntity2")
     */
    protected $assoc1;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getField1()
    {
        return $this->field1;
    }

    public function setField1($field1)
    {
        $this->field1 = $field1;
    }

    public function getField2()
    {
        return $this->field2;
    }

    public function setField2($field2)
    {
        $this->field2 = $field2;
    }

    public function getAssoc1()
    {
        return $this->assoc1;
    }

    public function setAssoc1($assoc1)
    {
        $this->assoc1 = $assoc1;
    }
}
