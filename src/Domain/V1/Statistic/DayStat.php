<?php

namespace MyTarget\Domain\V1\Statistic;

use MyTarget\Mapper\Annotation\Field;

class DayStat extends DatedStat
{
    /**
     * @var \DateTime
     * @Field(type="DateTime<d.m.Y|>")
     */
    private $date;

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
