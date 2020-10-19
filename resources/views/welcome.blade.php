<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BOTolye15</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <style>
        .composer-outdated-table, .composer-outdated-text {
            visibility: hidden;
        }

        .npm-outdated-table, .npm-outdated-text {
            visibility: hidden;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>BOTolye15</h1>
    <h3>Check outdated packages for PHP and Nodejs projects.</h3>
    <form method="POST" action="{{ route('outdated.checker')  }}">
        @csrf
        <div class="form-group">
            <label for="exampleInputPassword1">Repository</label>
            <input type="text" name="repository" class="form-control" id="exampleInputPassword1"
                   placeholder="https://github.com/composer/composer">
            @error('repository')
            <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Email address(es)</label>
            <input type="text" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                   placeholder="johndoe@gmail.com, jennydoe@hotmail.com">
            @error('email')
            <p class="text-danger">{{ $message }}</p>
            @enderror
            <small id="emailHelp" class="form-text text-muted">Please add "," to seperate email addressed.</small>
        </div>
        <button type="submit" class="btn btn-primary">Check</button>
    </form>
    <br>
    <div class="row">
        <p class="composer-notification text-success"></p>
        <p class="npm-notification"></p>
        <div class="col-sm composer-outdated-packages">
            <h2 class="text-center composer-outdated-text">Outdated Composer Packages</h2>
            <table class="table composer-outdated-table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Vendor</th>
                    <th scope="col">Package</th>
                    <th scope="col">Version</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="col-sm npm-outdated-packages">
            <h2 class="text-center npm-outdated-text">Outdated NPM Packages</h2>
            <table class="table npm-outdated-table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Vendor</th>
                    <th scope="col">Package</th>
                    <th scope="col">Version</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
<script>
    $(document).ready(function () {
        $('form').on('submit', function () {
            $('.composer-outdated-table, .composer-notification').css({'visibility': 'hidden'})
            const formData = $(this).serializeArray();
            $.ajax({
                'method': 'POST',
                'url': '/list/outdated-packages',
                'data': formData,
                'success': function (response) {
                    $('.composer-notification').css({'visibility': 'visible'})
                    var data = response.data;

                    if (typeof data.composer_outdated !== 'undefined' && data.composer_outdated.length === 0) {
                        if (data.composer_package_found) {
                            $('.composer-notification').text('No outdated package found');
                        } else {
                            $('.composer-notification').text('No composer package found');
                        }
                    } else {
                        $('.composer-outdated-table').css({'visibility': 'visible'})
                        var outputHtml = ``;
                        data.composer_outdated.forEach(function (outdated) {
                            outputHtml += `<tr>
                                <td>${outdated.vendor}</atd>
                                <td>${outdated.package}</td>
                                <td>${outdated.version}</td>
                            </tr>`
                        });

                        $('.composer-outdated-table tbody').append(outputHtml);
                    }
                },
            });
            return false;
        });
    });
</script>
</html>
