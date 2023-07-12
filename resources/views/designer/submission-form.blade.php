<h3 class="mb-4 text-gray-700">Submission details</h3>
<form action="{{ route('store.submission') }}" id="submission-form" method="POST" class="flex flex-col">
    @csrf

    <input type="hidden" name="submission_code" value="{{$submission->submission_code}}">

    <x-input-label for="assembly_name" :value="__('Assembly Name')" />
    <x-text-input id="assembly_name" name="assembly_name" type="text" class="my-1 block w-full" required/>

    <div class="flex flex-col sm:flex-row sm:gap-5">
        <div class="w-full">
            <x-input-label for="machine_number" :value="__('Machine number')" />
            <x-text-input id="machine_number" name="machine_number" type="text" required class="my-1 block w-full"/>
        </div>
        <div class="w-full">
            <x-input-label for="submission_type" :value="__('Submission type')" />
            <select class="w-full my-1 field-normal" id="submission_type" name="submission_type" required>
                <option value="">--Please select--</option>
                @foreach ($submission_types as $key => $submission_type)
                    <option value="{{$key}}">{{$submission_type}}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full">
            <x-input-label for="current_unit_number" :value="__('Current unit number')" />
            <select class="w-full my-1 field-normal" name="current_unit_number" id="current_unit_number" required>
                <option value="">--Please select--</option>
                @foreach ($unit_numbers as $key => $unit_number)
                    <option value="{{$key}}">{{$unit_number}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <x-input-label for="notes" :value="__('Notes')" />
    <textarea name="notes" cols="30" rows="6" class="w-full my-1 border-gray-300 focus:border-indigo-500
    focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Any additional info..."></textarea>
</form>
