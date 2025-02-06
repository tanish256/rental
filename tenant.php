<?php 
 require "Vhelper.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="root">
        <div class="sidebar">
            <div class="logo">
                <img src="assets/rental.svg" alt="">
                <p>v.01</p>
            </div>
            <nav>
                <ul>
                    <a href="dashboard.php">
                        <li><svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9 22.8272H15C20 22.8272 22 20.8088 22 15.7629V9.70783C22 4.66192 20 2.64355 15 2.64355H9C4 2.64355 2 4.66192 2 9.70783V15.7629C2 20.8088 4 22.8272 9 22.8272Z"
                                    stroke="#9197B3" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M16.2799 14.3601C15.1499 15.5005 13.5299 15.8537 12.0999 15.3996L9.50995 18.0033C9.32995 18.195 8.95995 18.3161 8.68995 18.2758L7.48995 18.1143C7.08995 18.0638 6.72995 17.6804 6.66995 17.2868L6.50995 16.0758C6.46995 15.8134 6.59995 15.44 6.77995 15.2482L9.35995 12.6445C8.91995 11.2014 9.25995 9.56654 10.3899 8.42617C12.0099 6.79129 14.6499 6.79129 16.2799 8.42617C17.8999 10.051 17.8999 12.7152 16.2799 14.3601Z"
                                    stroke="#9197B3" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M10.4501 17.0547L9.6001 16.1868" stroke="#9197B3" stroke-width="1.5"
                                    stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M13.3945 11.4234H13.4035" stroke="#9197B3" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>Dashboard</li>
                    </a>
                    <a href="#">
                        <li class="active"><svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.0006 22.7498H9.00063C7.68063 22.7498 6.58063 22.6198 5.65063 22.3398C5.31063 22.2398 5.09063 21.9098 5.11063 21.5598C5.36063 18.5698 8.39063 16.2197 12.0006 16.2197C15.6106 16.2197 18.6306 18.5598 18.8906 21.5598C18.9206 21.9198 18.7006 22.2398 18.3506 22.3398C17.4206 22.6198 16.3206 22.7498 15.0006 22.7498ZM6.72063 21.0598C7.38063 21.1898 8.13063 21.2498 9.00063 21.2498H15.0006C15.8706 21.2498 16.6206 21.1898 17.2806 21.0598C16.7506 19.1398 14.5606 17.7197 12.0006 17.7197C9.44063 17.7197 7.25063 19.1398 6.72063 21.0598Z"
                                    fill="#9197B3" />
                                <path
                                    d="M15 2H9C4 2 2 4 2 9V15C2 18.78 3.14 20.85 5.86 21.62C6.08 19.02 8.75 16.97 12 16.97C15.25 16.97 17.92 19.02 18.14 21.62C20.86 20.85 22 18.78 22 15V9C22 4 20 2 15 2ZM12 14.17C10.02 14.17 8.42 12.56 8.42 10.58C8.42 8.60002 10.02 7 12 7C13.98 7 15.58 8.60002 15.58 10.58C15.58 12.56 13.98 14.17 12 14.17Z"
                                    stroke="#9197B3" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M11.9999 14.92C9.60992 14.92 7.66992 12.97 7.66992 10.58C7.66992 8.19002 9.60992 6.25 11.9999 6.25C14.3899 6.25 16.3299 8.19002 16.3299 10.58C16.3299 12.97 14.3899 14.92 11.9999 14.92ZM11.9999 7.75C10.4399 7.75 9.16992 9.02002 9.16992 10.58C9.16992 12.15 10.4399 13.42 11.9999 13.42C13.5599 13.42 14.8299 12.15 14.8299 10.58C14.8299 9.02002 13.5599 7.75 11.9999 7.75Z"
                                    fill="#9197B3" />
                            </svg>
                            Tenants</li>
                    </a>
                    <a href="landlords.php">
                        <li><svg class="more" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15 22.75H9C3.57 22.75 1.25 20.43 1.25 15V9C1.25 3.57 3.57 1.25 9 1.25H15C20.43 1.25 22.75 3.57 22.75 9V15C22.75 20.43 20.43 22.75 15 22.75ZM9 2.75C4.39 2.75 2.75 4.39 2.75 9V15C2.75 19.61 4.39 21.25 9 21.25H15C19.61 21.25 21.25 19.61 21.25 15V9C21.25 4.39 19.61 2.75 15 2.75H9Z"
                                    fill="#9197B3" />
                                <path
                                    d="M12.0006 13.0797C11.8706 13.0797 11.7406 13.0497 11.6206 12.9797L6.32061 9.9197C5.96061 9.7097 5.84059 9.2497 6.05059 8.8997C6.26059 8.5397 6.72061 8.4197 7.07061 8.6297L11.9906 11.4797L16.8806 8.6497C17.2406 8.4397 17.7006 8.5697 17.9006 8.9197C18.1006 9.2697 17.9806 9.7397 17.6306 9.9397L12.3706 12.9797C12.2606 13.0397 12.1306 13.0797 12.0006 13.0797Z"
                                    fill="#9197B3" />
                                <path
                                    d="M12 18.5201C11.59 18.5201 11.25 18.1801 11.25 17.7701V12.3301C11.25 11.9201 11.59 11.5801 12 11.5801C12.41 11.5801 12.75 11.9201 12.75 12.3301V17.7701C12.75 18.1801 12.41 18.5201 12 18.5201Z"
                                    fill="#9197B3" />
                                <path
                                    d="M12.0002 18.7498C11.4202 18.7498 10.8503 18.6198 10.3903 18.3698L7.19025 16.5898C6.23025 16.0598 5.49023 14.7898 5.49023 13.6898V10.2998C5.49023 9.20981 6.24025 7.9398 7.19025 7.3998L10.3903 5.6198C11.3103 5.1098 12.6902 5.1098 13.6102 5.6198L16.8102 7.3998C17.7702 7.9298 18.5103 9.19981 18.5103 10.2998V13.6898C18.5103 14.7798 17.7602 16.0498 16.8102 16.5898L13.6102 18.3698C13.1502 18.6298 12.5802 18.7498 12.0002 18.7498ZM12.0002 6.7498C11.6702 6.7498 11.3502 6.8098 11.1202 6.9398L7.92026 8.7198C7.43026 8.9898 6.99023 9.7498 6.99023 10.2998V13.6898C6.99023 14.2498 7.43026 14.9998 7.92026 15.2698L11.1202 17.0498C11.5802 17.3098 12.4202 17.3098 12.8802 17.0498L16.0802 15.2698C16.5702 14.9998 17.0103 14.2398 17.0103 13.6898V10.2998C17.0103 9.73981 16.5702 8.9898 16.0802 8.7198L12.8802 6.9398C12.6502 6.8098 12.3302 6.7498 12.0002 6.7498Z"
                                    fill="#9197B3" />
                            </svg>
                            Landlords</li>
                    </a>
                    <a href="rooms.php"><li><svg class="more" width="24" height="24" viewBox="0 0 512.00 512.00" xmlns="http://www.w3.org/2000/svg" fill="#000000" stroke="#000000" stroke-width="0.00512" transform="rotate(0)matrix(1, 0, 0, 1, 0, 0)"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="11.264"></g><g id="SVGRepo_iconCarrier"> <path fill="var(--ci-primary-color, #000000)" d="M440,424V88H352V13.005L88,58.522V424H16v32h86.9L352,490.358V120h56V456h88V424ZM320,453.642,120,426.056V85.478L320,51Z" class="ci-primary"></path> <rect width="32" height="64" x="256" y="232" fill="var(--ci-primary-color, #000000)" class="ci-primary"></rect> </g></svg>Rooms</li></a>
                    <a href="accounting.php">
                        <li><svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.7516 16.8604V18.8904C10.7516 20.6104 9.15158 22.0004 7.18158 22.0004C5.21158 22.0004 3.60156 20.6104 3.60156 18.8904V16.8604C3.60156 18.5804 5.20158 19.8004 7.18158 19.8004C9.15158 19.8004 10.7516 18.5704 10.7516 16.8604Z"
                                    stroke="#9197B3" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M10.7501 14.11C10.7501 14.61 10.6101 15.07 10.3701 15.47C9.78006 16.44 8.57004 17.05 7.17004 17.05C5.77004 17.05 4.56003 16.43 3.97003 15.47C3.73003 15.07 3.59009 14.61 3.59009 14.11C3.59009 13.25 3.99007 12.48 4.63007 11.92C5.28007 11.35 6.17003 11.01 7.16003 11.01C8.15003 11.01 9.04006 11.36 9.69006 11.92C10.3501 12.47 10.7501 13.25 10.7501 14.11Z"
                                    stroke="#9197B3" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M10.7516 14.11V16.86C10.7516 18.58 9.15158 19.8 7.18158 19.8C5.21158 19.8 3.60156 18.57 3.60156 16.86V14.11C3.60156 12.39 5.20158 11 7.18158 11C8.17158 11 9.06161 11.35 9.71161 11.91C10.3516 12.47 10.7516 13.25 10.7516 14.11Z"
                                    stroke="#9197B3" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M22 10.9699V13.03C22 13.58 21.56 14.0299 21 14.0499H19.0399C17.9599 14.0499 16.97 13.2599 16.88 12.1799C16.82 11.5499 17.0599 10.9599 17.4799 10.5499C17.8499 10.1699 18.36 9.94995 18.92 9.94995H21C21.56 9.96995 22 10.4199 22 10.9699Z"
                                    stroke="#9197B3" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M2 10.5V8.5C2 5.78 3.64 3.88 6.19 3.56C6.45 3.52 6.72 3.5 7 3.5H16C16.26 3.5 16.51 3.50999 16.75 3.54999C19.33 3.84999 21 5.76 21 8.5V9.95001H18.92C18.36 9.95001 17.85 10.17 17.48 10.55C17.06 10.96 16.82 11.55 16.88 12.18C16.97 13.26 17.96 14.05 19.04 14.05H21V15.5C21 18.5 19 20.5 16 20.5H13.5"
                                    stroke="#9197B3" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>Accounting</li>
                    </a>
                    <a href="payment.php">
                        <li><svg class="fill" fill="#000000" height="800px" width="800px" version="1.1" id="Capa_1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                viewBox="0 0 511 511" xml:space="preserve">
                                <g>
                                    <path d="M471.5,28h-432C17.72,28,0,45.72,0,67.5v256C0,345.28,17.72,363,39.5,363h176c4.142,0,7.5-3.358,7.5-7.5
                               s-3.358-7.5-7.5-7.5h-176C25.991,348,15,337.009,15,323.5v-256C15,53.991,25.991,43,39.5,43h432c13.509,0,24.5,10.991,24.5,24.5
                               v256c0,4.142,3.358,7.5,7.5,7.5s7.5-3.358,7.5-7.5v-256C511,45.72,493.28,28,471.5,28z" />
                                    <path
                                        d="M63.5,307h152c4.142,0,7.5-3.358,7.5-7.5s-3.358-7.5-7.5-7.5h-152c-4.142,0-7.5,3.358-7.5,7.5S59.358,307,63.5,307z" />
                                    <path
                                        d="M151,155.5v-32c0-12.958-10.542-23.5-23.5-23.5h-48C66.542,100,56,110.542,56,123.5v32c0,12.958,10.542,23.5,23.5,23.5h48
                               C140.458,179,151,168.458,151,155.5z M71,155.5V147h8.5c4.142,0,7.5-3.358,7.5-7.5s-3.358-7.5-7.5-7.5H71v-8.5
                               c0-4.687,3.813-8.5,8.5-8.5H96v49H79.5C74.813,164,71,160.187,71,155.5z M127.5,164H111v-49h16.5c4.687,0,8.5,3.813,8.5,8.5v8.5
                               h-8.5c-4.142,0-7.5,3.358-7.5,7.5s3.358,7.5,7.5,7.5h8.5v8.5C136,160.187,132.187,164,127.5,164z" />
                                    <path
                                        d="M56,251.5c0,4.142,3.358,7.5,7.5,7.5s7.5-3.358,7.5-7.5v-16c0-4.142-3.358-7.5-7.5-7.5s-7.5,3.358-7.5,7.5V251.5z" />
                                    <path
                                        d="M80,235.5v16c0,4.142,3.358,7.5,7.5,7.5s7.5-3.358,7.5-7.5v-16c0-4.142-3.358-7.5-7.5-7.5S80,231.358,80,235.5z" />
                                    <path
                                        d="M104,235.5v16c0,4.142,3.358,7.5,7.5,7.5s7.5-3.358,7.5-7.5v-16c0-4.142-3.358-7.5-7.5-7.5S104,231.358,104,235.5z" />
                                    <path
                                        d="M128,235.5v16c0,4.142,3.358,7.5,7.5,7.5s7.5-3.358,7.5-7.5v-16c0-4.142-3.358-7.5-7.5-7.5S128,231.358,128,235.5z" />
                                    <path
                                        d="M175,251.5v-16c0-4.142-3.358-7.5-7.5-7.5s-7.5,3.358-7.5,7.5v16c0,4.142,3.358,7.5,7.5,7.5S175,255.642,175,251.5z" />
                                    <path
                                        d="M199,251.5v-16c0-4.142-3.358-7.5-7.5-7.5s-7.5,3.358-7.5,7.5v16c0,4.142,3.358,7.5,7.5,7.5S199,255.642,199,251.5z" />
                                    <path d="M215.5,228c-4.142,0-7.5,3.358-7.5,7.5v16c0,4.142,3.358,7.5,7.5,7.5s7.5-3.358,7.5-7.5v-16
                               C223,231.358,219.642,228,215.5,228z" />
                                    <path
                                        d="M247,251.5v-16c0-4.142-3.358-7.5-7.5-7.5s-7.5,3.358-7.5,7.5v16c0,4.142,3.358,7.5,7.5,7.5S247,255.642,247,251.5z" />
                                    <path d="M415.5,179c21.78,0,39.5-17.72,39.5-39.5S437.28,100,415.5,100h-48c-21.78,0-39.5,17.72-39.5,39.5s17.72,39.5,39.5,39.5
                               H415.5z M343,139.5c0-13.509,10.991-24.5,24.5-24.5h48c13.509,0,24.5,10.991,24.5,24.5S429.009,164,415.5,164h-48
                               C353.991,164,343,153.009,343,139.5z" />
                                    <path d="M473.781,274.948c-1.705-1.179-3.822-1.599-5.848-1.164c-6.816,1.47-13.691,2.216-20.434,2.216
                               c-33.301,0-63.826-16.915-81.652-45.247c-1.373-2.182-3.77-3.506-6.348-3.506s-4.975,1.324-6.348,3.506
                               C335.326,259.085,304.801,276,271.5,276c-6.742,0-13.617-0.746-20.434-2.216c-2.024-0.436-4.143-0.016-5.848,1.164
                               c-1.705,1.179-2.846,3.012-3.151,5.062c-1.372,9.2-2.067,18.449-2.067,27.49c0,78.615,49.25,152.258,117.106,175.108
                               c0.776,0.261,1.585,0.392,2.394,0.392s1.617-0.131,2.394-0.392C429.75,459.758,479,386.115,479,307.5
                               c0-9.041-0.695-18.29-2.067-27.49C476.627,277.959,475.486,276.127,473.781,274.948z M285.252,407.035
                               C265.744,377.584,255,342.235,255,307.5c0-5.801,0.313-11.699,0.932-17.616c5.206,0.742,10.417,1.116,15.568,1.116
                               c30.881,0,59.683-12.584,80.5-34.458v208.044C326.04,453.367,302.616,433.248,285.252,407.035z M433.748,407.035
                               c-17.364,26.213-40.787,46.332-66.748,57.552V256.542C387.817,278.416,416.619,291,447.5,291c5.151,0,10.362-0.375,15.568-1.116
                               c0.619,5.917,0.932,11.815,0.932,17.616C464,342.235,453.256,377.584,433.748,407.035z" />
                                </g>
                            </svg>Payment</li>
                    </a>
                    <a href="administrator.php">
                        <li><svg class="fill" fill="#000000" width="800px" height="800px" viewBox="0 0 36 36"
                                version="1.1" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>administrator-line</title>
                                <path
                                    d="M14.68,14.81a6.76,6.76,0,1,1,6.76-6.75A6.77,6.77,0,0,1,14.68,14.81Zm0-11.51a4.76,4.76,0,1,0,4.76,4.76A4.76,4.76,0,0,0,14.68,3.3Z"
                                    class="clr-i-outline clr-i-outline-path-1"></path>
                                <path
                                    d="M16.42,31.68A2.14,2.14,0,0,1,15.8,30H4V24.22a14.81,14.81,0,0,1,11.09-4.68l.72,0a2.2,2.2,0,0,1,.62-1.85l.12-.11c-.47,0-1-.06-1.46-.06A16.47,16.47,0,0,0,2.2,23.26a1,1,0,0,0-.2.6V30a2,2,0,0,0,2,2H16.7Z"
                                    class="clr-i-outline clr-i-outline-path-2"></path>
                                <path d="M26.87,16.29a.37.37,0,0,1,.15,0,.42.42,0,0,0-.15,0Z"
                                    class="clr-i-outline clr-i-outline-path-3"></path>
                                <path
                                    d="M33.68,23.32l-2-.61a7.21,7.21,0,0,0-.58-1.41l1-1.86A.38.38,0,0,0,32,19l-1.45-1.45a.36.36,0,0,0-.44-.07l-1.84,1a7.15,7.15,0,0,0-1.43-.61l-.61-2a.36.36,0,0,0-.36-.24H23.82a.36.36,0,0,0-.35.26l-.61,2a7,7,0,0,0-1.44.6l-1.82-1a.35.35,0,0,0-.43.07L17.69,19a.38.38,0,0,0-.06.44l1,1.82A6.77,6.77,0,0,0,18,22.69l-2,.6a.36.36,0,0,0-.26.35v2.05A.35.35,0,0,0,16,26l2,.61a7,7,0,0,0,.6,1.41l-1,1.91a.36.36,0,0,0,.06.43l1.45,1.45a.38.38,0,0,0,.44.07l1.87-1a7.09,7.09,0,0,0,1.4.57l.6,2a.38.38,0,0,0,.35.26h2.05a.37.37,0,0,0,.35-.26l.61-2.05a6.92,6.92,0,0,0,1.38-.57l1.89,1a.36.36,0,0,0,.43-.07L32,30.4A.35.35,0,0,0,32,30l-1-1.88a7,7,0,0,0,.58-1.39l2-.61a.36.36,0,0,0,.26-.35V23.67A.36.36,0,0,0,33.68,23.32ZM24.85,28a3.34,3.34,0,1,1,3.33-3.33A3.34,3.34,0,0,1,24.85,28Z"
                                    class="clr-i-outline clr-i-outline-path-4"></path>
                                <rect x="0" y="0" width="36" height="36" fill-opacity="0" />
                            </svg>Administrator</li>
                    </a>
                </ul>
            </nav>
        </div>
        <div class="dashmain">

            <div class="summary">

                <!-- ...................................summary.................................. -->
                <div class="sum">
                    <div class="circle">
                        <img src="assets/profile-2user.svg" alt="">
                    </div>
                    <div class="inf">
                        <h3>Total Tenants</h3>
                        <h4><?php echo $ttenants?></h4>
                        <p>this month</p>
                    </div>
                </div>

                <div class="sum">
                    <div class="circle">
                        <img src="assets/profile-tick.svg" alt="">
                    </div>
                    <div class="inf">
                        <h3>Total landlords</h3>
                        <h4><?php echo $tlandlords?></h4>
                        <p>this month</p>
                    </div>
                </div>

                <div class="sum">
                    <div class="circle">
                        <img src="assets/monitor.svg" alt="">
                    </div>
                    <div class="inf">
                        <h3>Vacant Rooms</h3>
                        <h4><?php echo count($roomsWithoutTenant)?></h4>
                        <p>this month</p>
                    </div>
                </div>

            </div>
            <!-- ...................................summary.................................. -->

            <!-- ----------------------------------table-------------------------------------------- -->
            <div class="tablecard">
                <div class="tops">
                    <div class="headers">
                        <h1>Tenants</h1>
                        <p>active Tenants</p>
                    </div>
                    <div class="right">
                        <input type="text" id="search" placeholder="Search..." onkeyup="filterTable()">
                        <div class="sort-component">
                            <label for="sort-options" class="sort-label">Sort_by:</label>
                            <select id="sort-options" class="sort-select" onchange="sortTable()">
                                <option value="name-asc">Name</option>
                                <option value="landlord-asc">Landlord</option>
                                <option value="status-asc">Status</option>
                                <option value="balance-asc">Balance</option>
                            </select>
                        </div>
                    </div>
                </div>

                <table id="tenantTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>landlord</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Balance</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                            // Loop through tenants and output the table rows
                            foreach ($tenants as $tenant) {
                                // Get room data from $rooms array
                                $room = getRoom($tenant['room_id'], $rooms);
                                $location = $room['location'];
                                $landlord = getLandlord($room['landlord'], $landlords);
                                $balances = getBalance($tenant['id'],date("M"),date("Y"));
                                $balance = isset($balances[0]['total_balance']) ? $balances[0]['total_balance'] : 0;
                                $tenant['balance'] = $balance;
                                echo "<tr class='TReport' onclick='TReport({$tenant['id']})'>";
                                echo "<td>{$tenant['name']}</td>";
                                echo "<td>{$landlord['name']}</td>";
                                echo "<td>{$tenant['contact']}</td>";
                                echo "<td>{$location}</td>";
                                echo "<td>ugx " . number_format($tenant['balance'], 0, '.', ',') . "</td>";
                                if ($tenant['balance'] <= 0) {
                                    echo "<td class='status-active'><div>cleared</div></td>";
                                } else {
                                    echo "<td class='status-inactive'><div>pending</div></td>";
                                }
                                echo "</tr>";
                            }
                            ?>
                    </tbody>
                </table>
            </div>
            <!-- ----------------------------------table-------------------------------------------- -->

        </div>
    </div>
    <div class="Tparent tenant edit">
        <div class="card">
            <div class="x" id="xt">x</div>
            <h2>Tenant Information</h2>
            <form action="">
                <input type="text" name="" id="tid" placeholder="Id" disabled>
                <input type="text" name="" id="tname" placeholder="Tenant Name" disabled>
                <input type="text" name="" id="tcontact" placeholder="Contact" disabled>
                <input type="text" name="" id="tlocation" placeholder="location" disabled>
                <input type="text" name="" id="tbalance" placeholder="Balance" disabled>
                <input type="text" name="" id="troom" placeholder="Room Id" disabled>
                <input type="text" name="" id="tlandlord" placeholder="Landlord" disabled>
                <input type="text" name="" id="tdate" placeholder="date registered" disabled>
            </form>
            <a id="thistory" target="_blank" href="transhistry.php?tenant=2" >Transaction History</a>
        </div>

    </div>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/filter.js"></script>
</body>

</html>