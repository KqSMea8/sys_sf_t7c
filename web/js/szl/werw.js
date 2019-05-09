window.onload = function() {
    /*
     *欢迎关注https://github.com/weijhfly/rolldate，并给个star，此插件会持续完善。
     */
    // 格式
    new rolldate.Date({
        el: '#date15',
        format: 'YYYY',
        beginYear: 2000,
        endYear: 2100
    })
    new rolldate.Date({
        el: '#date',
        format: 'YYYY-MM',
        beginYear: 2000,
        endYear: 2100
    })
    new rolldate.Date({
        el: '#date2',
        format: 'YYYY-MM-DD',
        beginYear: 2000,
        endYear: 2100
    })
    new rolldate.Date({
        el: '#date3',
        format: 'YYYY-MM-DD hh:mm',
        beginYear: 2000,
        endYear: 2100
    })
    new rolldate.Date({
        el: '#date4',
        format: 'YYYY-MM-DD hh:mm:ss',
        beginYear: 2000,
        endYear: 2100
    })
    new rolldate.Date({
        el: '#date17',
        format: 'MM'
    })
    new rolldate.Date({
        el: '#date18',
        format: 'DD'
    })
    new rolldate.Date({
        el: '#date19',
        format: 'hh:mm'
    })
    new rolldate.Date({
            el: '#date20',
            format: 'hh:mm:ss'
        })
        // 主题
    new rolldate.Date({
        el: '#date5',
        format: 'YYYY-MM-DD',
        beginYear: 2000,
        endYear: 2100,
        theme: 'blue'
    })
    new rolldate.Date({
        el: '#date6',
        format: 'YYYY-MM-DD',
        beginYear: 2000,
        endYear: 2100,
        theme: 'red'
    })
    new rolldate.Date({
        el: '#date7',
        format: 'YYYY-MM-DD',
        beginYear: 2000,
        endYear: 2100,
        theme: 'green'
    })
    new rolldate.Date({
        el: '#date8',
        format: 'YYYY-MM-DD',
        beginYear: 2000,
        endYear: 2100,
        theme: 'black'
    })
    new rolldate.Date({
            el: '#date9',
            format: 'YYYY-MM-DD',
            beginYear: 2000,
            endYear: 2100,
            theme: '#f90'
        })
        // 回调
    new rolldate.Date({
        el: '#date10',
        format: 'YYYY-MM-DD',
        beginYear: 2000,
        endYear: 2100,
        theme: 'blue',
        tapBefore: function(el) {
            console.log('插件开始触发');
        },
        moveEnd: function(el, iscroll) {
            console.log('滚动结束');
        },
        confirmBefore: function(el, date) {
            //console.log(this,el,date)
            console.log('确定按钮触发');
        },
        confirmEnd: function(el, date) {
            console.log('插件运行结束');
        }
    })
  	new rolldate.Date({
        el: '#date10_end',
        format: 'YYYY-MM-DD',
        beginYear: 2000,
        endYear: 2100,
        theme: 'blue',
        tapBefore: function(el) {
            console.log('插件开始触发');
        },
        moveEnd: function(el, iscroll) {
            console.log('滚动结束');
        },
        confirmBefore: function(el, date) {
            //console.log(this,el,date)
            console.log('确定按钮触发');
        },
        confirmEnd: function(el, date) {
            console.log('插件运行结束');
        }
    })
    new rolldate.Date({
        el: '#date11',
        format: 'YYYY-MM-DD',
        beginYear: 2000,
        endYear: 2100,
        theme: 'blue',
        tapBefore: function(el) {
            if (1 == 1) {
                //console.log(this);
                console.log('阻止插件继续执行');
                return false;
            }
        }
    })
    new rolldate.Date({
            el: '#date12',
            format: 'YYYY-MM-DD',
            beginYear: 2000,
            endYear: 2100,
            theme: 'blue',
            confirmBefore: function(el, date) {
                return date.replace(/-/g, '/');
            }
        })
        // 其他示例
    new rolldate.Date({ //日期判断
        el: '#date13',
        format: 'YYYY-MM-DD',
        beginYear: 2000,
        endYear: 2100,
        theme: 'blue',
        confirmBefore: function(el, date) {
            var d = new Date(),
                d1 = new Date(date.replace(/\-/g, "\/")),
                d2 = new Date(d.getFullYear() + '/' + (d.getMonth() + 1) + '/' + d.getDate()); //如果非'YYYY-MM-DD'格式，需要另做调整

            if (d1 < d2) {
                alert('不能小于当前的日期');
                return false;
            }
        }
    })
    var vue = new Vue({
        el: "#app",
        data: {
            date: ''
        }
    })
    new rolldate.Date({ //设置model
            el: '#date14',
            format: 'YYYY-MM-DD',
            beginYear: 2000,
            endYear: 2100,
            theme: 'blue',
            confirmBefore: function(el, date) {
                vue.date = date;
            }
        })
        // scrollTime
    new rolldate.Date({
        el: '#date16',
        format: 'YYYY-MM-DD',
        beginYear: 2000,
        endYear: 2100,
        theme: 'blue',
        scrollTime: 0
    })
}