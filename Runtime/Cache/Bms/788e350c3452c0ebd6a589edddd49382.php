<?php if (!defined('THINK_PATH')) exit();?><!--避免重复加载-->
<?php if($plugins_param['stat_type'] == 1 and $plugins_param['is_first'] == 1): ?><script src="/Public/Bms/js/unicorn.interface.js"></script><?php endif; ?>
<?php if($plugins_param['stat_type'] != 1 and $plugins_param['is_first'] == 1): ?><script src="/Public/Static/highcharts/js/highcharts.js"></script>
    <script src="/Public/Static/highcharts/js/modules/exporting.js"></script><?php endif; ?>
<?php if($plugins_param['stat_type'] == 1): ?><li>
        <div class="left peity-<?php echo ($plugins_param['peity']); ?>">
            <span>
                <span style="display: none;">
                    <?php
 $rand = ''; for($i = 0; $i < 7; $i++) { $rand .= rand(5,20) . ','; } echo $rand; ?>
                </span>
                <canvas height="24" width="50"></canvas>
            </span>
            <?php echo ($plugins_param['title_text']); ?>
        </div>
        <div class="right">
            <?php if(empty($plugins_param['href'])): ?><strong><?php echo ((isset($plugins_param['data']) && ($plugins_param['data'] !== ""))?($plugins_param['data']):$plugins_param['default']); ?></strong>
                <?php echo ($plugins_param['title_en_text']); ?>
            <?php else: ?>
                <a href="<?php echo ($plugins_param['href']); ?>" style="color: #666666" target="_blank">
                    <strong><?php echo ((isset($plugins_param['data']) && ($plugins_param['data'] !== ""))?($plugins_param['data']):$plugins_param['default']); ?></strong>
                    <?php echo ($plugins_param['title_en_text']); ?>
                </a><?php endif; ?>
        </div>
    </li>
<?php else: ?>
    <!--是否核心内容-->
    <?php if($plugins_param['core_content'] == 1): ?><div id="<?php echo ($plugins_param['chart_id']); ?>" class="" style="height:400px;"></div>
    <?php else: ?>
        <div class="span6" <?php if(($plugins_param['margin']) == "1"): ?>style="margin-left: 2.12766%"<?php else: ?>style="margin-left:0;"<?php endif; ?>>
            <div class="widget-box">
                <div class="widget-title">
                    <span class="icon">
                        <i class="fa fa-<?php echo ((isset($plugins_param['title_icon']) && ($plugins_param['title_icon'] !== ""))?($plugins_param['title_icon']):'line-chart'); ?>"></i>
                    </span>
                    <h5><?php echo ((isset($plugins_param['title_text']) && ($plugins_param['title_text'] !== ""))?($plugins_param['title_text']):'统计'); ?></h5>
                </div>
                <div class="widget-content">
                    <div id="<?php echo ($plugins_param['chart_id']); ?>" class="" style="height:<?php echo ($plugins_config['height']); ?>;"></div>
                </div>
            </div>
        </div><?php endif; endif; ?>

