<div id="order-new">
    <div class="breadcrumb">
        <ol>
            <li><a href="/">发起订单</a></li>
            <li>></li>
            <li class="active">发起出口产装订单</li>
        </ol>
    </div>
	<div class="order-number clearfix">
		<span class="number-left"></span>
		<span class="number-right">运单号{%$data.data.yudan_code%}</span>
	</div>
	<div class="carteam_freight mod">
        <div class="title">
            <h3>承运方</h3>
        </div>
        <div class="content">
            <div class="item clearfix">
                <div class="item-name">
                    <label for="carteam_name">
                        <span class="name">承运方</span>
                        <span class="icon-require">*</span>
                    </label>
                </div>
                <div class="item-content">
                    <div id="carteam-selector"></div>
                </div>
                <div class="item-message clearfix">
                    <div class="error-message hidden"><i class="icon-warn"></i>承运方必填</div>
                    <div class="right-message hidden"><i class="icon-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="chanzhuang mod">
        <div class="title">
            <h3>产装联系单</h3>
        </div>
        <div class="content">
            <div class="item clearfix">
                <div class="item-name">
                    <label for="chanzhuang_name">
                        <span class="name">上传产装联系单</span>
                        <span class="icon-require">*</span>
                    </label>
                </div>
                <div class="item-content clearfix">
                     <div class="upload-show chanzhuang-show hidden clearfix">
                        <div class="show-box">
                            <span class="filename"></span>
                        </div>
                        <a href="javascript:;" id="chanzhuang-delete" class="upload-delete" title="删除">删除</a>
                    </div>
                    <div class="btn-wrap">
                        <a href="javascript:;" class="user-btn chanzhuang_btn">选择文件上传</a>
                        <input type="file" name="chanzhuang" type="file" id="upload-chanzhuang" value="" accept=".doc,.docx,.pdf"/>
                    </div>
                    <div class="info-wrap">
                        <p>支持.doc.docx或.pdf格式文件,不超过2M</p>
                    </div>
                </div>
                <div class="item-message clearfix">
                    <div class="error-message hidden"><i class="icon-warn"></i>产装联系单必须上传</div>
                    <div class="right-message hidden"><i class="icon-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="tixiang mod">
        <div class="title">
            <h3>提箱联系单</h3>
        </div>
        <div class="content ">
            <div class="item clearfix">
                <div class="item-name">
                    <label for="freight_name">
                        <span class="name">提单号</span>
                        <span class="icon-require">*</span>
                    </label>
                </div>
                <div class="item-content">
                    <input type="text" value="" id="tidan_name" class="" name="tidan_name" />
                </div>
                <div class="item-message clearfix">
                    <div class="error-message hidden"><i class="icon-warn"></i>提单号必填</div>
                    <div class="china-notice hidden"><i class="icon-warn"></i>只能由数字和英文字母组成</div>
                    <div class="right-message hidden"><i class="icon-right"></i></div>
                </div>
            </div>
            <div class="item clearfix">
                <div class="item-name">
                    <label for="tixiang_name">
                        <span class="name">上传提箱联系单</span>
                        <span class="icon-require">*</span>
                    </label>
                </div>
                <div class="item-content">
                    <div class="upload-show tixiang-show hidden clearfix">
                        <div class="show-box">
                            <span class="filename"></span>
                        </div>
                        <a href="javascript:;" id="tixiang-delete" class="upload-delete" title="删除">删除</a>
                    </div>
                    <div class="btn-wrap">
                        <a href="javascript:;" class="user-btn tixiang_btn">选择文件上传</a>
                        <input type="file" name="tidan" type="file" id="upload-tixiang" value="" accept=".doc,.docx,.pdf"/>
                    </div>
                    <div class="info-wrap">
                        <p>支持.doc.docx或.pdf格式文件,不超过2M</p>
                    </div>
                </div>
                <div class="item-message clearfix">
                    <div class="error-message hidden"><i class="icon-warn"></i>产装联系单必须上传</div>
                    <div class="right-message hidden"><i class="icon-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    <button class="order-creat">创建订单</button>
</div>

{%script type="text/javascript"%}
        console.log({%json_encode($data)%})
        console.log({%json_encode($data.data.carteamList)%})
	require('order/widget/view/order_new/order_new').init({%json_encode($data.data.carteamList)%},{%json_encode($data.data.yudan_code)%});
{%/script%}