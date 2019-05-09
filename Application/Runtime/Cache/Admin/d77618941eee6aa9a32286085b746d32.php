<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>公证处机构人员统计-<?php echo ($site["SITE_INFO"]["name"]); ?></title>
        <?php $addCss=""; $addJs=""; $currentNav ='公证处机构人员统计'; ?>
        <link rel="stylesheet" type="text/css" href="/Public/Min/?f=/Public/Admin/Css/base.css|/Public/Admin/Css/layout.css|/Public/Js/asyncbox/skins/default.css<?php echo ($addCss); ?>" />
<script type="text/javascript" src="/Public/Min/?f=/Public/Js/jquery-1.9.0.min.js|/Public/Js/jquery.lazyload.js|/Public/Js/functions.js|/Public/Admin/Js/base.js|/Public/Js/jquery.form.js|/Public/Js/asyncbox/asyncbox.js<?php echo ($addJs); ?>"></script>
<link rel="stylesheet" type="text/css" href="/Public/Css/page.css">



<link href="/Public/Css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/Css/head.css" rel="stylesheet" type="text/css">

        <script type="text/javascript" src="/Public/Min/?f=/Public/Js/jquery-1.5.2.min.js|/Public/Js/highcharts.js|/Public/Js/grid.js"></script>
    </head>
    <body>
        <div class="wrap">
            <!-- <div id="Top">
    <div class="logo"><a target="_blank" href="<?php echo ($site["WEB_ROOT"]); ?>"><img src="/Public/Admin/Img/logo.png" /></a></div>
    
    <div class="menu">
        <ul> <?php echo ($menu); ?> </ul>
    </div>
</div>
<div id="Tags">
    <div class="userPhoto"><img src="/Uploads/<?php echo ($my_info["loc"]); ?>" /> </div>
    <div class="navArea">
        <div class="userInfo"><div><a href="<?php echo U('Webinfo/index');?>" class="sysSet"><span>&nbsp;</span>系统设置</a> <a href="<?php echo U("Public/loginOut");?>" class="loginOut"><span>&nbsp;</span>退出系统</a></div>欢迎您，<?php echo ($my_info["email"]); ?></div>
        <div class="nav"><font id="today"><?php echo date("Y-m-d H:i:s"); ?></font>您的位置：<?php echo ($currentNav); ?></div>
    </div>
</div>
<div class="clear"></div> -->

<div id="Top">
    <div class="admincp-header">
	  <div class="bgSelector"></div>
	 

	  <div class="nc-module-menu">
	    <ul class="nc-row">
	      <?php echo ($menu); ?>
	      <!-- <li><a href="<?php echo U('Webinfo/index');?>" class="sysSet"><span>&nbsp;</span>系统设置</a></li> -->
      	  <li><a href="<?php echo U("Public/loginOut");?>" class="loginOut"><span>&nbsp;</span>退出系统</a></li>
	    </ul>
	  </div>
	<div class="clear"></div>
</div>


            <div class="mainBody">
                <div id="Left">
    <div id="control" class=""></div>
    <div class="subMenuList">
        <div class="itemTitle"><!-- <?php if(CONTROLLER_NAME == 'Index'): ?>常用操作<?php else: ?>子菜单<?php endif; ?> --> </div>
        <ul>
            <?php if(is_array($sub_menu)): foreach($sub_menu as $key=>$sv): if($sv["sub3"] != 'false'): ?><li class="togellernav"><a><?php echo ($sv["title"]); ?></a>
                        <ul style="margin-top: 0;margin-left: 40px;">
                            <?php if(is_array($sv['sub3'])): foreach($sv['sub3'] as $key=>$s): ?><!-- <li><a href="<?php echo ($s["url"]); ?>"><?php echo ($s["title"]); ?></a></li> -->
                                <!--  四级菜单开始-->
                                <?php if($s["sub4"] != 'false'): ?><li class="togellernav"><a><?php echo ($s["title"]); ?></a></li>
                                    <ul style="margin-top: 0;margin-left: 30px;">
                                        <?php if(is_array($s['sub4'])): foreach($s['sub4'] as $key=>$s4): ?><li><a href="<?php echo ($s4["url"]); ?>"><?php echo ($s4["title"]); ?></a></li><?php endforeach; endif; ?>
                                    </ul>
                                <?php else: ?>
                                    <li><a href="<?php echo ($s["url"]); ?>"><?php echo ($s["title"]); ?></a></li><?php endif; ?>
                                <!--  四级菜单结束--><?php endforeach; endif; ?>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo ($sv["url"]); ?>"><?php echo ($sv["title"]); ?></a></li><?php endif; endforeach; endif; ?>
        </ul>
    </div>
