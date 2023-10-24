<script>
    let gridChild = [false, false, false, false, false]
    let status_arr_meta = @json($arr_meta);
    let project_arr_meta = @json($arr_project);
    let saleschannel_arr_meta = @json($arr_saleschannel);
    let ConstSaleschannel_arr_meta = @json($arr_saleschannel);
    let ConstProject_arr_meta = @json($arr_project);


    console.log('status ')
    console.log(status_arr_meta)

    console.log('project ')
    console.log(project_arr_meta)

    console.log('saleschannel ')
    console.log(saleschannel_arr_meta)

    let Saved = @json($SavedCreateToArray);


    let addingOff = document.getElementById("adding_off");
    let addingOn = document.getElementById("adding_on");
    let isMouseDown = false;


</script>
