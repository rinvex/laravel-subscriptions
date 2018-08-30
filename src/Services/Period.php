<?php

declare(strict_types=1);

namespace Rinvex\Subscriptions\Services;

use Carbon\Carbon;

class Period
{
    /**
     * Starting date of the period.
     *
     * @var string
     */
    protected $start;

    /**
     * Ending date of the period.
     *
     * @var string
     */
    protected $end;

    /**
     * Interval.
     *
     * @var string
     */
    protected $interval;

    /**
     * Interval count.
     *
     * @var int
     */
    protected $period = 1;

    /**
     * Create a new Period instance.
     *
     * @param string $interval
     * @param int    $count
     * @param string $start
     *
     * @return void
     */
    public function __construct($interval = 'month', $count = 1, $start = '')
    {
        if (empty($start)) {
            $this->start = now();
        } elseif (! $start instanceof Carbon) {
            $this->start = new Carbon($start);
        } else {
            $this->start = $start;
        }

        $this->interval = in_array($interval, ['day', 'week', 'month', 'year']) ? $interval : 'month';

        if ($count > 0) {
            $this->period = $count;
        }

        $method = 'add'.ucfirst($this->interval).'s';
        $start = clone $this->start;
        $this->end = $start->$method($this->period);
    }

    /**
     * Get start date.
     *
     * @return \Carbon\Carbon
     */
    public function getStartDate(): Carbon
    {
        return $this->start;
    }

    /**
     * Get end date.
     *
     * @return \Carbon\Carbon
     */
    public function getEndDate(): Carbon
    {
        return $this->end;
    }

    /**
     * Get period interval.
     *
     * @return string
     */
    public function getInterval(): string
    {
        return $this->interval;
    }

    /**
     * Get period interval count.
     *
     * @return int
     */
    public function getIntervalCount(): int
    {
        return $this->period;
    }
}
