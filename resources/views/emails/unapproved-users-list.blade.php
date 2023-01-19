<h1>{{ __('messages.admin.unapproved_user_list') }}</h1>
<table border="1">
    <tr>
        <td>S. No</td>
        <td>Name</td>
        <td>Email</td>
        <td>Role</td>
        <td>Created At</td>
    </tr>
    @forelse ($unapprovedUsers as $key => $user)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role_name }}</td>
            <td>{{ $user->created_at }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5">{{ __('messages.admin.unapproved_user') }}</td>
        </tr>
    @endforelse
</table>
