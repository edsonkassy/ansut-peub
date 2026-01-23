@props(['name' => 'region', 'id' => 'region', 'required' => false, 'value' => null, 'class' => '', 'placeholder' => 'Sélectionnez votre région'])

@php
    use App\Helpers\RegionHelper;
    $regions = RegionHelper::getRegions();
@endphp

<select 
    name="{{ $name }}" 
    id="{{ $id }}" 
    {{ $required ? 'required' : '' }}
    {{ $attributes->merge(['class' => "block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm " . $class]) }}
>
    <option value="">{{ $placeholder }}</option>
    @foreach($regions as $regionKey => $regionName)
        <option value="{{ $regionKey }}" {{ old($name, $value) == $regionKey ? 'selected' : '' }}>
            {{ $regionName }}
        </option>
    @endforeach
</select> 