<div>
  <x-form-group error="{{ $errors->first('profile.name.first') }}">
    <m-text-field label='First Name' name='profile[name][first]'></m-text-field>
  </x-form-group>

  <x-form-group error="{{ $errors->first('profile.name.last') }}">
    <m-text-field label='Last Name' name='profile[name][last]'></m-text-field>
  </x-form-group>
</div>
