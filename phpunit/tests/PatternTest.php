<?php
namespace TimTegeler\Routerunner;

class PatternTest extends \PHPUnit_Framework_TestCase
{

    public function testParse()
    {

//        for ($i = 0; $i < 100; $i++) {
//            $file = __DIR__ . "/../assets/routes";
//
//            $starttime = microtime();
//            clearstatcache(True, $file);
//            filemtime($file);
//            $endtime = microtime();
//            $parsingTimeWithoutCaching = $endtime - $starttime;
//
//            $starttime = microtime();
//            $content = file_get_contents($file);
//            md5($content);
//            $endtime = microtime();
//            $parsingTimeWithCacheWrite = $endtime - $starttime;
//            echo $parsingTimeWithoutCaching . " " . $parsingTimeWithCacheWrite . "\r\n";
//            $this->assertGreaterThan($parsingTimeWithoutCaching, $parsingTimeWithCacheWrite);
//        }

        $this->assertEquals('/^\/(\w+)\/(\d+|\d+\.\d+)$/', Pattern::buildUri('/[string]/[numeric]'));
        $this->assertEquals('/^GET$/', Pattern::buildHttpMethod('GET'));
        $this->assertEquals('/^POST$/', Pattern::buildHttpMethod('POST'));
        $this->assertEquals('/^GET|POST$/', Pattern::buildHttpMethod('*'));
    }

}
