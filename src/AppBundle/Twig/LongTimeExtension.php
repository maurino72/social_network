<?php
/**
 * Created by PhpStorm.
 * User: bmaurino
 * Date: 10/31/17
 * Time: 18:37
 */

namespace AppBundle\Twig;


use Symfony\Component\Validator\Constraints\DateTime;

class LongTimeExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('long_time', [$this, 'longTimeFilter'])
        ];
    }

    public function longTimeFilter($date)
    {
        if ($date == null) {
            return 'Date is null';
        }

        $startDate = $date;
        $sinceStartDate = $startDate->diff(new \DateTime(date('Y-m-d') . ' ' . date('H:i:s')));

        if ($sinceStartDate->y == 0) {
            if ($sinceStartDate->m == 0) {
                if ($sinceStartDate->d == 0) {
                    if ($sinceStartDate->h == 0) {
                        if ($sinceStartDate->i == 0) {
                            if ($sinceStartDate->s == 0) {
                                $result = $sinceStartDate->s . ' ' . 'seconds';
                            } else {
                                if ($sinceStartDate->s == 1) {
                                    $result = $sinceStartDate->s . ' ' . 'second';
                                } else {
                                    $result = $sinceStartDate->s . ' ' . 'seconds';
                                }
                            }
                        } else {
                            if ($sinceStartDate->i == 1) {
                                $result = $sinceStartDate->i . ' ' . 'minute';
                            } else {
                                $result = $sinceStartDate->i . ' ' . 'minutes';
                            }
                        }
                    } else {
                        if ($sinceStartDate->h == 1) {
                            $result = $sinceStartDate->h . ' ' . 'hour';
                        } else {
                            $result = $sinceStartDate->h . ' ' . 'hours';
                        }
                    }
                } else {
                    if ($sinceStartDate->d == 1) {
                        $result = $sinceStartDate->d . ' ' . 'day';
                    } else {
                        $result = $sinceStartDate->d . ' ' . 'days';
                    }
                }
            } else {
                if ($sinceStartDate->m == 1) {
                    $result = $sinceStartDate->m . ' ' . 'month';
                } else {
                    $result = $sinceStartDate->m . ' ' . 'months';
                }
            }
        } else {
            if ($sinceStartDate->y == 1) {
                $result = $sinceStartDate->y . ' ' . 'year';
            } else {
                $result = $sinceStartDate->y . ' ' . 'years';
            }
        }

        return 'Published' . ' ' . $result . ' ' . 'ago';
    }

    public function getName()
    {
        return 'long_time_extension';
    }
}