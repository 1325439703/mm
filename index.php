<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width,user-scalable=no" name="viewport">
    <title>加密单页更多资源公众号：狗凯之家</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="dropify/css/dropify.min.css" rel="stylesheet">
    <link href="css/loading.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <div class="container">
	<form id='myForm' action="check.php">
	<div style="height:50px; line-height:50px; text-align:center;">
	 
                                        <input value="1" type="radio"  id="customRadio1" name="storage"
                                               class="custom-control-input">
                                        <label class="custom-control-label" for="customRadio1">扩展[安全等级99%]</label>
                                   
                                  <!--无扩展加密-->
                                 
                                        <input value="2" type="radio" checked id="customRadio3" name="storage"
                                               class="custom-control-input">
                                        <label class="custom-control-label" for="customRadio3">无扩展[安全等级97%]</label>
                                   
                            
                                   
              </div>              
								
        <ul class="step">
            <li class='active'>
                <span class='xulie'>1</span>
                <span class='text line'>上传资料</span>
            </li>
            <li>
                <span class='xulie'>2</span>
                <span class='text line'>正在加密</span>
            </li>
            <li>
                <span class='xulie'>3</span>
                <span class='text'>查看结果</span>
            </li>
        </ul>
        <div style="text-align: center;">
            
                <div class='c-step1'>
                    <input type="file" name="file" data-max-file-size="3M" data-allowed-file-extensions="zip" class="dropify">
                    <div class="tishi"></div>
                    <button type='submit' class="submit">确认开始加密</button>
                </div>
            </form>
            <div class='loading'>
                <ul id='spinners'>
                    <li class="active" data-id="1">
                        <div id="preloader_1">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </li>
                </ul>
                <div class="loading-text">正在加载页面</div>
            </div>
            <div class="finished">
                <div class="result">
                    <div class="micon"></div>
                    <div class="result-text">加密成功</div>
                </div>
                <a href='###' target="_blank" class="submit download">点击下载加密包</a>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js?v=2.1.4"></script>
    <script src="dropify/js/dropify.js"></script>
    <script>
        $('.dropify').dropify({
            messages: {
                'default': '请把需要加密的压缩包点击或拖拽到这里',
                'replace': '请把需要加密的压缩包点击或拖拽这里来替换',
                'remove': '移除文件',
                'error': '请上传zip格式的压缩包且体积不能超过3M'
            },
            error: {
                fileExtension: '加密压缩包格式只能是 ({{ value }})',
                fileSize: '压缩包体积不能超过({{ value }})',
            }
        });
        var queryTimer;
        var queryTime;
        $(function(){
            setTimeout(function(){
                $(".loading").hide();
                $(".c-step1").show();
            },500);
        });
        $("#myForm").submit(function () {
		
		var storage=$('input[name="storage"]:checked').val();
		

             if (!$("input[name='file']").val()) {
                $(".tishi").text('请上传需要加密的压缩包').show();
                return false;
            } 

            var files = $("input[name='file']")[0].files[0] //单个
            var data = new FormData();
            data.append('file', files);
            data.append('step',1);
			data.append('storage',storage);
            $(".c-step1").hide();
            $(".loading-text").text('正在上传数据');
            $(".loading").show();
            $.ajax({ // $.post，告辞
                type: 'post',
                contentType: false,
                processData: false,
                url: $(this).attr('action'),
                data: data,
                dataType: 'json',
                success: function (res) {
                    if (res.code == 0) {
                        $(".step li.active").removeClass('active').addClass('finish').find('.xulie').text('✓');
                        $(".step li:eq(1)").addClass('active');
                        $(".loading-text").text('正在队列中');
                        queryTimer && clearInterval(queryTimer);
                        queryTimer = setInterval(function () {
                            query_status(res.data.order_no);
                            queryTime++;
                        }, 2500);
                    } else {
                        $(".c-step1").show();
                        $(".loading").hide();
                        $(".tishi").text(res.message).show();
                    }
                },
            }).fail(function (e) {
                $(".c-step1").show();
                $(".loading").hide();
                if (e.status == 413) {
                    $(".tishi").text('上传压缩包过大').show();
                } else {
                    $(".tishi").text('提交信息失败').show();
                }
            })

            return false;
        })

        function query_status(order_no) {
            $.post('check.php', { order_no: order_no, step: 2 }, function (data) {
                if (data.code == 0) {
                    var status = data.data.status;
                    if (status == 2 || status == 0) {
                        queryTimer && clearInterval(queryTimer);

                        if ($(".step li.active").index() == 1) {
                            $(".step li.active").removeClass('active').addClass('finish').find('.xulie').text('✓');
                            $(".step li:eq(2)").addClass('active');
                            //.find('.xulie').text('✓');
                        };
                        $(".loading").hide();
                        $(".loading-text").text('加密完成');

                        if (status == 0) {
                            $(".finished").find('.micon').addClass('error');
                            $(".finished").find('.result-text').text('加密失败');
                        }

                        if (status == 2) {
                            $(".finished").find('.micon').addClass('success');
                            $(".finished").find('.result-text').text('村少博客提示：恭喜，已经加密成功！');
                            $(".submit.download").attr('href', data.data.url).show();
                        }

                        $(".finished").show();

                    } else {
                        $(".loading-text").text(data.message);
                    }
                }else{
                    queryTimer && clearInterval(queryTimer);
                    $(".loading").hide();
                    $(".finished").find('.micon').addClass('error');
                    $(".finished").find('.result-text').text('加密失败');
                    $(".finished").show();
                }
            }, 'json').fail(function (data) {
            })
        }

    </script>
</body>

</html>