<div class="border p-5">
    <h2>Edit position</h2>
    <form class="p-5" wire:submit.prevent="editPosition">
        <div class="mb-3">
            <label for="positionName" class="form-label">Position name</label>
            <input type="text" class="form-control" id="positionName" aria-describedby="positionName" wire:model="positionName">
            <input type="hidden" class="form-control" wire:model="positionId" value="{{$positionId}}">
            @error("positionName")
            <p class="text-danger">{{$message}}</p>
            @enderror
            <div id="positionName2" class="form-text">{{$currentNameLengthCounter}} / {{$maxLength}}</div>
            <div class="row my-4">
                <div class="col-md-6">
                    <h5>Created at</h5>
                    <p class="text-black-50">{{\Carbon\Carbon::parse($created_at)->format("d-m-Y")}}</p>
                </div>
                <div class="col-md-6">
                    <h5>Admin created id</h5>
                    <p class="text-black-50">{{$admin_created_id}}</p>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-md-6">
                    <h5>Updated at</h5>
                    <p class="text-black-50">{{\Carbon\Carbon::parse($updated_at)->format("d-m-Y")}}</p>
                </div>
                <div class="col-md-6">
                    <h5>Admin updated id</h5>
                    <p class="text-black-50">{{$admin_updated_id}}</p>
                </div>
            </div>
        </div>
        <a href="{{route('positions')}}" class="btn btn-danger mx-1">Cancel</a>
        <button type="submit" class="btn btn-primary mx-1">Save</button>
    </form>
</div>
