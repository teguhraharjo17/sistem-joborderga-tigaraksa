<x-auth-layout>
    @section('title', 'Register Page')
    <!--begin::Form-->
    <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form" data-kt-redirect-url="{{ route('login') }}" action="{{ route('admin.register') }}" method="POST">
        @csrf

        <!--begin::Heading-->
        <div class="text-center mb-11">
            <h1 class="text-dark fw-bolder mb-3">Sign Up</h1>
            <div class="text-gray-500 fw-semibold fs-6">Sistem JobOrder GA Tigaraksa</div>
        </div>

        <!--begin::Separator-->
        <div class="separator separator-content my-14"></div>

        <!--begin::Input group: Name-->
        <div class="fv-row mb-8">
            <input type="text" placeholder="Name" name="name" autocomplete="off" class="form-control bg-transparent" required />
        </div>

        <!--begin::Input group: Username-->
        <div class="fv-row mb-8">
            <input type="text" placeholder="Username" name="username" autocomplete="off" class="form-control bg-transparent" required />
        </div>

        <!--begin::Input group: Role (dropdown)-->
        <div class="fv-row mb-8">
            <select name="role" class="form-select bg-transparent" required>
                <option value="" selected hidden>Select Role</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
                <option value="super admin">Super Admin</option>
            </select>
        </div>

        <!--begin::Input group: Password-->
        <div class="fv-row mb-8" data-kt-password-meter="true">
            <!--begin::Wrapper-->
            <div class="mb-1">
                <!--begin::Input wrapper-->
                <div class="position-relative mb-3">
                    <input class="form-control bg-transparent" type="password" placeholder="Password" name="password" autocomplete="off" id="password" required />
                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-4" onclick="togglePassword('password', 'eye1')" style="cursor:pointer;">
                        <i id="eye1" class="bi bi-eye-slash fs-2"></i>
                    </span>
                </div>
                <!--end::Input wrapper-->

                <!--begin::Meter-->
                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                </div>
                <!--end::Meter-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Hint-->
            <div class="text-muted">
                Use 8 or more characters with a mix of letters, numbers & symbols.
            </div>
            <!--end::Hint-->
        </div>

        <!--begin::Input group: Repeat Password-->
        <div class="fv-row mb-8 position-relative">
            <input class="form-control bg-transparent" type="password" placeholder="Repeat Password" name="password_confirmation" autocomplete="off" id="password_confirmation" required />
            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-4" onclick="togglePassword('password_confirmation', 'eye2')" style="cursor:pointer;">
                <i id="eye2" class="bi bi-eye-slash fs-2"></i>
            </span>
        </div>

        <!--begin::Submit button-->
        <div class="d-grid mb-10">
            <button type="submit" id="kt_sign_up_submit" class="btn btn-primary">
                @include('partials/general/_button-indicator', ['label' => 'Sign Up'])
            </button>
        </div>

        <!--begin::Sign in link-->
        <div class="text-gray-500 text-center fw-semibold fs-6">
            Already have an Account?
            <a href="{{ route('login') }}" class="link-primary fw-semibold">Sign in</a>
        </div>
    </form>

    <!--begin::Toggle password script-->
    <script>
        function togglePassword(fieldId, iconId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            }
        }
    </script>
</x-auth-layout>
