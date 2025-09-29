<x-auth-layout>
    @section('title', 'Login Page')
    <!--begin::Form-->
    <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" data-kt-redirect-url="{{ url('/') }}" action="{{ route('login') }}" method="POST">
        @csrf

        <!--begin::Heading-->
        <div class="text-center mb-11">
            <h1 class="text-dark fw-bolder mb-3">Sign In</h1>
            <div class="text-gray-500 fw-semibold fs-6">Sistem JobOrder GA Tigaraksa</div>
        </div>

        <!--begin::Separator-->
        <div class="separator separator-content my-14">
        </div>

        <!--begin::Input group: Username-->
        <div class="fv-row mb-8">
            <input type="text" placeholder="Username" name="username" autocomplete="off" class="form-control bg-transparent"/>
        </div>

        <!--begin::Input group: Password-->
        <div class="fv-row mb-3 position-relative">
            <input type="password" placeholder="Password" name="password" autocomplete="off" class="form-control bg-transparent" id="password-field"/>

            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-4" onclick="togglePasswordVisibility()" style="cursor: pointer;">
                <i id="eye-icon" class="bi bi-eye-slash fs-2"></i>
            </span>
        </div>

        <!--begin::Submit button-->
        <div class="d-grid mb-10">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                @include('partials/general/_button-indicator', ['label' => 'Sign In'])
            </button>
        </div>
    </form>
    <!--end::Form-->

    <!--begin::Password toggle script-->
    <script>
        function togglePasswordVisibility() {
            const input = document.getElementById("password-field");
            const icon = document.getElementById("eye-icon");

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
