<?php

/**
 * Created by PhpStorm.
 * User: fsalles
 * Date: 28/06/17
 * Time: 14:31
 */
namespace Trivia;

use PHPUnit\Framework\TestCase;

class GoldenMasterTest extends TestCase
{
    function testGenerateOutput() {
        $times = 20000;
        $this->generateMany($times, '/tmp/gm.txt');
        $this->generateMany($times, '/tmp/gm2.txt');
        $file_content_gm = file_get_contents('/tmp/gm.txt');
        $file_content_gm2 = file_get_contents('/tmp/gm2.txt');
        $this->assertEquals($file_content_gm, $file_content_gm2);
    }

    private function generateMany($times, $fileName) {
        $first = true;
        while ($times) {
            if ($first) {
                file_put_contents($fileName, $this->generateOutput($times));
                $first = false;
            } else {
                file_put_contents($fileName, $this->generateOutput($times), FILE_APPEND);
            }
            $times--;
        }
    }

    private function generateOutput($seed) {
        ob_start();
        srand($seed);
        require __DIR__ . '/../src/GameRunner.php';
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}
