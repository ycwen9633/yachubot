<?php

/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require_once('./LINEBotTiny.php');



$channelAccessToken = getenv('LINE_CHANNEL_ACCESSTOKEN');
$channelSecret = getenv('LINE_CHANNEL_SECRET');

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            $test_result = 'trueee';
            

            $json = file_get_contents('https://spreadsheets.google.com/feeds/list/1tBG01g4WIaV_tgPmJ0cmh80y3pkC4E7QJdBsWTpQ-c4/od6/public/values?alt=json');
            $data = json_decode($json, true);
            $result = array();

            foreach ($data['feed']['entry'] as $item) {
                $keywords = explode(',', $item['gsx$keyword']['$t']);
                foreach ($keywords as $keyword) {
                    $test_result = $keyword.$message['text'];
                    // if (mb_strpos($message['text'], $keyword) !== false) {
                    //     // $candidate = array(
                    //     //     'thumbnailImageUrl' => $item['gsx$photourl']['$t'],
                    //     //     'title' => $item['gsx$title']['$t'],
                    //     //     'text' => $item['gsx$title']['$t'],
                    //     //     'actions' => array(
                    //     //         array(
                    //     //             'type' => 'uri',
                    //     //             'label' => '查看詳情',
                    //     //             'uri' => $item['gsx$url']['$t'],
                    //     //             ),
                    //     //         ),
                    //     //     );
                    //     $test_result = 'in';
                    //     // array_push($result, $candidate);
                    // }
                }
                // $test_result = $keywords[0];
            }

            switch ($message['type']) {
                case 'text':
                    $m_message = $message['text'];
                    if($m_message!="")
                    {
                        $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => $m_message.$test_result
                            )
                        )
                        ));
                    }
                    break;
                
            }

            // switch ($message['type']) {
            //     case 'text':
            //         $client->replyMessage(array(
            //             'replyToken' => $event['replyToken'],
            //             'messages' => array(
            //                 array(
            //                     'type' => 'text',
            //                     'text' => $message['text'].'等等我喔...',
            //                 ),
            //                 array(
            //                     'type' => 'template',
            //                     'altText' => '找到了！資料如下：',
            //                     'template' => array(
            //                         'type' => 'carousel',
            //                         'columns' => $result,
            //                     ),
            //                 ),
            //                 array(
            //                     'type' => 'text',
            //                     'text' => '慢慢欣賞:)',
            //                 ),
            //                 array(
            //                     'type' => 'sticker',
            //                     'packageId' => '1',
            //                     'stickerId' => '2',
            //                 ),
            //             ),
            //         ));
            //         break;
            //     default:
            //         error_log("Unsupporeted message type: " . $message['type']);
            //         break;
            // }
            break;
        default:
            error_log("Unsupporeted event type: " . $event['type']);
            break;
    }
};
