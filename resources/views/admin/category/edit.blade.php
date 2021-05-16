@extends('admin.layout.master')

@section('title', $pageMeta['pageName'])

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{isset($categoryArr['id']) ? 'Edit' : 'Add'}} Category</h3>
    </div>
    <div class="panel-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id='addEditCategoryForm' method='POST'  action="{{url('/admin/category/save')}}" class="form-horizontal" >
            {{ csrf_field() }}
            <input id="id" type="hidden" class="form-control" name="id" value="{{isset($categoryArr['id']) ? $categoryArr['id'] : ''}}" autofocus>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="category_name">Category Name</label>
                <div class="col-sm-5">
                    <input id="category_name" type="text" placeholder="Category Name" class="form-control" name="category_name" value="{{isset($categoryArr['category_name']) ? $categoryArr['category_name'] : old('name')}}" autofocus>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-4">
                    <button type="submit" class="btn btn-primary" name="signup" value="Sign up">{{isset($categoryArr['id']) ? 'Edit' : 'Add'}}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('pageJavascript')
<script type='text/javascript'>
    $(document).ready(function () {
        $("#addEditCategoryForm").validate({
            rules: {
                category_name: "required"
            },
            messages: {
                name: "Please enter category name",
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `help-block` class to the error element
                error.addClass("help-block");

                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.insertAfter(element);
                }

                // Add the span element, if doesn't exists, and apply the icon classes to it.
                if (!element.next("span")[ 0 ]) {
                    $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
                    $(".glyphicon").css("margin-right", "15px");
                }

            },
            success: function (label, element) {
                // Add the span element, if doesn't exists, and apply the icon classes to it.
                if (!$(element).next("span")[ 0 ]) {
                    $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
                    $(".glyphicon").css("margin-right", "15px");
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
                $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
                $(".glyphicon").css("margin-right", "15px");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
                $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
                $(".glyphicon").css("margin-right", "15px");
            }
        });

    });
</script>
@endsection