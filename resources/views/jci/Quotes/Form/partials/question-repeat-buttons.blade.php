<button 
	id="{{isset($id) ? $id : ''}}"
	class='btn btn-dark-orange btn-repeat' 
	data-repeat="{{$repeat}}"
	data-repeat-container="{{$container}}"
>{{$text}} <i class='cp-spinner cp-eclipse '></i>
</button>