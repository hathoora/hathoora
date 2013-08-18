<?php 
    
    $arrHttpStatusColor = array(
        '200' => '#176A17',
        '404' => '#FF0000'
    );
?>

<link rel="stylesheet" type="text/css" href="/_assets/_hathoora/webprofiler/webprofiler.css?<?php echo $version; ?>" media="screen" />
<script>window.jQuery || document.write('<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"><\/script>');</script>
<script>window.jQuery || document.write('<script src="/_assets/_hathoora/webprofiler/jquery-1.8.3.min.js"><\/script>')</script>
<script type="text/javascript" src="/_assets/_hathoora/webprofiler/webprofiler.js?<?php echo $version; ?>"></script>

<div id="hathoora_debug">
    <div class="hathoora_summary">
        <b>Time:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format(round(\hathoora\logger\profiler::microtimeDiff(HATHOORA_PROFILE_START_TIME, $scriptEndTime) * 1000, 3), 3); ?> msec<br/>
        <b>Memory:</b> &nbsp;&nbsp;<?php echo number_format(round($totalMemory/1024, 2), 2); ?> KB 
    </div>
    
    <div class="hathoora_logo">
        <img src="/_assets/_hathoora/hathoora_logo.png?<?php echo $version;?>" title="Hathoora <?php echo $version;?>" align="absmiddle"/>
        <?php echo HATHOORA_ENV; ?>
    </div>
    
    <div class="hathoora_route" section="route">
        <div class="hathoora_section_toggle"><?php echo HATHOORA_APP . '<span style="color:#975301;"> / </span>' . $controller->getControllerName() . '<span style="color:#975301;"> / </span>' . $controller->getControllerActionName() . ' (<span style="color:' . $arrHttpStatusColor[$httpStatus] .';">'. $httpStatus .'</span>)'?></div>
        <div class="hathoora_section_table_wrapper hathoora_section_tabs">
            <div class="hathoora_section_tab" tab="request">Request</div>
            <div class="hathoora_section_tab" tab="response">Response</div>
            <div class="hathoora_section_tab_content" tab="request">
                <b>Request UUID:</b> <?php echo HATHOORA_UUID; ?> <br/>
                <pre>
                    <?php print_r($request); ?>
                </pre>
            </div>
            <div class="hathoora_section_tab_content" tab="response">
                <pre>
                    <?php /* print_r($response); */ ?>
                </pre>
            </div>
        </div>
    </div>
    
    <div class="hathoora_config" section="config">
        <div class="hathoora_section_toggle">Configutation</div>
        <div class="hathoora_section_table_wrapper">
            <center class="hathoora_section_table">
                <table>
                    <thead>
                        <tr>
                            <th class="hathoora_config_key">Key</th>
                            <th class="hathoora_config_value">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (is_array($arrConfigs) && count($arrConfigs))
                            {
                                $i = -1;
                                foreach($arrConfigs as $c => $_arr)
                                {
                                    foreach($_arr as $k => $v)
                                    {
                                        $i++;
                                        $class = 'even';
                                        if ($i % 2 == 0)
                                            $class = 'odd';
                                            
                                        echo '
                                        <tr class="'. $class .'">
                                            <td>'. $c . '.' . $k .'</td>
                                            <td>'. (is_array($v) || is_object($v) ? '<pre>'. print_r($v, true) . '</pre>' : $v) .'</td>
                                        </tr>';
                                    }
                                }
                            }
                            else
                            {
                                echo '
                                <tr>
                                    <td colspan="2">No configuration found.</td>
                                </tr>';                    
                            }
                        ?>
                    </tbody>
                </table>
            </center>
        </div>
    </div>

    <div class="hathoora_log" section="log">
        <div class="hathoora_section_toggle">Logging (<?php echo count($arrLog); ?>)</div>
        <div class="hathoora_section_table_wrapper">
            <div style="padding-left:10px; padding-bottom:5px;">
                <b>hathoora.logger.logging.enabled:</b> <?php echo $loggingStatus; ?><br/>
                <b>hathoora.logger.webprofiler.content_types:</b> <?php print_r($arrContentTypeRegexes); ?> <br/>
            </div>
            <center class="hathoora_section_table">
                <table>
                    <thead>
                        <tr>
                            <th class="hathoora_log_num">#</th>
                            <th class="hathoora_log_time">Time&nbsp;(msec)</th>
                            <th class="hathoora_log_level">Level</th>
                            <th class="hathoora_log_memory">Memory&nbsp;(KB)</th>
                            <th class="hathoora_log_message">Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (is_array($arrLog) && count($arrLog))
                            {
                                $k = 0;
                                foreach($arrLog as $i => $_arrLog)
                                {
                                    $k++;
                                    $class = 'even';
                                    if ($i % 2 == 0)
                                        $class = 'odd';
                                        
                                    $time = $_arrLog['time'];
                                    $memory = number_format(round($_arrLog['memory']/1024, 2), 2);
                                    
                                    echo '
                                    <tr class="'. $class .'">
                                        <td>'. $k .'</td>
                                        <td class="n">'. number_format(round(\hathoora\logger\profiler::microtimeDiff(HATHOORA_PROFILE_START_TIME, $time) * 1000, 3), 3) .'</td>
                                        <td>'. $_arrLog['level'] .'</td>
                                        <td class="n">'. $memory .'</td>
                                        <td>'. $_arrLog['message'] .'</td>
                                    </tr>';
                                }
                            }
                            else
                            {
                                echo '
                                <tr>
                                    <td colspan="5">Nothing is being logged, make sure you have defined "<i>hathoora.logger.logging.enabled</i>" and "<i>hathoora.logger.logging.level</i>" in configuration files properly.</td>
                                </tr>';                    
                            }
                        ?>
                    </tbody>
                </table>
            </center>
        </div>
    </div>

    <div class="hathoora_profiling" section="profiling">
        <div class="hathoora_section_toggle">Profiling</div>
        <div class="hathoora_section_table_wrapper hathoora_section_tabs">
            <?php
                if (is_array($arrProfile))
                {
                    //printr($arrProfile);
                    $arrProfileKeys = array_keys($arrProfile);
                    foreach ($arrProfileKeys as $k)
                    {
                        echo '<div class="hathoora_section_tab" tab="'. $k .'">'. $k .'</div>';
                    }
                    
                    foreach ($arrProfileKeys as $k)
                    {
                        if ($k == 'cache')
                        {
                            echo '
                            <div class="hathoora_section_tab_content" tab="'. $k .'">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="hathoora_profile_num">#</th>
                                            <th class="hathoora_profile_took">Time&nbsp;(msec)</th>
                                            <th class="hathoora_profile_name">Pool</th>
                                            <th class="hathoora_profile_name">Method</th>
                                            <th class="hathoora_profile_message">Key</th>
                                            <th class="hathoora_profile_name">Status</th>
                                            <th class="hathoora_profile_took">Took&nbsp;(msec)</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                            $_arr = $arrProfile[$k];
                            if (is_array($_arr))
                            {
                                $i = 0;
                                foreach ($_arr as $k => $_arrProfile)
                                {
                                    $i++;
                                    $class = 'even';
                                    if ($k % 2 == 0)
                                        $class = 'odd';
                                    
                                    $start = $_arrProfile['start'];
                                    $end = $_arrProfile['end'];
                                    
                                    $took = number_format(round(\hathoora\logger\profiler::microtimeDiff($start, $end) * 1000, 3), 3);
                                    
                                    echo '
                                    <tr class="'. $class .'">
                                        <td>'. $i .'</td>
                                        <td class="n">'. number_format(round(\hathoora\logger\profiler::microtimeDiff(HATHOORA_PROFILE_START_TIME, $start) * 1000, 3), 3) .'</td>
                                        <td>'. $_arrProfile['poolName'] .'</td>
                                        <td>'. $_arrProfile['method'] .'</td>
                                        <td>'. $_arrProfile['name'] .'</td>
                                        <td style="padding-right:10px;">'. $_arrProfile['status'] .'</td>
                                        <td class="n">'. $took .'</td>
                                    </tr>';
                                }
                            }
                            else
                            {
                                echo '
                                <tr>
                                    <td colspan="5">Nothing is being profiled.</td>
                                </tr>';                                            
                            }
                            
                            echo '  </tbody>
                                </table>
                            </div>';
                        }                    
                        else if ($k == 'db')
                        {
                            echo '
                            <div class="hathoora_section_tab_content" tab="'. $k .'">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="hathoora_profile_num">#</th>
                                            <th class="hathoora_profile_time">Time&nbsp;(msec)</th>
                                            <th class="hathoora_profile_name">DSN</th>
                                            <th class="hathoora_profile_message">Query</th>
                                            <th class="hathoora_profile_took">Took&nbsp;(msec)</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                            $_arr = $arrProfile[$k];
                            if (is_array($_arr))
                            {
                                $i = 0;
                                foreach ($_arr as $k => $_arrProfile)
                                {
                                    $i++;
                                    $class = 'even';
                                    if ($k % 2 == 0)
                                        $class = 'odd';
                                    
                                    $start = $_arrProfile['start'];
                                    $end = $_arrProfile['end_query'];
                                    $end_execution = isset($_arrProfile['end_execution']) ? $_arrProfile['end_execution'] : null;
                                    
                                    $query_time = number_format(round(\hathoora\logger\profiler::microtimeDiff($start, $end) * 1000, 3), 3);
                                    $execution_time = false;
                                    if ($end_execution)
                                        $execution_time = number_format(round(\hathoora\logger\profiler::microtimeDiff($start, $end_execution) * 1000, 3), 3);
                                    $error = isset($_arrProfile['error']) ? $_arrProfile['error'] : null;
                                    if ($error)
                                        $error = '<div class="hathoora_profile_error">'. $error .'</div>';
                                    $comment = $_arrProfile['comment'];
                                    if ($comment)
                                        $comment = '<div style="color:#BCBCBC; font-size:11px;">'. $comment .'</div>';
                                    
                                    echo '
                                    <tr class="'. $class .'">
                                        <td>'. $i .'</td>
                                        <td class="n">'. number_format(round(\hathoora\logger\profiler::microtimeDiff(HATHOORA_PROFILE_START_TIME, $start) * 1000, 3), 3) .'</td>
                                        <td>'. $_arrProfile['dsn_name'] .'</td>
                                        <td style="padding-right:10px;">
                                            '. $comment .'
                                            '. nl2br(htmlentities($_arrProfile['query'])) . $error .'
                                        </td>
                                        <td class="n">'. $query_time . ($execution_time ? '<br/><span>('. $execution_time .')' : '') .'</td>
                                    </tr>';
                                }
                            }
                            else
                            {
                                echo '
                                <tr>
                                    <td colspan="5">Nothing is being profiled.</td>
                                </tr>';                                            
                            }
                            
                            echo '  </tbody>
                                </table>
                            </div>';
                        }
                        else if ($k == 'template')
                        {
                            echo '
                            <div class="hathoora_section_tab_content" tab="'. $k .'">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="hathoora_profile_num">#</th>
                                            <th class="hathoora_profile_time">Time&nbsp;(msec)</th>
                                            <th>Name</th>
                                            <th class="hathoora_profile_num">Cached</th>
                                            <th class="hathoora_profile_took">Took&nbsp;(msec)</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                            $_arr = $arrProfile[$k];
                            if (is_array($_arr))
                            {
                                $i = 0;
                                foreach ($_arr as $k => $_arrProfile)
                                {
                                    $i++;
                                    $class = 'even';
                                    if ($k % 2 == 0)
                                        $class = 'odd';
                                    
                                    $start = $_arrProfile['start'];
                                    $end = $_arrProfile['end'];
                                    
                                    $time = number_format(round(\hathoora\logger\profiler::microtimeDiff($start, $end) * 1000, 3), 3);
                                    
                                    echo '
                                    <tr class="'. $class .'">
                                        <td>'. $i .'</td>
                                        <td class="n">'. number_format(round(\hathoora\logger\profiler::microtimeDiff(HATHOORA_PROFILE_START_TIME, $start) * 1000, 3), 3) .'</td>
                                        <td>'. $_arrProfile['name'] .'</td>
                                        <td>'. $_arrProfile['cached'] .'</td>
                                        <td class="n">'. $time .'</td>
                                    </tr>';
                                }
                            }
                            else
                            {
                                echo '
                                <tr>
                                    <td colspan="5">Nothing is being profiled.</td>
                                </tr>';                                            
                            }
                            
                            echo '  </tbody>
                                </table>
                            </div>';
                        }                    
                    }
                }
                else
                {
                    echo 'No profiling information is available.';
                }
            ?>
        </div>
    </div>
</div>