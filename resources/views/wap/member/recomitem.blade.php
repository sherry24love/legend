@foreach( $list->items() as $val )
<li class="aui-list-item">
    <div class="aui-media-list-item-inner">
        <div class="aui-list-item-inner">
            <div class="aui-list-item-text">
                <div class="aui-list-item-title">
                    用户名称：{{substr_replace($val->name , '****' , 4 , 4 )}}
                </div>
            </div>
            <div class="aui-list-item-text">
                注册时间：{{str_limit( $val->created_at , 10 , '' )}}
            </div>            
        </div>
    </div>
</li>
@endforeach