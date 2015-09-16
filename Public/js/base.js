//<div class="more-box"><a href="#">自动加载.....</a></div>
$(document).ready(function () {
    $("#login").click(function () {
        openLoginBox();
    })

    $("#personal_login").click(function () {
        $("#company_login").removeClass("active");
        $("#personal_login").addClass('active');
        $("#personal_login_html").show();
        $("#company_login_html").hide();

    });
    $("#company_login").click(function () {
        $("#personal_login").removeClass("active");
        $("#company_login").addClass('active');
        $("#company_login_html").show();
        $("#personal_login_html").hide();
    });

    $("#goToRegister").click(function () {
        location.href = "/user/regUser";
    });

    $('#login_form').keypress(function (e) {
        if (e.which == 13) {
            ajaxLogin('login_form');
        }
    });

    $('#login_form_company').keypress(function (e) {
        if (e.which == 13) {
            ajaxLogin('login_form_company');
        }
    });
});

function openLoginBox() {
    $("#dialog_login").dialog({
        title: "用户登陆",
        modal: true,
        /*open: function (event, ui) {
         $(".ui-widget-overlay").css({
         opacity: 0.5,
         filter: "Alpha(Opacity=50)",
         backgroundColor: "black"
         });
         },*/
        minWidth: 430,
        minHeight: 330
    });
}

function ajaxLogin(fid)
{
    //$('#login_err').html('正在为您登录...');
    $('#' + fid + ' div.error').html('正在为您登录...');
    var options = {
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.status == 0) {
                window.location.href = data.redirect;
            } else {
                $('#' + fid + ' div.error').html(data.message);
            }
        },
        error: function () {
            $('#' + fid + ' div.error').html('登录失败了!');
        }
    };
    $('#' + fid).ajaxSubmit(options);
    return false;
}

function changePwd(f)
{
    $('#old_password_err').html('');
    $('#passwordVerify_err').html('');
    $('#password_err').html('');
    var options = {
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.status == 0) {
                showMessage('系统提示', '密码修改成功');
                reset('changePwdForm');
            } else {
                switch (data.status) {
                    case 1:
                        $('#old_password_err').html(data.message);
                        break;
                    case 2:
                        $('#passwordVerify_err').html(data.message);
                        break;
                    case 3:
                        $('#password_err').html(data.message);
                        break;
                }
//                showMessage('出错了:(', data.message);
            }
        },
    };
    $('#changePwdForm').ajaxSubmit(options);
    return false;
}

function validateLogin(formData, jqForm, options)
{
    var username = jqForm[0]['username'].value;
    var password = jqForm[0]['password'].value;
    if ($.trim(username).length == 0) {
        $('#error_message').html("请输入手机号或者企业名");
        return false;
    }
    if ($.trim(password).length < 6) {
        $('#error_message').html("密码长度至少6位");
        return false;
    }
}

function login()
{
    $('#error_message').html('');
    var options = {
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.result) {
                loadNote(lid);
            }
        },
        beforeSubmit: validateLogin
    };
    $('#login_form').ajaxSubmit(options);
    return false;
}

function showMessage(title, content, okFunc, cancelFunc, options)
{
    var okBtn = '确定';
    var cancelBtn = '取消';
    var width = 200;
    if (typeof (options) != 'undefined') {
        if (typeof (options['okBtn']) != 'undefined') {
            okBtn = options['okBtn'];
        }
        if (typeof (options['cancelBtn']) != 'undefined') {
            cancelBtn = options['cancelBtn'];
        }
        if (typeof (options['width']) != 'undefined') {
            width = options['width'];
        }
    }

    var btns = {};
    btns[okBtn] = function () {
        $(this).dialog("close");
        if (typeof (okFunc) != 'undefined') {
            okFunc();
        }
    };

    if (cancelBtn !== null) {
        btns[cancelBtn] = function () {
            $(this).dialog("close");
            if (typeof (cancelFunc) != 'undefined') {
                cancelFunc();
            }
        };
    }

    $('<div></div>').appendTo('body').html(
            '<div style="width: ' + width + 'px; text-align: center"><h6>' + content + '</h6></div>').dialog({
        modal: true,
        title: title,
        zIndex: 10000,
        autoOpen: true,
        width: 'auto',
        resizable: false,
        buttons: btns,
        close: function (event, ui) {
            $(this).remove();
        }
    });
}


