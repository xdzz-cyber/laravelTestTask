<div class="border p-5">
    <h2>Add position</h2>
    <form class="p-5" wire:submit.prevent="addPosition">
        <div class="mb-3">
            <label for="positionName" class="form-label">Position name</label>
            <input type="text" class="form-control" id="positionName" aria-describedby="positionName" wire:model="positionName">
            @error("positionName")
                <p class="text-danger">{{$message}}</p>
            @enderror
            <div id="positionName2" class="form-text">{{$currentNameLengthCounter}} / {{$maxLength}}</div>
        </div>
        <a href="{{route('positions')}}" class="btn btn-danger mx-1">Cancel</a>
        <button type="submit" class="btn btn-primary mx-1">Save</button>
    </form>
</div>
