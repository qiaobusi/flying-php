<!DOCTYPE html>
<html>
    <head>
        <title>生活助手</title>

        <style>
            html, body {
                height: 100%;
            }
            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }
            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }
            .content {
                text-align: center;
                display: inline-block;
            }
            .logo {
                margin-bottom: 20px;
            }
            .title {
                font-size: 50px;
            }
        </style>

    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="logo">
                    <img src="{{ asset("assets/images/logo.png") }}">
                </div>
                <div class="title">生活助手</div>
            </div>
        </div>
    </body>
</html>
