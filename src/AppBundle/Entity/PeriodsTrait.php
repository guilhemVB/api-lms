<?php

namespace AppBundle\Entity;

trait PeriodsTrait
{

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $periodJanuary;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $periodFebruary;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $periodMarch;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $periodApril;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $periodMay;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $periodJune;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $periodJuly;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $periodAugust;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $periodSeptember;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $periodOctober;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $periodNovember;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $periodDecember;

    /**
     * @return int
     */
    public function getPeriodJanuary()
    {
        return $this->periodJanuary;
    }

    /**
     * @param int $periodJanuary
     */
    public function setPeriodJanuary($periodJanuary)
    {
        $this->periodJanuary = $periodJanuary;
    }

    /**
     * @return int
     */
    public function getPeriodDecember()
    {
        return $this->periodDecember;
    }

    /**
     * @param int $periodDecember
     */
    public function setPeriodDecember($periodDecember)
    {
        $this->periodDecember = $periodDecember;
    }

    /**
     * @return int
     */
    public function getPeriodNovember()
    {
        return $this->periodNovember;
    }

    /**
     * @param int $periodNovember
     */
    public function setPeriodNovember($periodNovember)
    {
        $this->periodNovember = $periodNovember;
    }

    /**
     * @return int
     */
    public function getPeriodOctober()
    {
        return $this->periodOctober;
    }

    /**
     * @param int $periodOctober
     */
    public function setPeriodOctober($periodOctober)
    {
        $this->periodOctober = $periodOctober;
    }

    /**
     * @return int
     */
    public function getPeriodSeptember()
    {
        return $this->periodSeptember;
    }

    /**
     * @param int $periodSeptember
     */
    public function setPeriodSeptember($periodSeptember)
    {
        $this->periodSeptember = $periodSeptember;
    }

    /**
     * @return int
     */
    public function getPeriodAugust()
    {
        return $this->periodAugust;
    }

    /**
     * @param int $periodAugust
     */
    public function setPeriodAugust($periodAugust)
    {
        $this->periodAugust = $periodAugust;
    }

    /**
     * @return int
     */
    public function getPeriodJuly()
    {
        return $this->periodJuly;
    }

    /**
     * @param int $periodJuly
     */
    public function setPeriodJuly($periodJuly)
    {
        $this->periodJuly = $periodJuly;
    }

    /**
     * @return int
     */
    public function getPeriodJune()
    {
        return $this->periodJune;
    }

    /**
     * @param int $periodJune
     */
    public function setPeriodJune($periodJune)
    {
        $this->periodJune = $periodJune;
    }

    /**
     * @return int
     */
    public function getPeriodMay()
    {
        return $this->periodMay;
    }

    /**
     * @param int $periodMay
     */
    public function setPeriodMay($periodMay)
    {
        $this->periodMay = $periodMay;
    }

    /**
     * @return int
     */
    public function getPeriodApril()
    {
        return $this->periodApril;
    }

    /**
     * @param int $periodApril
     */
    public function setPeriodApril($periodApril)
    {
        $this->periodApril = $periodApril;
    }

    /**
     * @return int
     */
    public function getPeriodMarch()
    {
        return $this->periodMarch;
    }

    /**
     * @param int $periodMarch
     */
    public function setPeriodMarch($periodMarch)
    {
        $this->periodMarch = $periodMarch;
    }

    /**
     * @return int
     */
    public function getPeriodFebruary()
    {
        return $this->periodFebruary;
    }

    /**
     * @param int $periodFebruary
     */
    public function setPeriodFebruary($periodFebruary)
    {
        $this->periodFebruary = $periodFebruary;
    }


}
