<div id="error">
    <div class="wrapper">
        <div class="error-content clearfix">
            <div class="left err-img">
                <img src="/static/index/static/image/error.png" alt="error" class="error-img"/>
            </div>
            <div class="left err-info">
                {%if $data && $data.data && $data.data.error_msg%}
                  <div class="error-info">{%$data.data.error_msg%}</div>
                {%else%}
                  <div class="error-info">您访问的页面找不到。</div>
                {%/if%}
                <div class="error-connection">如有疑问请联系客服电话<strong>400-969-6790</strong></div>
            </div>
        </div>
    </div>
</div>