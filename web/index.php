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
                                    'text' => '我最大的興趣是打籃球，高中時擔任籃球社社長，大學時擔任政大資管系女籃隊長，對籃球熱愛的程度不亞於男生，除了系上練球時間有空閒也會自主訓練，對於自己熱愛的事物非常執著！歡迎找我單挑（？'
                                    )
                                )
                            ));
                        } elseif (strpos($message['text'], '個性') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => '我是一個開朗外向的人，喜歡到處結交朋友，也喜歡和朋友一起說垃圾話，很好相處，相處過都說讚，同時也是一個閒不下來的人，會一直找事請給自己做，希望能在line實習讓自己生活更豐富<3'
                                    )
                                )
                            ));
                        } elseif (strpos($message['text'], '外表') !== false || strpos($message['text'], '外貌') !== false || strpos($message['text'], '外觀') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => '擁有如熊大一般的膚色...不是很高的身高'
                                    )
                                )
                            ));
                        } elseif (strpos($message['text'], '哲煜科技') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                    array(
                                        'type' => 'template', // 訊息類型 (模板)
                                        'altText' => '在哲煜科技的實習經歷', // 替代文字
                                        'template' => array(
                                            'type' => 'buttons', // 類型 (按鈕)
                                            'text' => '公司主要以接案為主，開發過各式不同形象官網前後台以及TMS運輸管理系統，語言則以PHP為主，HTML、CSS、Javascript、Vue.js為輔，除了寫程式之外還需與專案經理溝通理解客戶需求。可以點選下方文字看我其他經歷喔', // 文字
                                            'actions' => array(
                                                array(
                                                    'type' => 'message',
                                                    'label' => '在富邦人壽核心系統部門擔任實習生', 
                                                    'text' => '在富邦人壽核心系統部門擔任實習生'
                                                ),
                                                array(
                                                    'type' => 'message', 
                                                    'label' => '在久元電子顧機台', 
                                                    'text' => '在久元電子顧機台'
                                                ),
                                                array(
                                                    'type' => 'message', 
                                                    'label' => '在大馬小吃擔任櫃台', 
                                                    'text' => '在大馬小吃擔任櫃台'
                                                )
                                            )
                                        )
                                    )
                                )
                            ));
                        } elseif (strpos($message['text'], '富邦人壽') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                    array(
                                        'type' => 'template', // 訊息類型 (模板)
                                        'altText' => '在富邦人壽的實習經歷', // 替代文字
                                        'template' => array(
                                            'type' => 'buttons', // 類型 (按鈕)
                                            'text' => '我在核心系統部門下的保費管理科擔任實習生，而我們科內主要負責的是保費支付的系統，在金融科技創新的席捲下，我很幸運的參與到保險業與Apple Pay支付接軌的專案，利用node.js串接Apple Pay API，幫忙測試保費由行動支付的可行性，與收單行溝通，瞭解行動支付的流程以及安全性，除了師傅指派的這項專案外，另與其他實習生一同發想創新提案以及舉辦UX桌遊競賽，在實習期間內自行報名了壽險考照班，對於保險的知識大大提升，除此之外亦了解到金融科技在實際金融業的發展程度。可以點選下方文字看我其他經歷喔', // 文字
                                            'actions' => array(
                                                array(
                                                    'type' => 'message',
                                                    'label' => '在哲煜科技擔任實習工程師', 
                                                    'text' => '在哲煜科技擔任實習工程師'
                                                ),
                                                array(
                                                    'type' => 'message', 
                                                    'label' => '在久元電子顧機台', 
                                                    'text' => '在久元電子顧機台'
                                                ),
                                                array(
                                                    'type' => 'message', 
                                                    'label' => '在大馬小吃擔任櫃台', 
                                                    'text' => '在大馬小吃擔任櫃台'
                                                )
                                            )
                                        )
                                    )
                                )
                            ));
                        } elseif (strpos($message['text'], '久元電子') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                    array(
                                        'type' => 'template', // 訊息類型 (模板)
                                        'altText' => '在哲煜科技的實習經歷', // 替代文字
                                        'template' => array(
                                            'type' => 'buttons', // 類型 (按鈕)
                                            'text' => '在挑揀晶圓的無塵室中顧機台，處理機台發生的問題以及上下晶圓片，許多專業術語以及晶圓的型號等皆是全新的事物，需要非常專注的在短時間內記熟不同的機台對應的晶圓片以及藍膜，許多同批進去的工讀生幾天就辭職了，但對於喜歡嘗試新事物的我來說是一個全新的挑戰，而在經過努力學習後，我熟悉了挑檢晶圓作業的流程甚至對接續的封裝測試過程也有一定的瞭解，這份打工經驗驗證了我不願放棄、積極學習的態度，也讓自己更相信對於任何新事物都要保持著高度的好奇心。可以點選下方文字看我其他經歷喔', // 文字
                                            'actions' => array(
                                                array(
                                                    'type' => 'message', 
                                                    'label' => '在哲煜科技擔任實習工程師', 
                                                    'text' => '在哲煜科技擔任實習工程師'
                                                ),
                                                array(
                                                    'type' => 'message',
                                                    'label' => '在富邦人壽核心系統部門擔任實習生', 
                                                    'text' => '在富邦人壽核心系統部門擔任實習生'
                                                ),
                                                array(
                                                    'type' => 'message', 
                                                    'label' => '在大馬小吃擔任櫃台', 
                                                    'text' => '在大馬小吃擔任櫃台'
                                                )
                                            )
                                        )
                                    )
                                )
                            ));
                        } elseif (strpos($message['text'], '大馬小吃') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                    array(
                                        'type' => 'template', // 訊息類型 (模板)
                                        'altText' => '在哲煜科技的實習經歷', // 替代文字
                                        'template' => array(
                                            'type' => 'buttons', // 類型 (按鈕)
                                            'text' => '鄰近學校的餐廳，在尖峰時段非常忙碌，培養了我良好的危機處理以及應變的能力，期間內我不斷地提升自己的工作效能，讓出餐的流程更順暢、記得熟客點的品項，處事圓融的我也深受客人以及老闆的喜愛，面對不同的客人我能提供最貼合他們需求的服務。可以點選下方文字看我其他經歷喔', // 文字
                                            'actions' => array(
                                                array(
                                                    'type' => 'message', 
                                                    'label' => '在哲煜科技擔任實習工程師', 
                                                    'text' => '在哲煜科技擔任實習工程師'
                                                ),
                                                array(
                                                    'type' => 'message',
                                                    'label' => '在富邦人壽核心系統部門擔任實習生', 
                                                    'text' => '在富邦人壽核心系統部門擔任實習生'
                                                ),
                                                array(
                                                    'type' => 'message', 
                                                    'label' => '在久元電子顧機台', 
                                                    'text' => '在久元電子顧機台'
                                                ),
                                            )
                                        )
                                    )
                                )
                            ));
                        } elseif (strpos($message['text'], '實習經歷') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                    array(
                                        'type' => 'template', // 訊息類型 (模板)
                                        'altText' => '你想知道哪段實習經歷？', // 替代文字
                                        'template' => array(
                                            'type' => 'buttons', // 類型 (按鈕)
                                            'title' => '你想知道哪段實習經歷？', // 標題 <不一定需要>
                                            'text' => '每一段工作經歷我都很珍惜投入，不論職務是甚麼都全力以赴，不同技能或是待人處事的態度都獲益良多', // 文字
                                            'actions' => array(
                                                array(
                                                    'type' => 'message',
                                                    'label' => '在哲煜科技擔任實習工程師',
                                                    'text' => '在哲煜科技擔任實習工程師' 
                                                ),
                                                array(
                                                    'type' => 'message',
                                                    'label' => '在富邦人壽核心系統部門擔任實習生', 
                                                    'text' => '在富邦人壽核心系統部門擔任實習生'
                                                ),
                                                array(
                                                    'type' => 'message', 
                                                    'label' => '在久元電子顧機台', 
                                                    'text' => '在久元電子顧機台'
                                                ),
                                                array(
                                                    'type' => 'message', 
                                                    'label' => '在大馬小吃擔任櫃台', 
                                                    'text' => '在大馬小吃擔任櫃台'
                                                )
                                            )
                                        )
                                    )
                                )
                            ));
                        } elseif (strpos($message['text'], '?') !== false || strpos($message['text'], '？') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                    array(
                                        'type' => 'template', // 訊息類型 (模板)
                                        'altText' => '快速問我問題吧！', // 替代文字
                                        'template' => array(
                                            'type' => 'buttons', // 類型 (按鈕)
                                            'thumbnailImageUrl' => 'https://www.hahatai.com/sites/default/files/u1031/8_2.jpg.pagespeed.ce.9yo2iS_Cxl.jpg',
                                            'imageAspectRatio' => 'square',
                                            'title' => '按下方按鈕可以快速問我問題:)', // 標題 <不一定需要>
                                            'text' => '第一次提問會等待較久，先不要離開QQ，更多操作說明在記事本哦', // 文字
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
                                                    'label' => '我想要知道你的實習經歷', 
                                                    'text' => '我想要知道你的實習經歷'
                                                ),
                                                array(
                                                    'type' => 'message', 
                                                    'label' => '你為什麼想來Line實習', 
                                                    'text' => '你為什麼想來Line實習'
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
