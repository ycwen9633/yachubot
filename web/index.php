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
            $test_result = 'ini';
            

            $json = file_get_contents('https://spreadsheets.google.com/feeds/list/1tBG01g4WIaV_tgPmJ0cmh80y3pkC4E7QJdBsWTpQ-c4/od6/public/values?alt=json');
            $data = json_decode($json, true);
            $result = array();

            foreach ($data['feed']['entry'] as $item) {
                $keywords = explode(',', $item['gsx$keyword']['$t']);
                foreach ($keywords as $keyword) {
                    if (strpos(strtolower($message['text']), $keyword) !== false) {
                        $candidate = array(
                            'thumbnailImageUrl' => $item['gsx$photourl']['$t'],
                            'title' => $item['gsx$title']['$t'],
                            'text' => $item['gsx$description']['$t'],
                            'actions' => array(
                                array(
                                    'type' => 'uri',
                                    'label' => '查看詳情',
                                    'uri' => $item['gsx$url']['$t'],
                                    ),
                                array(
                                    'type' => 'message', // 類型 (訊息)
                                    'label' => '作品', // 標籤 2
                                    'text' => '作品' // 用戶發送文字
                                    ),

                                ),
                            );
                        array_push($result, $candidate);
                    }
                }
            }
            switch ($message['type']) {
                case 'text':
                    if ($result) {
                        $client->replyMessage(array(
                            'replyToken' => $event['replyToken'],
                            'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => $message['text'].'等等我喔...'.$test_result,
                                ),
                                array(
                                    'type' => 'template',
                                    'altText' => '找到了！資料如下：',
                                    'template' => array(
                                        'type' => 'carousel',
                                        'columns' => $result,
                                    ),
                                ),
                                array(
                                    'type' => 'text',
                                    'text' => '慢慢欣賞:)',
                                ),
                                array(
                                    'type' => 'sticker',
                                    'packageId' => '1',
                                    'stickerId' => '2',
                                ),
                            ),
                        ));
                    } else {
                        if (strpos($message['text'], '興趣') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => '打籃球打籃球打籃球'
                                    )
                                )
                            ));
                        } elseif (strpos($message['text'], '?') !== false) {
                            // $client->replyMessage(array(
                            //     'replyToken' => $event['replyToken'],
                            //     'messages' => array(
                            //     array(
                            //         'type' => 'text',
                            //         'text' => '???'
                            //         )
                            //     )
                            // ));
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                    array(
                                        'type' => 'template', // 訊息類型 (模板)
                                        'altText' => 'Example buttons template', // 替代文字
                                        'template' => array(
                                            'type' => 'buttons', // 類型 (按鈕)
                                            'thumbnailImageUrl' => 'https://www.hahatai.com/sites/default/files/u1031/8_2.jpg.pagespeed.ce.9yo2iS_Cxl.jpg',
                                            'imageAspectRatio' => 'square',
                                            'title' => '按下方按鈕可以快速問我問題:)', // 標題 <不一定需要>
                                            'text' => '更多操作說明在記事本哦', // 文字
                                            'actions' => array(
                                                array(
                                                    'type' => 'message',
                                                    'label' => '我想要更了解你',
                                                    'text' => '我想要更了解你' 
                                                ),
                                                array(
                                                    'type' => 'message',
                                                    'label' => '我想要觀看你的作品', 
                                                    'text' => '我想要觀看你的作品'
                                                ),
                                                array(
                                                    'type' => 'message', 
                                                    'label' => '我想要知道你的興趣', 
                                                    'text' => '我想要知道你的興趣' 
                                                )
                                            )
                                        )
                                    )
                                )
                            ));
                        }
                    }
                    
                    break;
                default:
                    error_log("Unsupporeted message type: " . $message['type']);
                    break;
            }
            break;
        default:
            error_log("Unsupporeted event type: " . $event['type']);
            break;
    }
};
