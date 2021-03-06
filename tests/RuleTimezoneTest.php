<?php
namespace Inet\Transformation\Tests;

use Inet\Transformation\Transform as T;

class RuleTimezoneTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Inet\Transformation\Exception\TransformationException
     * @expectedExceptionMessageRegExp |Rule Timezone Expects 2 or 3 arguments|
     */
    public function testTimezoneNoParameters()
    {
        T::Timezone()->transform('2015-01-01');
    }

    /**
     * @expectedException Inet\Transformation\Exception\TransformationException
     * @expectedExceptionMessageRegExp |Rule Timezone Expects 2 or 3 arguments|
     */
    public function testTimezoneOneParameters()
    {
        T::Timezone('Y-m-d')->transform('2015-01-01');
    }

    /**
     * @expectedException Inet\Transformation\Exception\NotTransformableException
     * @expectedExceptionMessageRegExp |Only strings, int, float, bool and array are transformable|
     */
    public function testTimezoneTwoParametersTimezoneNull()
    {
        T::Timezone('Y-m-d', 'Pacific/Nauru')->transform(null);
    }

    /**
     * @expectedException Inet\Transformation\Exception\TransformationException
     * @expectedExceptionMessageRegExp |Input \(foo\) or format \(Y-m-d\) is not valid|
     */
    public function testTimezoneWrongDate()
    {
        T::Timezone('Y-m-d', 'Pacific/Nauru')->transform('foo');
    }

    /**
     * @expectedException Inet\Transformation\Exception\TransformationException
     * @expectedExceptionMessageRegExp |Timezone 'foo' is not valid|
     */
    public function testTimezoneWrongTimezone()
    {
        T::Timezone('Y-m-d', 'foo')->transform('2015-01-01');
    }

    public function testTimezoneRightParameters()
    {
        $dateStr = '2014-12-31 15:00:00';
        $output = T::Timezone('Y-m-d H:i:s', date_default_timezone_get())->transform($dateStr);
        $this->assertEquals($output, $dateStr);
    }

    public function testTimezoneRightParametersWithOriginTimeZone()
    {
        $output = T::Timezone('Y-m-d H:i:s', 'Pacific/Nauru', 'Europe/Paris')->transform('2014-12-31 15:00:00');
        $this->assertEquals($output, '2015-01-01 02:00:00');
    }

    public function testTimezoneRightParametersDoubleTransformation()
    {
        $date = '2014-12-31 15:00:00';
        $output = T::Timezone('Y-m-d H:i:s', 'Pacific/Nauru', 'Asia/Calcutta')
                   ->Timezone('Y-m-d H:i:s', 'Asia/Calcutta', 'Pacific/Nauru')
                   ->transform($date);

        $this->assertEquals($output, $date);
    }
}