</div>
                <div id="Right">
                    <div class="Item hr">
                        <div class="current">公证处机构人员统计</div>
                        <div>
                            <form method="post">
                            <input type="hidden" name="id" value="<?php echo ($id); ?>">
                            <input type="date" name="tl" value="<?php echo ($tl); ?>">
                            <input type="date" name="tr" value="<?php echo ($tr); ?>">
                            <button>查询</button></form>
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 15px;margin: 10px 0;">回复总数：<?php echo ($count); ?> 服务总人数：<?php echo ($hnum); ?> 服务总时间：<?php echo ($ctime); ?></div>
                        <div id="container"></div>
                        <div id="container2"></div>
                        <div id="container3"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <script type="text/javascript">
    $(window).resize(autoSize);
    $(function(){
        autoSize();
        $(".loginOut").click(function(){
            var url=$(this).attr("href");
            popup.confirm('你确定要退出吗？','你确定要退出吗',function(action){
                if(action == 'ok'){ window.location=url; }
            });
            return false;
        });

        var time=self.setInterval(function(){$("#today").html(date("Y-m-d H:i:s"));},1000);


    });

</script>
    </body>
<script type="text/javascript">
var d = <?php echo ($list); ?>;
var chart;
$(document).ready(function() {
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container',          //放置图表的容器
            plotBackgroundColor: null,
            plotBorderWidth: null,
            defaultSeriesType: 'column'   //图表类型line, spline, area, areaspline, column, bar, pie , scatter 
        },
        title: {
            text: '服务人数柱状图演示'
        }, 
        xAxis: {
            categories: d.categories,
            labels: {
                rotation: -45, //字体倾斜
                align: 'right',
                style: { font: 'normal 13px 宋体' }
            }
        },
        yAxis: {
            title: {
                text: '人数'
            }
        },
        tooltip: {
            enabled: true,
            formatter: function() {
                return '<b>' + this.x + '</b><br/>' + this.series.name + ': ' + Highcharts.numberFormat(this.y, 1) + "人";
            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: true//是否显示title
            }
        },
        series: [{
            name: '服务人数',
            data: d.hnum
        },]
    });
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container2',          //放置图表的容器
            plotBackgroundColor: null,
            plotBorderWidth: null,
            defaultSeriesType: 'column'   //图表类型line, spline, area, areaspline, column, bar, pie , scatter 
        },
        title: {
            text: '回复数量柱状图演示'
        }, 
        xAxis: {
            categories: d.categories,
            labels: {
                rotation: -45, //字体倾斜
                align: 'right',
                style: { font: 'normal 13px 宋体' }
            }
        },
        yAxis: {
            title: {
                text: '次'
            }
        },
        tooltip: {
            enabled: true,
            formatter: function() {
                return '<b>' + this.x + '</b><br/>' + this.series.name + ': ' + Highcharts.numberFormat(this.y, 1) + "次";
            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: true//是否显示title
            }
        },
        series: [{
            name: '回复数量',
            data: d.count
        },]
    });
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container3',          //放置图表的容器
            plotBackgroundColor: null,
            plotBorderWidth: null,
            defaultSeriesType: 'column'   //图表类型line, spline, area, areaspline, column, bar, pie , scatter 
        },
        title: {
            text: '服务时间柱状图演示'
        }, 
        xAxis: {
            categories: d.categories,
            labels: {
                rotation: -45, //字体倾斜
                align: 'right',
                style: { font: 'normal 13px 宋体' }
            }
        },
        yAxis: {
            title: {
                text: '分钟'
            }
        },
        tooltip: {
            enabled: true,
            formatter: function() {
                return '<b>' + this.x + '</b><br/>' + this.series.name + ': ' + Highcharts.numberFormat(this.y, 1) + "分";
            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: true//是否显示title
            }
        },
        series: [{
            name: '服务时间',
            data: d.ctime
        },]
    });
});
/*
$(document).ready(function() {
//折线图示例
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container',          //放置图表的容器
            plotBackgroundColor: null,
            plotBorderWidth: null,
            defaultSeriesType: 'line'   
        },
        title: {
            text: 'JQuery曲线图演示'
        },
        subtitle: {
            text: '副标题'
        },
        xAxis: {
            categories: ['一月份', '二月份', '三月份', '四月份', '五月份', '六月份', '七月份', '八月份', '九月份', '十月份', '十一月份', '十二月份'],
            labels: {
                rotation: -45, //字体倾斜
                align: 'right',
                style: { font: 'normal 13px 宋体' }
            }
        },
        yAxis: {
            title: {
                text: '产量/百万'
            }
        },
        tooltip: {
            enabled: true,
            formatter: function() {
                return '<b>' + this.x + '</b><br/>' + this.series.name + ': ' + Highcharts.numberFormat(this.y, 1);
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: true//是否显示title
            }
        },
        series: [{
            name: '杭州',
            data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
        }, {
            name: '江西',
            data: [4.0, 2.9, 5.5, 24.5, 18.4, 11.5, 35.2, 36.5, 23.3, 38.3, 23.9, 3.6]
        }, {
            name: '北京',
            data: [14.0, 12.9, 15.5, 14.5, 28.4, 21.5, 15.2, 16.5, 13.3, 28.3, 13.9, 13.6]
        }, {
            name: '湖南',
            data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
        }]
    });
//柱状图图示例
chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container2',          //放置图表的容器
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    defaultSeriesType: 'column'   //图表类型line, spline, area, areaspline, column, bar, pie , scatter 
                },
                title: {
                    text: 'JQuery柱状图演示'
                }, 
                xAxis: {
                    categories: ['一月份', '二月份', '三月份', '四月份', '五月份', '六月份', '七月份', '八月份', '九月份', '十月份', '十一月份', '十二月份', '十三月份', '十四月份'],
                    labels: {
                        rotation: -45, //字体倾斜
                        align: 'right',
                        style: { font: 'normal 13px 宋体' }
                    }
                },
                yAxis: {
                    title: {
                        text: '产量/百万'
                    }
                },
                tooltip: {
                    enabled: true,
                    formatter: function() {
                        return '<b>' + this.x + '</b><br/>' + this.series.name + ': ' + Highcharts.numberFormat(this.y, 1) + "百万";
                    }
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true//是否显示title
                    }
                },
                series: [{
                    name: '杭州',
                    data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6, 9.6, 9.6]
                }, {
                    name: '江西',
                    data: [4.0, 2.9, 5.5, 24.5, 18.4, 11.5, 35.2, 36.5, 23.3, 38.3, 23.9, 3.6, 9.6, 9.6]
                }, {
                    name: '湖南',
                    data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8, 9.6, 9.6]
                }]
              });
//饼状图图示例
chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container3',          //放置图表的容器
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    defaultSeriesType: 'pie'   //图表类型line, spline, area, areaspline, column, bar, pie , scatter 
                },
                title: {
                    text: 'JQuery饼状图演示'
                }, 
                xAxis: {
                    categories: ['一月份', '二月份', '三月份', '四月份', '五月份', '六月份', '七月份', '八月份', '九月份', '十月份', '十一月份', '十二月份', '十三月份', '十四月份'],
                    labels: {
                        rotation: -45, //字体倾斜
                        align: 'right',
                        style: { font: 'normal 13px 宋体' }
                    }
                },
                yAxis: {
                    title: {
                        text: '产量/百万'
                    }
                },
                tooltip: {
                    enabled: true,
                    formatter: function() {
                        return '<b>' + this.x + '</b><br/>' + this.series.name + ': ' + Highcharts.numberFormat(this.y, 1) + "百万";
                    }
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true//是否显示title
                    }
                },
                series: [{
                   data: [
                        ['杭州', 45.0],
                        ['江西', 26.8],
                        { name: '湖南', y: 12.8, sliced: true, selected: true }, //选中状态
                        ['北京', 8.5],
                        ['上海', 6.2],
                        ['深圳', 0.7]
                     ]
                }]
              });
//spline 平滑曲线图示例
chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container4',          //放置图表的容器
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    defaultSeriesType: 'spline'   //图表类型line, spline, area, areaspline, column, bar, pie , scatter 
                },
                title: {
                    text: 'JQuery spline平滑曲线图演示'
                }, 
                xAxis: {
                    categories: ['一月份', '二月份', '三月份', '四月份', '五月份', '六月份', '七月份', '八月份', '九月份', '十月份', '十一月份', '十二月份', '十三月份', '十四月份'],
                    labels: {
                        rotation: -45, //字体倾斜
                        align: 'right',
                        style: { font: 'normal 13px 宋体' }
                    }
                },
                yAxis: {
                    title: {
                        text: '产量/百万'
                    }
                },
                tooltip: {
                    enabled: true,
                    formatter: function() {
                        return '<b>' + this.x + '</b><br/>' + this.series.name + ': ' + Highcharts.numberFormat(this.y, 1) + "百万";
                    }
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true//是否显示title
                    }
                },
                series: [{
                    name: '杭州',
                    data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6, 9.6, 9.6]
                }, {
                    name: '江西',
                    data: [4.0, 2.9, 5.5, 24.5, 18.4, 11.5, 35.2, 36.5, 23.3, 38.3, 23.9, 3.6, 9.6, 9.6]
                }, {
                    name: '湖南',
                    data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8, 9.6, 9.6]
                }]
              });
//area 填充图示例
chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container5',          //放置图表的容器
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    defaultSeriesType: 'area'   //图表类型line, spline, area, areaspline, column, bar, pie , scatter 
                },
                title: {
                    text: 'JQuery area填充图演示'
                }, 
                xAxis: {
                    categories: ['一月份', '二月份', '三月份', '四月份', '五月份', '六月份', '七月份', '八月份', '九月份', '十月份', '十一月份', '十二月份', '十三月份', '十四月份'],
                    labels: {
                        rotation: -45, //字体倾斜
                        align: 'right',
                        style: { font: 'normal 13px 宋体' }
                    }
                },
                yAxis: {
                    title: {
                        text: '产量/百万'
                    }
                },
                tooltip: {
                    enabled: true,
                    formatter: function() {
                        return '<b>' + this.x + '</b><br/>' + this.series.name + ': ' + Highcharts.numberFormat(this.y, 1) + "百万";
                    }
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true//是否显示title
                    }
                },
                series: [{
                    name: '杭州',
                    data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6, 9.6, 9.6]
                }, {
                    name: '江西',
                    data: [4.0, 2.9, 5.5, 24.5, 18.4, 11.5, 35.2, 36.5, 23.3, 38.3, 23.9, 3.6, 9.6, 9.6]
                }, {
                    name: '湖南',
                    data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8, 9.6, 9.6]
                }]
              });
//areaspline 填充图示例
chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container6',          //放置图表的容器
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    defaultSeriesType: 'areaspline'   //图表类型line, spline, area, areaspline, column, bar, pie , scatter 
                },
                title: {
                    text: 'JQuery areaspline填充图演示'
                }, 
                xAxis: {
                    categories: ['一月份', '二月份', '三月份', '四月份', '五月份', '六月份', '七月份', '八月份', '九月份', '十月份', '十一月份', '十二月份', '十三月份', '十四月份'],
                    labels: {
                        rotation: -45, //字体倾斜
                        align: 'right',
                        style: { font: 'normal 13px 宋体' }
                    }
                },
                yAxis: {
                    title: {
                        text: '产量/百万'
                    }
                },
                tooltip: {
                    enabled: true,
                    formatter: function() {
                        return '<b>' + this.x + '</b><br/>' + this.series.name + ': ' + Highcharts.numberFormat(this.y, 1) + "百万";
                    }
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true//是否显示title
                    }
                },
                series: [{
                    name: '杭州',
                    data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6, 9.6, 9.6]
                }, {
                    name: '江西',
                    data: [4.0, 2.9, 5.5, 24.5, 18.4, 11.5, 35.2, 36.5, 23.3, 38.3, 23.9, 3.6, 9.6, 9.6]
                }, {
                    name: '湖南',
                    data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8, 9.6, 9.6]
                }]
              });
//bar 图示例
chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container7',          //放置图表的容器
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    defaultSeriesType: 'bar'   //图表类型line, spline, area, areaspline, column, bar, pie , scatter 
                },
                title: {
                    text: 'JQuery bar 增长图演示'
                }, 
                xAxis: {
                    categories: ['一月份', '二月份', '三月份', '四月份', '五月份', '六月份', '七月份', '八月份', '九月份', '十月份', '十一月份', '十二月份', '十三月份', '十四月份'],
                    labels: {
                        rotation: -45, //字体倾斜
                        align: 'right',
                        style: { font: 'normal 13px 宋体' }
                    }
                },
                yAxis: {
                    title: {
                        text: '产量/百万'
                    }
                },
                tooltip: {
                    enabled: true,
                    formatter: function() {
                        return '<b>' + this.x + '</b><br/>' + this.series.name + ': ' + Highcharts.numberFormat(this.y, 1) + "百万";
                    }
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true//是否显示title
                    }
                },
                series: [{
                    name: '杭州',
                    data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6, 9.6, 9.6]
                }, {
                    name: '江西',
                    data: [4.0, 2.9, 5.5, 24.5, 18.4, 11.5, 35.2, 36.5, 23.3, 38.3, 23.9, 3.6, 9.6, 9.6]
                }, {
                    name: '湖南',
                    data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8, 9.6, 9.6]
                }]
              });
//scatter 点位图示例
chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container8',          //放置图表的容器
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    defaultSeriesType: 'scatter'   //图表类型line, spline, area, areaspline, column, bar, pie , scatter 
                },
                title: {
                    text: 'JQuery scatter 点位图演示'
                }, 
                xAxis: {
                    categories: ['一月份', '二月份', '三月份', '四月份', '五月份', '六月份', '七月份', '八月份', '九月份', '十月份', '十一月份', '十二月份', '十三月份', '十四月份'],
                    labels: {
                        rotation: -45, //字体倾斜
                        align: 'right',
                        style: { font: 'normal 13px 宋体' }
                    }
                },
                yAxis: {
                    title: {
                        text: '产量/百万'
                    }
                },
                tooltip: {
                    enabled: true,
                    formatter: function() {
                        return '<b>' + this.x + '</b><br/>' + this.series.name + ': ' + Highcharts.numberFormat(this.y, 1) + "百万";
                    }
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true//是否显示title
                    }
                },
                series: [{
                    name: '杭州',
                    data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6, 9.6, 9.6]
                }, {
                    name: '江西',
                    data: [4.0, 2.9, 5.5, 24.5, 18.4, 11.5, 35.2, 36.5, 23.3, 38.3, 23.9, 3.6, 9.6, 9.6]
                }, {
                    name: '湖南',
                    data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8, 9.6, 9.6]
                }]
              });
}); */

</script>
</html>