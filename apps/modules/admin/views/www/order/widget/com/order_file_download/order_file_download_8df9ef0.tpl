{%$file = $data.data.order_files%}
<!-- 下载文件开始 -->
<div class="clearfix order-files">
	<div class="title">
		<h3>下单文件</h3>
	</div>
	<div class="files-content">
		<ul>
			<li>
				<a id="download-one" href="{%$file.tixiang%}">查看提箱单</a>
			</li>
			<li>
				<a id="download-two" href="{%$file.chanzhuang%}">查看产装联系单</a>
			</li>
		</ul>
		<a href="javascript:;" id="download-all">全部下载</a>
	</div>
</div>
<!-- 下载文件结束 -->
{%script type="text/javascript"%}

	require('www/order/widget/com/order_file_download/order_file_download').init();
{%/script%}