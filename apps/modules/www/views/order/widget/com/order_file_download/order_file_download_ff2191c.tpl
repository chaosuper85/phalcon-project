{%$file = $data.data.order_files%}
<!-- 下载文件开始 -->
<div class="clearfix order-files">
	<div class="title">
		<h3>货代下单文件</h3>
	</div>
	<div class="files-content">
		<ul>
			<li>
				<a id="download-one" href="{%$file.tixiang%}" target="_blank">查看提箱单</a>
			</li>
			<li>
				<a id="download-two" href="{%$file.chanzhuang%}" target="_blank">查看装货单</a>
			</li>
		</ul>
		<a href="/order/downLoadAll?orderid={%$data.data.order_fregiht_id%}" target="_blank" id="download-all">全部下载</a>
	</div>
</div>
<!-- 下载文件结束 -->