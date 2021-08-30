<div class="border p-5">
    <h2>Add employee</h2>
    <form class="p-5" wire:submit.prevent="addEmployee" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <input type="file" wire:model="photo">
            @if($photo)
                <img src="{{$photo->temporaryUrl()}}" alt="user chosen image" width="300" height="300">
            @endif
            @error("photo")
            <p class="text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="fullname" class="form-label">Employee fullname</label>
            <input type="text" class="form-control" id="fullname" aria-describedby="name" wire:model="fullname">
            @error("fullname")
            <p class="text-danger">{{$message}}</p>
            @enderror
            <div id="name2" class="form-text">{{$currentNameLengthCounter}} / {{$maxLength}}</div>
        </div>
        <div class="mb-3">
            <label for="positionName" class="form-label">Phone</label>
            <input type="tel" class="form-control" id="phone" aria-describedby="phone" wire:model="phone">
            @error("phone")
            <p class="text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" aria-describedby="email" wire:model="email">
            @error("email")
            <p class="text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="position" class="form-label">Position</label>
            <input type="text" class="form-control" id="position" aria-describedby="position" wire:model="position">
            @error("position")
            <p class="text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="salary" class="form-label">Salary, $</label>
            <input type="number" class="form-control" id="salary" aria-describedby="salary" wire:model="salary">
            @error("salary")
            <p class="text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="head" class="form-label">Head</label>
            <input type="text" class="form-control" id="head" aria-describedby="head" wire:model="head">
            @error("head")
            <p class="text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="dateOfEmployment" class="form-label">Date of employment</label>
            <input type="date" class="form-control" id="dateOfEmployment" aria-describedby="dateOfEmployment" wire:model="dateOfEmployment">
            @error("dateOfEmployment")
            <p class="text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mt-5">
            <a href="{{route('positions')}}" class="btn btn-danger mx-1">Cancel</a>
            <button type="submit" class="btn btn-primary mx-1">Save</button>
        </div>

    </form>
</div>
