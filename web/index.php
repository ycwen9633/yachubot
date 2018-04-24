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
                                    'text' => '我是一個開朗外向的人，喜歡到處結交朋友，也喜歡和朋友一起說垃圾話，很好相處，相處過都說讚，同時也是一個閒不下來的人，會一直找事請給自己做，希望能在line實習讓自己生活更豐富'
                                    )
                                )
                            ));
                        } elseif (strpos($message['text'], '外表') !== false || strpos($message['text'], '外貌') !== false || strpos($message['text'], '長怎樣') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => '擁有如熊大一般的膚色...不是很高的身高，剩下的當面看'
                                    )
                                )
                            ));
                        } elseif (strpos($message['text'], '哲煜科技') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => '公司主要以接案為主，開發過各式不同形象官網前後台以及TMS運輸管理系統，語言則以PHP為主，HTML、CSS、Javascript、Vue.js為輔，除了寫程式之外還需與專案經理溝通理解客戶需求。'
                                    )
                                )
                            ));
                        } elseif (strpos($message['text'], '富邦人壽') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => '我在核心系統部門下的保費管理科擔任實習生，而我們科內主要負責的是保費支付的系統，在金融科技創新的席捲下，我很幸運的參與到保險業與Apple Pay支付接軌的專案，利用node.js串接Apple Pay API，幫忙測試保費由行動支付的可行性，與收單行溝通，瞭解行動支付的流程以及安全性，除了師傅指派的這項專案外，另與其他實習生一同發想創新提案以及舉辦UX桌遊競賽，在實習期間內自行報名了壽險考照班，對於保險的知識大大提升，除此之外亦了解到金融科技在實際金融業的發展程度。'
                                    )
                                )
                            ));
                        } elseif (strpos($message['text'], '久元電子') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => '在挑揀晶圓的無塵室中顧機台，處理機台發生的問題以及上下晶圓片，許多專業術語以及晶圓的型號等皆是全新的事物，需要非常專注的在短時間內記熟不同的機台對應的晶圓片以及藍膜，許多同批進去的工讀生幾天就辭職了，但對於喜歡嘗試新事物的我來說是一個全新的挑戰，而在經過努力學習後，我熟悉了挑檢晶圓作業的流程甚至對接續的封裝測試過程也有一定的瞭解，這份打工經驗驗證了我不願放棄、積極學習的態度，也讓自己更相信對於任何新事物都要保持著高度的好奇心。'
                                    )
                                )
                            ));
                        } elseif (strpos($message['text'], '大馬小吃') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => '鄰近學校的餐廳，在尖峰時段非常忙碌，培養了我良好的危機處理以及應變的能力，期間內我不斷地提升自己的工作效能，讓出餐的流程更順暢、記得熟客點的品項，處事圓融的我也深受客人以及老闆的喜愛，面對不同的客人我能提供最貼合他們需求的服務。'
                                    )
                                )
                            ));
                        } elseif (strpos($message['text'], '比賽經歷') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => '畢業專題製作＿第三名（2016/09~2017/06）'
                                    ),
                                array(
                                    'type' => 'text',
                                    'text' => '政大統計資料競賽＿進入決於賽（2017/02~2017/05）'
                                    ),
                                array(
                                    'type' => 'text',
                                    'text' => '華南Fintech創新競賽＿獲得與繁星科技合作機會（2016/10~2016/11）'
                                    ),
                                array(
                                    'type' => 'text',
                                    'text' => 'SAS玉山數據科學家競賽＿進入複賽（進行中）'
                                    ),
                                array(
                                    'type' => 'text',
                                    'text' => '政大資管系女籃隊長（2015/09~2016/06）'
                                    ),
                                ),
                                
                            ));
                        } elseif (strpos($message['text'], '能力') !== false ||strpos($message['text'], '技能') !== false  ) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => '前端：HTML,CSS,Javascript,Vue.js'
                                    ),
                                array(
                                    'type' => 'text',
                                    'text' => '後端：PHP,Java,R'
                                    ),
                                array(
                                    'type' => 'text',
                                    'text' => '使用過Git,Bitbucket,Github'
                                    ),
                                ),array(
                                    'type' => 'text',
                                    'text' => '語言能力：TOEIC 930'
                                    ),
                                ),array(
                                    'type' => 'text',
                                    'text' => '其他：SAS、PS、Flash'
                                    ),
                                ),
                                
                            ));
                        }  elseif (strtolower(strpos($message['text']), '來line') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => 'Line是目前台灣人主要溝通的APP，而現在Line除了能通訊外還有各種服務，對於Line不斷求創新、精進自己、推出更貼切使用者需求服務的精神，和我自己的理念相當，因此我很希望能和一群擁有相同抱負的同事和環境裡，發揮自己的價值！'
                                    )
                                
                            ));
                        }  elseif (strtolower(strpos($message['text']), '適合') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => '我喜歡嘗試以及挑戰自己，因此上大學以來我參加了許多活動、社團、比賽，即便生活忙碌我仍然享受如此的過程，在不同的經歷中我各方面都有成長，實習帶給我的除了在技術上的成長外，更令我滿足的是自己尋找答案想出解法的過程，多元的生活亦讓我喜歡合作、以及與他人溝通，相信自己能在實習中貢獻一己之力。作品的部分由於畢業專案（活動交友 App）、大學作品(大馬小吃資料庫系統、電商平台產銷資訊系統)並無上線、MOMO TMS系統為實習公司財產因此無法展示。'
                                    )
                                
                            ));
                        } elseif (strpos($message['text'], '個人特質') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                    array(
                                        'type' => 'template', // 訊息類型 (模板)
                                        'altText' => '你想知道我哪方面的個人特質？', // 替代文字
                                        'template' => array(
                                            'type' => 'buttons', // 類型 (按鈕)
                                            'title' => '你想知道我哪方面的個人特質？', // 標題 <不一定需要>
                                            'text' => '雖然透過Line@能初步了解我，但是更希望能加入Line實習團隊讓你們徹徹底底了解我', // 文字
                                            'actions' => array(
                                                array(
                                                    'type' => 'message',
                                                    'label' => '你的個性如何',
                                                    'text' => '你的個性如何' 
                                                ),
                                                array(
                                                    'type' => 'message',
                                                    'label' => '你有什麼興趣', 
                                                    'text' => '你有什麼興趣'
                                                ),
                                                array(
                                                    'type' => 'message', 
                                                    'label' => '你大概長怎樣', 
                                                    'text' => '你大概長怎樣'
                                                ),
                                                array(
                                                    'type' => 'message', 
                                                    'label' => '你有什麼技能', 
                                                    'text' => '你有什麼技能'
                                                )
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
                        } elseif (strpos(strtolower($message['text']), 'why you') !== false) {
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                    array(
                                        'type' => 'template', // 訊息類型 (模板)
                                        'altText' => 'Why you?', // 替代文字
                                        'template' => array(
                                            'type' => 'buttons', // 類型 (按鈕)
                                            'title' => 'Why you?', // 標題 <不一定需要>
                                            'text' => 'Just choose me.', // 文字
                                            'actions' => array(
                                                array(
                                                    'type' => 'message',
                                                    'label' => '你為什麼想來Line',
                                                    'text' => '你為什麼想來Line' 
                                                ),
                                                array(
                                                    'type' => 'message',
                                                    'label' => '你為什麼適合這個職位', 
                                                    'text' => '你為什麼適合這個職位'
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
                                            'text' => '第一次提問會等待較久，先不要離開，我真的會回覆QQ，更多操作說明在記事本哦', // 文字
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
                                                    'label' => 'Why you', 
                                                    'text' => 'Why you'
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
