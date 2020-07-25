<?php

use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    static $testToken = 'xoxb-1262499667603-1260946658501-K9wzYUIVwaxUOuNa5oyA3xsj';

    public function testOk(): void
    {
        $result = messageToChannel(self::$testToken, 'general', 'test runner says hello');
        $result = json_decode($result);
        self::assertEquals(true, $result->{'ok'});
    }

    public function testEmptyMessage(): void
    {
        $result = messageToChannel(self::$testToken, 'general', '');
        $result = json_decode($result);
        self::assertEquals(false, $result->{'ok'});
    }

    public function testEmptyChannel(): void
    {
        $result = messageToChannel(self::$testToken, '', '');
        $result = json_decode($result);
        self::assertEquals(false, $result->{'ok'});
    }

    public function testBadUrl(): void
    {
        $result = messageToChannel('', 'general', 'test runner says hello');
        $result = json_decode($result);
        self::assertEquals(false, $result->{'ok'});
    }

    public function testEndToEnd(): void
    {
        $random = strval(rand());
        $result = messageToChannel(self::$testToken, 'general', $random);
        $result = json_decode($result);
        $channelID = $result->{'channel'};
        $messageTS = $result->{'ts'};

        $cURLConnection = curl_init('https://slack.com/api/conversations.history');
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS,
            http_build_query(
                array(
                    'token' => self::$testToken,
                    'channel' => $channelID,
                    'inclusive' => 'true',
                    'oldest' => $messageTS
                ))
        );
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded'));

        $result = curl_exec($cURLConnection);
        curl_close($cURLConnection);

        $result = json_decode($result);
        $testMessage = $result->{'messages'}[0]->{'text'};

        self::assertEquals($testMessage, $random);
    }
}
