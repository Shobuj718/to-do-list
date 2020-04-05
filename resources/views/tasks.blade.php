@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card card-new-task">

                    <div class="card-body">
                        <form id="taskSubmit" name="taskSubmit" action="javascript:void(0)">
                            @csrf
                            <div class="form-group">
                                <input id="title" name="title" type="text" maxlength="255" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" autocomplete="off" />
                                @if ($errors->has('title'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                                @endif
                            </div>
                            <button style="display: none;" type="submit" onclick="addTask()"></button>

                        </form>
                    </div>
                </div>
                <div class="card">

                    <div class="card-body taskshow">
                        @foreach ($tasks as $task)

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="task_check" name="task_check" <?php if($task->is_complete == 1) echo 'checked'; ?> onclick="taskUpdate('{{ $task->id }}')">  
                                <label class="form-check-label" for="taskfor">
                                    @if ($task->is_complete)
                                        <s>{{ $task->title }}</s>
                                    @else
                                        {{ $task->title }}
                                    @endif
                                </label>
                            </div>

                        @endforeach
                        <hr>
                        <span style="margin-right: 10px; background-color: #f7efefe3;">{{ count($incomplete_task) }} items left</span>
                        <button type="submit" class="btn btn-default" onclick="allTask()">All</button>
                        <button type="submit" class="btn btn-default" onclick="activeTask()">Active</button>
                        <button type="submit" class="btn btn-default" onclick="completedTask()">Completed</button>
                        <button type="submit" class="btn btn-default" onclick="clearTask()">Clear Completed</button>
                                              
                    </div>

                    

                </div>
            </div>
        </div>
    </div>
@endsection

<script type="text/javascript" src="https://chat.miyn.app/assets/js/jquery.js"></script>


<script type="text/javascript">
   $(function () {
    $(".form-check-label").dblclick(function (e) {

        e.stopPropagation();
        var currentEle = $(this);
        var value = $(this).html();
        //alert(value);
        updateVal(currentEle, value);
    });
});

function updateVal(currentEle, value) {
    
    $(currentEle).html('<input class="taskvl" type="text" value="' + value + '" />');
    $(".taskvl").focus();
    $(".taskvl").keyup(function (event) {
        if (event.keyCode == 13) {
            $(currentEle).html($(".taskvl").val().trim());
            //alert('dssd');
        }
    });

    $(document).click(function () {
            $(currentEle).html($(".taskvl").val().trim());
            //alert(value);

    });
}
</script>

<script type="text/javascript">

    function addTask(){
        //alert('calll');
        var title = $('#title').val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        //return false;

        $.ajax({
            url:'/tasks',
            type: 'POST',
            data:{_token:CSRF_TOKEN, title:title},
            dataType:'json',
            success:function(data){
                console.log(data);
                $('#title').val('');
                $.ajax({
                    url:'/tasks/all-task',
                    type:'GET',
                    data:{_token:CSRF_TOKEN},
                    dataType:'json',
                    success:function(data){
                        console.log(data);
                        var matches = 0;
                        var html = "";
                        for (var task in data) {                            
                            console.log('task title');
                            console.log(data[task]['title']);
                            console.log(data[task]['is_complete']);

                            //for(var val in data[task]){
                                
                                html += '<div class="form-check">';
                                    html += '<input type="checkbox" class="form-check-input" id="task_check" name="task_check"';
                                    if(data[task]['is_complete'] == 1){
                                        html +=' checked ';
                                    }
                                    html += 'onclick="taskUpdate(';
                                    html += data[task]['id'];
                                    html +=')">';
                                    html += '<label class="form-check-label" for="taskfor">';
                                    if(data[task]['is_complete'] == 1){
                                        html +='<s>';
                                        html += data[task]['title'];
                                        html +='</s>';
                                    }else{
                                        
                                            matches++;
                                        
                                        html += data[task]['title'];
                                    }
                                    
                                    html += '</label>';
                                html += '</div>';

                                
                                //$(".form-check").html(html);  
                            //}
                            
                            
                        }
                       
                        html += '<span style="margin-right: 10px; background-color: #f7efefe3;">';
                        html += matches + ' items left';
                        html += '</span>';

                        html += '<button type="submit" class="btn btn-default" onclick="allTask()">All</button>';

                        html += '<button type="submit" class="btn btn-default" onclick="activeTask(';
                        //html += data[task]['id'];
                        html += ')">Active</button>';

                        html += '<button type="submit" class="btn btn-default" onclick="completedTask()">Completed</button>';

                        html += '<button type="submit" class="btn btn-default" onclick="clearTask()">Clear Completed</button>';


                         $(".taskshow").html(html);

                        
                    }
               });

            }
        });

    }


    function completedTask(){
        //alert('calll');
        var title = $('#title').val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        
        $.ajax({
            url:'/completed-task',
            type: 'POST',
            data:{_token:CSRF_TOKEN},
            dataType:'json',
            success:function(data){
                console.log(data);
                var matches = 0;
                var html = "";
                for (var task in data) {                            
                    console.log('task titless');
                    console.log(data[task]['title']);
                    console.log(data[task]['is_complete']);

                    //for(var val in data[task]){
                        
                        html += '<div class="form-check">';
                            html += '<input type="checkbox" class="form-check-input" id="task_check" name="task_check"';
                            if(data[task]['is_complete'] == 1){
                                html +=' checked ';
                            }
                            html += 'onclick="taskUpdate(';
                            html += data[task]['id'];
                            html +=')">';
                            html += '<label class="form-check-label" for="taskfor">';
                            if(data[task]['is_complete'] == 1){

                                
                                html +='<s>';
                                html += data[task]['title'];
                                html +='</s>';
                            }else{
                                
                                    
                                
                                html += data[task]['title'];
                            }
                            if(data[task]['is_complete'] == 0){
                                    matches++;
                                }
                            
                            html += '</label>';
                        html += '</div>';

                        
                        //$(".form-check").html(html);  
                    //}
                    
                    
                }
               
                html += '<span style="margin-right: 10px; background-color: #f7efefe3;">';
                html += matches + ' items left';
                html += '</span>';

                html += '<button type="submit" class="btn btn-default" onclick="allTask()">All</button>';

                html += '<button type="submit" class="btn btn-default" onclick="activeTask(';
                //html += data[task]['id'];
                html += ')">Active</button>';

                html += '<button type="submit" class="btn btn-default" onclick="completedTask()">Completed</button>';

                html += '<button type="submit" class="btn btn-default" onclick="clearTask()">Clear Completed</button>';


                 $(".taskshow").html(html);


            }
        });

    }
    function allTask(){
        //alert('calll all');
        var title = $('#title').val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        
        $.ajax({
            url:'/all-task',
            type: 'POST',
            data:{_token:CSRF_TOKEN},
            dataType:'json',
            success:function(data){
                console.log(data);
                var matches = 0;
                var html = "";
                for (var task in data) {                            
                    console.log('task titless');
                    console.log(data[task]['title']);
                    console.log(data[task]['is_complete']);

                    //for(var val in data[task]){
                        
                        html += '<div class="form-check">';
                            html += '<input type="checkbox" class="form-check-input" id="task_check" name="task_check"';
                            if(data[task]['is_complete'] == 1){
                                html +=' checked ';
                            }
                            html += 'onclick="taskUpdate(';
                            html += data[task]['id'];
                            html +=')">';
                            html += '<label class="form-check-label" for="taskfor">';
                            
                                
                                    matches++;
                                
                                html += data[task]['title'];
                            
                            
                            html += '</label>';
                        html += '</div>';

                        
                        //$(".form-check").html(html);  
                    //}
                    
                    
                }
               
                html += '<span style="margin-right: 10px; background-color: #f7efefe3;">';
                html += matches + ' items left';
                html += '</span>';

                html += '<button type="submit" class="btn btn-default" onclick="allTask()">All</button>';

                html += '<button type="submit" class="btn btn-default" onclick="activeTask(';
                //html += data[task]['id'];
                html += ')">Active</button>';

                html += '<button type="submit" class="btn btn-default" onclick="completedTask()">Completed</button>';

                html += '<button type="submit" class="btn btn-default" onclick="clearTask()">Clear Completed</button>';


                 $(".taskshow").html(html);


            }
        });

    }
    function clearTask(){
        //alert('calll');
        var title = $('#title').val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        
        $.ajax({
            url:'/clear-task',
            type: 'POST',
            data:{_token:CSRF_TOKEN},
            dataType:'json',
            success:function(data){
                console.log(data);
                var matches = 0;
                var html = "";
               
               
                html += '<span style="margin-right: 10px; background-color: #f7efefe3;">';
                html += matches + ' items left';
                html += '</span>';

                html += '<button type="submit" class="btn btn-default" onclick="allTask()">All</button>';

                html += '<button type="submit" class="btn btn-default" onclick="activeTask(';
                //html += data[task]['id'];
                html += ')">Active</button>';

                html += '<button type="submit" class="btn btn-default" onclick="completedTask()">Completed</button>';

                html += '<button type="submit" class="btn btn-default" onclick="clearTask()">Clear Completed</button>';


                 $(".taskshow").html(html);

                /*var html = "";
                html += '<input type="checkbox" id="task_check" name="task_check" checked="checked"  class="form-check-input"> <label for="taskfor" class="form-check-label"><s>to do list 55</s></label>';   
                $(".form-check").html(html);*/

            }
        });

    }
    function activeTask(){
        //alert('value');
        //var task_id = value;
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        
        $.ajax({
            url:'/active-tasks',
            type: 'POST',
            data:{},
            dataType:'json',
            success:function(data){
                console.log(data);
                var matches = 0;
                var html = "";
                for (var task in data) {                            
                    console.log('task titless');
                    console.log(data[task]['title']);
                    console.log(data[task]['is_complete']);

                    //for(var val in data[task]){
                        
                        html += '<div class="form-check">';
                            html += '<input type="checkbox" class="form-check-input" id="task_check" name="task_check"';
                            if(data[task]['is_complete'] == 1){
                                html +=' checked ';
                            }
                            html += 'onclick="taskUpdate(';
                            html += data[task]['id'];
                            html +=')">';
                            html += '<label class="form-check-label" for="taskfor">';
                            if(data[task]['is_complete'] == 1){
                                html +='<s>';
                                html += data[task]['title'];
                                html +='</s>';
                            }else{
                                
                                    matches++;
                                
                                html += data[task]['title'];
                            }
                            
                            html += '</label>';
                        html += '</div>';

                        
                        //$(".form-check").html(html);  
                    //}
                    
                    
                }
               
                html += '<span style="margin-right: 10px; background-color: #f7efefe3;">';
                html += matches + ' items left';
                html += '</span>';

                html += '<button type="submit" class="btn btn-default" onclick="allTask()">All</button>';

                html += '<button type="submit" class="btn btn-default" onclick="activeTask(';
                //html += data[task]['id'];
                html += ')">Active</button>';

                html += '<button type="submit" class="btn btn-default" onclick="completedTask()">Completed</button>';

                html += '<button type="submit" class="btn btn-default" onclick="clearTask()">Clear Completed</button>';


                 $(".taskshow").html(html);

                /*var html = "";
                html += '<input type="checkbox" id="task_check" name="task_check" checked="checked"  class="form-check-input"> <label for="taskfor" class="form-check-label"><s>to do list 55</s></label>';   
                $(".form-check").html(html);*/

            }
        });

    }
    
    function taskUpdate(value){
        //alert(value);
        var task_id = value;
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        
        //return false;
        
        $.ajax({
            url:'/tasks/update',
            type: 'POST',
            //dataType:'json',
            //contentType: 'json',
            data:{_token:CSRF_TOKEN, task_id:task_id},
            dataType:'json',
            success:function(data){
                console.log(data);

                /*var html = "";
                html += '<input type="checkbox" id="task_check" name="task_check" checked="checked"  class="form-check-input"> <label for="taskfor" class="form-check-label"><s>to do list 55</s></label>';   
                $(".form-check").html(html);*/

               $.ajax({
                    url:'/tasks/all-task',
                    type:'GET',
                    data:{_token:CSRF_TOKEN},
                    dataType:'json',
                    success:function(data){
                        console.log(data);
                        var matches = 0;
                        var html = "";
                        for (var task in data) {                            
                            console.log('task title');
                            console.log(data[task]['title']);
                            console.log(data[task]['is_complete']);

                            //for(var val in data[task]){
                                
                                html += '<div class="form-check">';
                                    html += '<input type="checkbox" class="form-check-input" id="task_check" name="task_check"';
                                    if(data[task]['is_complete'] == 1){
                                        html +=' checked ';
                                    }
                                    html += 'onclick="taskUpdate(';
                                    html += data[task]['id'];
                                    html +=')">';
                                    html += '<label class="form-check-label" for="taskfor">';
                                    if(data[task]['is_complete'] == 1){
                                        html +='<s>';
                                        html += data[task]['title'];
                                        html +='</s>';
                                    }else{
                                        
                                            matches++;
                                        
                                        html += data[task]['title'];
                                    }
                                    
                                    html += '</label>';
                                html += '</div>';

                                
                                //$(".form-check").html(html);  
                            //}
                            
                            
                        }
                       
                        html += '<span style="margin-right: 10px; background-color: #f7efefe3;">';
                        html += matches + ' items left';
                        html += '</span>';

                        html += '<button type="submit" class="btn btn-default" onclick="allTask()">All</button>';

                        html += '<button type="submit" class="btn btn-default" onclick="activeTask(';
                        //html += data[task]['id'];
                        html += ')">Active</button>';

                        html += '<button type="submit" class="btn btn-default" onclick="completedTask()">Completed</button>';

                        html += '<button type="submit" class="btn btn-default" onclick="clearTask()">Clear Completed</button>';


                         $(".taskshow").html(html);

                        /*var html = "";
                        html += '<input type="checkbox" id="task_check" name="task_check" checked="checked"  class="form-check-input"> <label for="taskfor" class="form-check-label"><s>to do list 55</s></label>';   
                        $(".form-check").html(html);*/

                    }
               });
            }
        });
        
    }

</script>
