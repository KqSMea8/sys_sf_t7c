var cc = 0; //计数器
var arr = []; //存储数据答案
var oo = "-" //分隔符
    // var checkbox_backgrond = "rgb(54,129,177)";
var checkbox_backgrond = "#3681B1";
var go_befer = document.getElementsByClassName("go_befer");
var sele_each = document.getElementsByClassName("sele_each");
sele_each[0].style.display = "block";

function tishi(text) {
    $(".tishi").text(text);
    $(".tishi").fadeIn();
    setTimeout(function() {
        $(".tishi").fadeOut()
    }, 1500)
}
for (var i = 0; i < go_befer.length; i++) {
    go_befer[i].addEventListener('click', function() {
        if (cc == 0) {
            // alert("没有上一页了", '来自史志龙的页面', '哈哈', function() {}, 'div')
            window.location.replace("Fzaixianshenban.html");
        } else {
            cc = cc - 1;
            $('.sele_each').eq(cc).fadeIn().siblings().fadeOut();
        }
    }, false)
}


for (let i = 0; i < sele_each.length; i++) {
    var sele_each_list = sele_each[i].getElementsByClassName("sele_each_list");
    var xiayiye = sele_each[i].getElementsByClassName("xiayiye");
    var sele_each_list_check = sele_each[i].getElementsByClassName("sele_each_list_check");
    var form_control = sele_each[i].getElementsByClassName("form-control");
    var user_select = sele_each[i].getElementsByClassName("user_select");
    //单选翻页架构
    if (sele_each_list.length) {
        for (let j = 0; j < sele_each_list.length; j++) {
            sele_each_list[j].addEventListener('click', function() {
                // $('.sele_each').eq(i).hide().fadeOut(1000);
                cc = i + 1;
                arr[i] = $(this).parent().find(".question").html() + this.innerHTML;
                console.log(arr + "");
                $('.sele_each').eq(cc).fadeIn().siblings().fadeOut();
                // console.log(cc);
                // if (cc == sele_each.length) {
                //     alert("没有下一页了");
                // }
                go_back()
            }, false)
        }
        //input 输入框架构
    } else if (xiayiye.length) {
        xiayiye[0].addEventListener('click', function() {
                var tt = sele_each[i].getElementsByTagName('input')[0].value;
                cc = i + 1;
                arr[i] = $(this).siblings(".question").text() + tt;
                console.log(arr);
                $('.sele_each').eq(cc).fadeIn().siblings().fadeOut();
                console.log(cc);
                go_back()
            }, false)
            //多选架构
    } else if (sele_each_list_check.length) {
        // console.log(sele_each_list_check.length);
        var len_check = sele_each_list_check.length;
        for (let ii = 0; ii < sele_each_list_check.length; ii++) {
            sele_each_list_check[ii].addEventListener('click', function() {
                if (this.style.backgroundColor == "") {
                    this.style.backgroundColor = "#3681B1";
                } else {
                    this.style.backgroundColor = "";
                    // console.log(this.style.backgroundColor)
                }
                console.log(this.style.backgroundColor)
            }, false)
        }
        console.log(sele_each_list_check.length);
        var check_xiayiye = sele_each[i].getElementsByClassName("check_xiayiye")[0];
        check_xiayiye.addEventListener('click', function() {
                var sele_each_list_check_bibao = sele_each[i].getElementsByClassName("sele_each_list_check");
                console.log(sele_each_list_check_bibao.length);
                // console.log(sele_each_list_check.length);
                var check_content = '';
                var count = 0;
                for (var p = 0; p < sele_each_list_check_bibao.length; p++) {
                    console.log(11);
                    console.log(sele_each_list_check_bibao[p].style.backgroundColor);
                    if (sele_each_list_check_bibao[p].style.backgroundColor == "#3681B1" || sele_each_list_check_bibao[p].style.backgroundColor == "rgb(54, 129, 177)") {
                        if (p != sele_each_list_check_bibao.length - count) {
                            check_content += sele_each_list_check_bibao[p].innerHTML + oo;
                        } else {
                            check_content += sele_each_list_check_bibao[p].innerHTML
                        }
                    }
                }
                cc = i + 1;
                arr[i] = "第" + cc + "答案：" + check_content;
                console.log(arr);
                $('.sele_each').eq(cc).fadeIn().siblings().fadeOut();
                console.log(cc);
                go_back()
                    // arr[i] = arr[i] = "第" + cc + "答案：" + check_content;
                    // console.log(arr);
            }, false)
            //三级联动地址架构
    } else if (form_control.length) {
        var address_three_sub = sele_each[i].getElementsByClassName("address_three_sub")[0];
        var form_control_con = sele_each[i].getElementsByClassName("form-control");
        address_three_sub.addEventListener('click', function() {
                // for (let prop in form_control_con) {
                var add_ress = '';
                // for (var prop = 0; prop < form_control_con.length; prop++) {
                //     var index = form_control_con[prop].selectedIndex;
                //     console.log(form_control_con[prop].options[index].value);
                //     if (prop != form_control_con.length - 1) {
                //         add_ress += form_control_con[prop].options[index].value + oo;
                //     } else {
                //         add_ress += form_control_con[prop].options[index].value;
                //     }
                // };
                add_ress = $("#cmbCity").val();
                cc = i + 1;
                arr[i] = $(this).parent().find(".question").text() + add_ress;
                console.log(arr);
                $('.sele_each').eq(cc).fadeIn().siblings().fadeOut();
                console.log(cc);
                go_back()
            }, false)
            //用户select选择提交架构
    } else if (user_select.length) {
        var user_select_submit = sele_each[i].getElementsByClassName("user_select_submit")[0];
        user_select_submit.addEventListener('click', function() {
            var user_select_con = sele_each[i].getElementsByClassName("user_select")[0];
            // var index = user_select_con.selectedIndex;
            // var uu = user_select_con.options[index].innerHTML;
            var uu = user_select_con.innerHTML;
            cc = i + 1;
            arr[i] = $(this).parent().find(".question").text() + uu;
            console.log(arr);
            $('.sele_each').eq(cc).fadeIn().siblings().fadeOut();
            console.log(cc);
            go_back()
        }, false)
    } else {
        var pp = i + 1;
        tishi("第" + pp + "页存在不符合违反页面");
    }
}


var chogntian = document.getElementById("chogntian");
chogntian.addEventListener('click', function() {
    tishi("回到初始页面");
    window.location.reload();
}, false)


//最后一页回调函数
function go_back() {
    if (cc == sele_each.length) {
        tishi("已完成全部题目");
        localStorage.setItem("daan", arr);
        window.location.replace("Fzaixianshenban.html?type=0");
        // history.go(0)
    }
}