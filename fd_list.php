<?php
// 添加高级搜索和过滤功能
?>
<div class="advanced-filters mb-4">
    <div class="row g-3">
        <div class="col-md-3">
            <input type="date" class="form-control" placeholder="开始日期从">
        </div>
        <div class="col-md-3">
            <input type="date" class="form-control" placeholder="到">
        </div>
        <div class="col-md-3">
            <select class="form-select">
                <option value="">所有银行</option>
                <!-- 银行选项 -->
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select">
                <option value="">所有状态</option>
                <option value="active">活跃</option>
                <option value="matured">已到期</option>
            </select>
        </div>
    </div>
</div>