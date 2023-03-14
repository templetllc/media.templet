@extends('layouts.pages')

@section('content')
<div class="containter">
      
      <form action="{{ route('canvas') }}" method="POST" autocomplete="off">
         {{ csrf_field() }}
      

         <textarea name="data" id="" cols="30" rows="10"></textarea>
         <input type="submit" value="Save"></input>

      </form>

</div>


   
@endsection