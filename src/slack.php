<?php
/**
 * This function send a text message to a slack channel
 * @param string $bearerToken
 * @param string $channel
 * @param string $message
 * @return bool|string
 */
function messageToChannel(string $bearerToken, string $channel, string $message)
{
    $url = 'https://slack.com/api/chat.postMessage';
    $form = array(
        'token' => $bearerToken,
        'channel' => $channel,
        'text' => $message
    );

    $cURLConnection = curl_init($url);
    curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, http_build_query($form));
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded'));

    $response = curl_exec($cURLConnection);
    curl_close($cURLConnection);
    return $response;
}

