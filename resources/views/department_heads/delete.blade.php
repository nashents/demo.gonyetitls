<div data-backdrop="static" data-keyboard="false" class="modal fade" id="department_headDeleteModal{{$department_head->id}}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-danger">
            <div class="modal-body">
               <center> <strong>Are you sure you want to remove this Deparment Head?</strong> </center> 
            </div>
            <form method="POST" action="{{route('department_heads.destroy', $department_head->id)}}">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="DELETE">
            <div class="modal-footer no-border">
                <div class="btn-group" role="group">
                    <button type="button" class="btn bg-white btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                    <button onClick="this.form.submit(); this.disabled=true; this.value='Sending…'; " class="btn bg-black btn-wide btn-rounded" ><i class="fa fa-trash"></i>Delete</button>
                </div>
                <!-- /.btn-group -->
            </div>
        </form>
        </div>
    </div>
</div>