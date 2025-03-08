<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../build/css/style.css">
</head>

<body class="font-poppins">
    <main class="mx-auto sm:mx-10">
        <div class="min-h-screen text-gray-900 flex justify-center">
            <div
                class="max-w-screen-xl m-0 sm:m-10 bg-white shadow-xl sm:rounded-lg flex flex-col lg:flex-row justify-center flex-1">
                <div class="lg:w-1/2 xl:w-5/12 p-6 sm:p-12">
                    <div class="flex flex-col md:flex-row justify-center items-center text-center mb-4 md:mb-0">
                        <div class="bg-green-400 w-10 h-10 rounded-full shadow-lg"></div>
                        <h1 class="text-3xl text-green-400 ml-2 font-bold">BartGain</h1>
                    </div>
                    <div class="mt-12 flex flex-col items-center">
                        <div class="w-full flex-1 mt-8">
                            <div class="flex flex-col items-center">
                                <button
                                    class="w-full max-w-xs font-bold shadow-sm rounded-lg py-3 bg-green-100 text-gray-800 flex items-center justify-center transition-all duration-300 ease-in-out focus:outline-none hover:shadow focus:shadow-sm focus:shadow-outline">
                                    <div class="bg-white p-2 rounded-full">
                                        <svg class="w-4" viewBox="0 0 533.5 544.3">
                                            <path
                                                d="M533.5 278.4c0-18.5-1.5-37.1-4.7-55.3H272.1v104.8h147c-6.1 33.8-25.7 63.7-54.4 82.7v68h87.7c51.5-47.4 81.1-117.4 81.1-200.2z"
                                                fill="#4285f4" />
                                            <path
                                                d="M272.1 544.3c73.4 0 135.3-24.1 180.4-65.7l-87.7-68c-24.4 16.6-55.9 26-92.6 26-71 0-131.2-47.9-152.8-112.3H28.9v70.1c46.2 91.9 140.3 149.9 243.2 149.9z"
                                                fill="#34a853" />
                                            <path
                                                d="M119.3 324.3c-11.4-33.8-11.4-70.4 0-104.2V150H28.9c-38.6 76.9-38.6 167.5 0 244.4l90.4-70.1z"
                                                fill="#fbbc04" />
                                            <path
                                                d="M272.1 107.7c38.8-.6 76.3 14 104.4 40.8l77.7-77.7C405 24.6 339.7-.8 272.1 0 169.2 0 75.1 58 28.9 150l90.4 70.1c21.5-64.5 81.8-112.4 152.8-112.4z"
                                                fill="#ea4335" />
                                        </svg>
                                    </div>
                                    <span class="ml-4">Sign In with Google</span>
                                </button>
                            </div>
                            <div class="my-12 border-b text-center">
                                <div
                                    class="leading-none px-2 inline-block text-sm text-gray-600 tracking-wide font-medium bg-white transform translate-y-1/2">
                                    Or sign In with Cartesian E-mail</div>
                            </div>
                            <div class="mx-auto max-w-xs">
                                <form id="signInForm">
                                    <input
                                        name="email"
                                        class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                        type="email" placeholder="Email" />
                                    <input
                                        name="password"
                                        class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white mt-5"
                                        type="password" placeholder="Password" />
                                        
                                </form>
                                <button
                                    id="btnSignIn"
                                    class="mt-5 tracking-wide font-semibold bg-green-400 text-white w-full py-4 rounded-lg hover:bg-green-700 transition-all duration-300 ease-in-out flex items-center justify-center focus:shadow-outline focus:outline-none">
                                    <svg class="w-6 h-6 -ml-2" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                        <circle cx="8.5" cy="7" r="4" />
                                        <path d="M20 8v6M23 11h-6" />
                                    </svg>
                                    <span class="ml-3">Sign In</span>
                                </button>
                                <p class="mt-6 text-sm text-gray-400 text-center">
                                    <input type="checkbox" id="terms" name="terms" class="inline-block">
                                    <label for="terms">
                                        I agree to abide by Cartesian Kinetics
                                        <a href="#" class="border-b border-gray-500 border-dotted">Terms of Service</a> and
                                        its
                                        <a href="#" class="border-b border-gray-500 border-dotted">Privacy Policy</a>
                                    </label>
                                </p>
                                <div class="mt-4 text-sm text-gray-400 text-center">
                                    <p>Don't have an account yet?<span class="text-green-500 ml-1 font-semibold"><a href="sign-up.php">Sign up</a></span></p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-1 bg-green-100 text-center hidden lg:flex">
                    <div class="m-12 xl:m-16 w-full bg-contain bg-center bg-no-repeat">
                        <img src="img/barter.png" class="my-20 mx-10" alt="Background Image">
                    </div>
                </div>
                
            </div>
        </div>
    </main>


    <script src="../js/plugins/jquery/jquery.js"></script>
    <script src="../js/plugins/sweetalert2/swal.js"></script>

    <script>
        $(document).on('click', '#btnSignIn', function(e) {
            e.preventDefault()
            let formData = $('#signInForm').serializeArray()
            $.ajax({
                    method: 'POST',
                    url: "/bart-gain/include/ajax/authentication.php?function=sign-in",
                    data: formData,
                    dataType: "JSON",
                    beforeSend: function () {
                        
                    }
                }).done(function (data) {
                    Swal.fire({
                        icon: data.status,
                        title: data.title,
                        showConfirmButton: true
                    });
                    if (data.status === 'success') {
                        window.location.href = '../pages/index.php';
                    }
                });
        })

    </script>
</body>

</html>