<?php if($plugins_param['chart_type'] == 'spline'): ?><script>
        /** 折线图 **/
        $(function () {
            $('#<?php echo ($plugins_param["chart_id"]); ?>').highcharts({
                //类型
                chart: {
                    type: 'spline'
                },
                //顶部标题 {plugins_param["title_text"]}
                title: {
                    text: '',
                    x: -20,
                    style: {color: '#1B3243',fontWeight: 'bold',fontSize: '16px'}
                },
                //顶部小标题
                subtitle: {
                    text: "" ,
                    x: -20,
                    y:22
                },
                //横坐标
                xAxis: {
                    categories: [<?php echo ($plugins_param["chart_data"]["x"]["x"]); ?>],
                    labels: { align: 'center', step: <?php echo ($plugins_param["chart_data"]["x"]["step"]); ?>}
                },
                //纵坐标
                yAxis: {
                    title: {text: '<?php echo ($plugins_param["y_title_text"]); ?>'},
                    plotLines: [{value: 0,width: 1,color: '#808080'}]
                },
                tooltip: { //单一显示 还是共同数量显示 valueSuffix数量后边的单位
                    crosshairs: true,
                    shared: true,
                    valueSuffix: '<?php echo ($plugins_param["value_suffix"]); ?>'
                },
//                legend: { //底部标题的位置
//                    layout: 'vertical',
//                    align: 'center',
//                    verticalAlign: 'bottom',
//                    borderWidth: 0
//                },
                plotOptions: {
                    spline: {
                        marker: { radius: 4, lineColor: '#ccc', lineWidth: 1 }
                    }
                },
                series: [<?php echo ($plugins_param["chart_data"]["line"]); ?>]//数据
            });
        });
    </script>
    <?php elseif($plugins_param['chart_type'] == 'column'): ?>
    <script>
        /** 柱形图 **/
        $(function () {
            $('#<?php echo ($plugins_param["chart_id"]); ?>').highcharts({
                //类型
                chart: {
                    type: 'column'
                },
                //顶部标题
                title: {
                    text: '',
                    x: -20 ,
                    style: {color: '#1B3243',fontWeight: 'bold',fontSize: '25px'}
                },
                //顶部小标题
                subtitle: {
                    text: "" ,
                    x: -20,
                    y:22
                },
                //横坐标
                xAxis: {
                    categories: [<?php echo ($plugins_param["chart_data"]["x"]["x"]); ?>],
                    labels: {
                        align: 'center',
                        step: <?php echo ($plugins_param["chart_data"]["x"]["step"]); ?>
                    }
                },
                //纵坐标
                yAxis: {
                    title: {text: '<?php echo ($plugins_param["y_title_text"]); ?>'},
                    plotLines: [{value: 0,width: 1,color: '#808080'}]
                },
                //鼠标滑过提示
                tooltip: {
//                    crosshairs: true,
//                    shared: true, //单一显示 还是共同数量显示
                    valueSuffix: '<?php echo ($plugins_param["value_suffix"]); ?>', //valueSuffix数量后边的单位
                    formatter: function() {
                        return this.series.name + '<br>' + '<span style="color:'+this.point.color+'">\u25cf</span> ' + this.x + ': <b>'+ this.y +'<?php echo ($plugins_param["value_suffix"]); ?></b>';
                    }
                },
//                legend: { //底部标题的位置
//                    layout: 'vertical',
//                    align: 'center',
//                    verticalAlign: 'bottom',
//                    borderWidth: 0
//                },
                plotOptions: {
                    spline: {
                        marker: {
                            radius: 4,
                            lineColor: '#ccc',
                            lineWidth: 1
                        }
                    },
                    column: {
                        cursor: 'normal',
                        dataLabels: {
                            enabled: true,
//                            color: colors[0],
                            style: {
                                fontWeight: 'bold'
                            },
                            formatter: function() {
                                return this.y;
                            }
                        }
                    }
                },
                series: [<?php echo ($plugins_param["chart_data"]["line"]); ?>]//数据
            });
        });
    </script>
    <?php elseif($plugins_param['chart_type'] == 'pie'): ?>
    <script>
        /** 饼状图 **/
        $(function () {
            $('#<?php echo ($plugins_param["chart_id"]); ?>').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: '',
                    style: {color: '#666'}
                },
                tooltip: {
                    pointFormat: '{series.name}: {point.percentage:.1f}%'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#666',
                            connectorColor: '#666',
                            formatter: function() {return '<span style="font-size:12px;">'+ this.point.name +':'+ this.percentage.toFixed(1) +' %</span>';}
                        }
                    }
                },
                series: [{
                    name: '<?php echo ($plugins_param["series_name"]); ?>',
                    data: [<?php echo ($plugins_param["chart_data"]); ?>]
                }]
            });
        })
    </script><?php endif; ?>