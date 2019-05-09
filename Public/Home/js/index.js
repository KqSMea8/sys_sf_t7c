// 请求我的团队 --个人信息
$.ajax({
	type: "POST",
	url: "http://jr.wokerr.com/index.php/Home/Index/team",
	data: {},
	async: false,
	dataType: "json",
	success: function(data) {
		console.log(data);
		var tempHTML = "";
		for(var i = 0; i < data.data.length; i++) {
			tempHTML += '<div class="team_demo"><div class="team_demo_content"><span class="left_tx"><img src="http://jr.wokerr.com/Uploads' + data.data[i].loc + '" alt="" /></span><span class="right_name">' + data.data[i].name + '</span><p class="right_job">' + data.data[i].pos + '</p></div><div class="team_demo_skill">' + data.data[i].describe + '</div></div>';
		}
		$(".team .team_item").html(tempHTML);
	}
})

//版权
$.ajax({
	type:"post",
	url:"http://jr.wokerr.com/index.php/Home/Index/cu_info",
	async:false,
	data:{},
	dataType:"json",
	success:function(data){
		console.log(data);
		var tempHTML="";
		tempHTML='<div class="contact"><div class="contact_our">联系我们</div><div class="contact_now"><span>电话 ：</span><span>'+ data.data.tel +'</span></div><div class="contact_now"><span>邮编 ： </span><span>'+ data.data.email +'</span></div><div class="contact_now"><span>工作时间 ：</span><span>'+ data.data.work_time +'</span></div></div><div class="address">'+ data.data.copyright +'</div><div class="codeall"><div class="code"><img src="http://jr.wokerr.com/Uploads'+ data.data.weixin +'" alt=""><p>微信公众号</p></div><div class="code"><img src="http://jr.wokerr.com/Uploads'+ data.data.qq +'" alt=""><p>官方QQ群</p></div></div>';
		$(".footer_content").html(tempHTML);
	}
});

//产品折线图请求
var myChart = echarts.init(document.getElementById('chartArea'));
var ItemLine = function() {
	return {
		itemStyle: {
			normal: {
				lineStyle: {
					color: '#0f0' //控制折线颜色
				}
			}
		},
		name: '',
		type: 'line',
//		symbol: 'none',   //去掉小圆点
		data: []
		//			itemStyle : { normal: {}}
	}
};
$.ajax({
	type: "post",
	url: "http://jr.wokerr.com/index.php/home/index/worthChart",
	data: {},
	async: false,
	dataType: "json",
	success: function(data) {
		console.log(data);
		var xTime = [];
		var newDate = new Date();
		var times;
		var Title = [];
		var Colour = [];
		var SeriesTotal = [];
		var now = new Date();
		var xdata;
		for(var i = 0; i < data.data.length; i++) {
			var itemLine = new ItemLine();
			if(data.data[i].data != null) {
				for(var j = 0; j < data.data[i].data.length; j++) {
					console.log(data.data[i].data.length);
					
					itemLine.data.push(data.data[i].data[j].jz);
				}
				if(i==0){
					for(var y=0; y<data.data[i].data.length; y++){
						xTime.push(data.data[i].data[y].time);
					}
				}
				
			}
			Title.push(data.data[i].title);
			Colour.push(data.data[i].colour);
			itemLine.name = data.data[i].title;
			itemLine.itemStyle.normal.lineStyle.color = data.data[i].colour;
			SeriesTotal.push(itemLine);
		}

//		var base = +new Date(2016, 10, 21);
//		var oneDay = 24 * 3600 * 1000;
//		var date = [];
//		var data = [Math.random() * 300];
//
//		for(var y = 1; y < 500; y++) {
//			var now = new Date(base += oneDay);
//			date.push([now.getFullYear(), now.getMonth() + 1, now.getDate()].join('/'));
//			data.push(Math.round((Math.random() - 0.5) * 20 + data[y - 1]));
//		}

		console.log(SeriesTotal);
		var option = {
			title: {
				text: '',
				x: "center",
				y: "top"
			},
			tooltip: {
				//					trigger: 'axis',
				axisPointer: { //鼠标滑过的线条样式
					type: 'line',
					lineStyle: {
						//color: 'red',//颜色
						width: 1, //宽度
						type: 'solid' //实线
					}
				}
			},
			calculable: false,
			dataZoom: [{

				startValue: '0' //只需要将这一项设置为0即可
			}, {
				type: 'inside'
			}],
			xAxis: [{ //x轴的数据
				type: 'category',
				name: '月份',
				boundaryGap: false,
//				axisLabel:{   //设置x轴刻度间隔
//                  interval:5,
//				},
				data: xTime
			}],
			yAxis: {
				type: 'value',
				name: "数值",
				min:1,
				max:1.25,
				splitArea: {
					show: true
				}
			},
			color: Colour,
			legend: {
				data: Title, //要与series中的name一致
				y: "top"
			},
			series: SeriesTotal
		};

		// 为echarts对象加载数据
		myChart.setOption(option, true);
	}
})