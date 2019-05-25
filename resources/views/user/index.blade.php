@extends('domain::domain.security')

@section('content')
<div class='container'>
  <m-card>
    <template #default>
      <table class='table'>
        <thead>
          <tr>
            <th>Username</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
          <tr>
            <td>
              {{ $user->photo }}
              <img height='64' src='/avatar.png' />
              <a href="{{ route('admin.users.show', $user) }}">{{ $user->username }}</a>
            </td>
            <td>{{ $user->email }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </template>
    <template #actions>
      {{ $users->links('material::components.pagination') }}
    </template>
  </m-card>
</div>
@endsection
