{%widget name="user/widget/header_content/header_content.tpl" title="车辆管理" class="car-manage"%}

<div id="car-manage" class="user-content">
    {%if count($data.data.data.result) !== 0%}
        <table class="content">
            <thead>
                <tr>
                    <th width="20%">司机姓名</th>
                    <th width="25%">司机联系电话</th>
                    <th width="25%">车牌号(牵引车)</th>
                    <th width="30%">行驶证号</th>
                </tr>
            </thead>
            <tbody>
                {%foreach $data.data.data.result as $item%}
                    <tr class="item-content">
                        <td>{%$item.driver_name%}</td>
                        <td>{%$item.contactNumber%}</td>
                        <td>{%$item.car_number%}</td>
                        <td>{%$item.drive_number%}</td>
                    </tr>
                {%/foreach%}
            </tbody>
        </table>
        {%if $data.data.data.pageCount >= 2%}
            {%widget name="common/widget/pager/pager.tpl" total=$data.data.data.pageCount%}
        {%/if%}
    {%else%}
        <div class="no_car">
            <div>
                <p>暂无司机信息，可联系客服添加车队司机</p>
                <p class="tel-text">客服电话<a href="tel:400-8666">400-8666</a></p>
            </div>
        </div>
    {%/if%}
</div>