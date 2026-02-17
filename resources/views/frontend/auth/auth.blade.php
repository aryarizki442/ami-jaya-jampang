<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Auth')</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">


    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"rel="stylesheet">

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="{{ asset('css/color.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/login.css') }}">


</head>

<body>
    @yield('auth')

    <script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
    <script>
        document.addEventListener("click", function(e) {
            const toggle = e.target.closest(".password-toggle");
            if (!toggle) return;

            const input = document.getElementById(toggle.dataset.target);
            const isHidden = input.type === "password";

            // toggle input type
            input.type = isHidden ? "text" : "password";

            // update icon setelah klik
            toggle.innerHTML = `
        <span class="iconify" data-icon="${
            isHidden
                ? "weui:eyes-on-filled"
                : "weui:eyes-off-outlined"
        }"></span>
    `;
        });

        // saat user mengetik password
        document.addEventListener("input", function(e) {
            if (e.target.type !== "password" && e.target.type !== "text") return;

            const wrapper = e.target.closest(".password-wrapper");
            if (!wrapper) return;

            const toggle = wrapper.querySelector(".password-toggle");
            if (!toggle) return;

            // kalau ada isi, icon jadi off-outlined
            if (e.target.value.length > 0) {
                toggle.innerHTML = `
            <span class="iconify" data-icon="weui:eyes-off-outlined"></span>
        `;
            } else {
                toggle.innerHTML = `
            <span class="iconify" data-icon="weui:eyes-on-filled"></span>
        `;
            }
        });
    </script>
</body>

</html>
