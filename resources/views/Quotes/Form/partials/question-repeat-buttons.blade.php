<button 
	id="{{isset($id) ? $id : ''}}"
	class='btn btn-info btn-repeat' 
	data-repeat="{{$repeat}}"
	data-repeat-container="{{$container}}"
>{{$text}} <i class='cp-spinner cp-eclipse '></i>
</button>