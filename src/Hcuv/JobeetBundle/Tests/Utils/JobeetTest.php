<?php
/**
 * Created by JetBrains PhpStorm.
 * User: imerayo
 * Date: 8/9/12
 * Time: 10:54 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Hcuv\JobeetBundle\Tests\Utils;

use Hcuv\JobeetBundle\Utils\Jobeet as Jobeet;

class JobeetTest extends \PHPUnit_Framework_Testcase
{
    /**
     * @test
     */
    public function twoWordsWithSpace()
    {
        $actual = Jobeet::slugify('Sensio Labs');
        $expected = 'sensio-labs';
        $this->assertEquals($expected, $actual);
    }
    /**
     * @test
     */
    public function twoWordsWithSpaceAndComma()
    {
        $actual = Jobeet::slugify('Paris, France');
        $expected = 'paris-france';
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function someExamplesToSlugify()
    {
        $this->assertEquals('sensio', Jobeet::slugify('Sensio'));
        $this->assertEquals('sensio-labs', Jobeet::slugify('sensio labs'));
        $this->assertEquals('sensio-labs', Jobeet::slugify('sensio   labs'));
        $this->assertEquals('paris-france', Jobeet::slugify('paris,france'));
        $this->assertEquals('sensio', Jobeet::slugify('  sensio'));
        $this->assertEquals('sensio', Jobeet::slugify('sensio  '));
        $this->assertEquals('n-a', Jobeet::slugify(''));
    }
}