function reset(f) {
    $('#' + f).find("input[type=text], input[type=password], textarea").val("");
    $('#' + f).find("input[type=checkbox]").removeAttr('checked');
    $('#' + f).removeAttr('checked').removeAttr('selected');
    $('#' + f).find('select option:selected').removeAttr('selected');
}

function dismissDialog(id) {
    $("#" + id).dialog('close');
}

function reloadPage() {
    document.location.reload();
}

function strlen(str) {  //在IE8 兼容性模式下 不会报错  
    var s = 0;
    for (var i = 0; i < str.length; i++) {
        if (str.charAt(i).match(/[\u0391-\uFFE5]/)) {
            s += 2;
        } else {
            s++;
        }
    }
    return s;
}

function myChart(month, lastMonth, target) {
    var myData = new Array(['当月实际值%', month], ['上月实际值%', lastMonth], ['当月目标值%', target]);
    var myChart = new JSChart('graph', 'line');
    myChart.setDataArray(myData);
    myChart.setTitle('');
    myChart.setTitleColor('#8E8E8E');
    myChart.setTitleFontSize(14);
    myChart.setAxisNameX('');
    myChart.setAxisNameY('');
    myChart.setAxisColor('#8420CA');
    myChart.setAxisValuesColor('#949494');
    myChart.setAxisPaddingLeft(40);
    myChart.setAxisPaddingRight(40);
    myChart.setAxisPaddingTop(30);
    myChart.setAxisPaddingBottom(40);
    myChart.setAxisValuesDecimals(2);
    myChart.setAxisValuesNumberX(5);
    myChart.setShowXValues(false);
    myChart.setGridColor('#C5A2DE');
    myChart.setLineColor('#BBBBBB');
    myChart.setLineWidth(2);
    myChart.setFlagColor('#9D12FD');
    myChart.setFlagRadius(5);
    myChart.setTooltip(['当月实际值%', month]);
    myChart.setTooltip(['上月实际值%', lastMonth]);
    myChart.setTooltip(['当月目标值%', target]);
    myChart.setLabelX(['当月实际值%', '当月实际值%']);
    myChart.setLabelX(['上月实际值%', '上月实际值%']);
    myChart.setLabelX(['当月目标值%', '当月目标值%']);
    myChart.setSize(320, 341);
    myChart.draw();
}

function selectmonth(type, obj, url, where) {
    if (type) {
        $.post(url, {'type': type, where: where}, function (data) {
            var json = $.parseJSON(data);
            if (json) {
                myChart(parseFloat(json.month), parseFloat(json.lastMonth), parseFloat(json.target));
                $(obj).siblings('a').removeClass('on');
                $(obj).addClass('on');
            } else {
                alert('抱歉，该月没有添加数据');
            }

        });
    }
}


function higthChatsColumn(qianyuji, houyuji, qiancash, houcash) {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: '',
            margin: 50
        },
        credits: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        xAxis: {
            categories: [
                '预计利润',
                '预计经营现金流'
            ],
            labels: {
                rotation: -45,
                align: 'right',
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'center',
            verticalAlign: 'top',
            x: 0,
            y: 0,
            floating: true,
            borderWidth: 1,
            backgroundColor: '#FFFFFF',
            shadow: true
        },
        yAxis: {
            title: {
                text: '金额 (元)'
            }
        },
        tooltip: {
            pointFormat: '金额: <b>{point.y:.1f} 元</b>',
        },
        series: [{
                name: '变化前',
                data: [qianyuji, qiancash],
                dataLabels: {
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    x: 4,
                    y: 10,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 3px black'
                    }
                }
            }, {
                name: '变化后',
                data: [houyuji, houcash],
                dataLabels: {
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    x: 4,
                    y: 10,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 3px black'
                    }
                }
            }]
    });
}

function selectmonth2(type, obj, url) {
    if (type) {
        $.post(url, {'type': type}, function (data) {
            var json = $.parseJSON(data);
            if (json) {
                higthChatsColumn(parseFloat(json.qianyuji), parseFloat(json.houyuji), parseFloat(json.qiancash), parseFloat(json.houcash));
            }

            $('#change').val(json.change);
            $(obj).siblings('a').removeClass('on');
            $(obj).addClass('on');

        });
    }
}

