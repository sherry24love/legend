@foreach( $posts as $v )
    <li class="aui-list-item aui-list-item-arrow">
        <div class="aui-media-list-item-inner">
            <div class="aui-list-item-inner">
            <a href="{{route('wap.show' , ['id' => $v->id ])}}">
                <div class="aui-list-item-text">
                    <div class="aui-list-item-title">{{$v->title}}</div>
                    <div class="aui-list-item-right">{{date("y/m/d" , strtotime( $v->updated_at ) )}}</div>
                </div>
                <div class="aui-list-item-text aui-ellipsis-2">
                    {{$v->description}}
                </div>
            </a>
            </div>
        </div>
    </li>
@endforeach