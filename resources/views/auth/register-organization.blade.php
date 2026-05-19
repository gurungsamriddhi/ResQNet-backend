<x-guest-layout>

    <h2>Register Organization</h2>

    <form method="POST" action="{{ route('register.organization') }}" enctype="multipart/form-data">
        @csrf

        <h3>User Details</h3>

        <div>
            <label for="user_name">Full Name</label>
            <input id="user_name" type="text" name="user_name" value="{{ old('user_name') }}" required>
        </div>

        <div>
            <label for="user_email">Email Address</label>
            <input id="user_email" type="email" name="user_email" value="{{ old('user_email') }}" required>
        </div>

        <div>
            <label for="user_phone">Phone Number</label>
            <input id="user_phone" type="text" name="user_phone">
        </div>

        <div>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
        </div>

        <div>
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>

        <hr>

        <h3>Organization Details</h3>

        <div>
            <label for="org_name">Organization Name</label>
            <input id="org_name" type="text" name="org_name" required>
        </div>

        <div>
            <label for="org_email">Organization Public Email</label>
            <input id="org_email" type="email" name="org_email" required>
        </div>

        <div>
            <label for="registration_number">Registration Number</label>
            <input id="registration_number" type="text" name="registration_number" required>
        </div>

        <div>
            <label for="document">Registration Document</label>
            <input id="document" type="file" name="document" required>
        </div>

        <button type="submit">
            Submit Registration
        </button>

    </form>

</x-guest-layout>